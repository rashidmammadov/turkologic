<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 24.06.2019
 * Time: 11:54
 */

namespace App\Http\Controllers;

use App\Http\Models\Etymon;
use App\Http\Models\Lexeme;
use App\Http\Models\Semantics;
use App\Http\Models\Source;
use App\Http\Models\WordType;
use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class TDKController extends ApiController {

    private $tdkDictionaryUri = 'https://sozluk.gov.tr/gts?ara=';

    public function __construct() { }

    public function get(Request $request) {
        $rules = array(
            WORD => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            $word = $request[WORD];
            return $this->fetchLexeme($word);
        }
    }

    private function convertFromTDKFormat($response) {
        $result = json_decode($response);
        if (isset($result->error)) {
            return $this->respondWithError($result->error);
        } else {
            $lexeme = $this->setLexeme($result);
            if (isset($lexeme)) {
                $lexeme[ETYMON] = new Etymon();
                $lexeme[SEMANTICS_LIST] = $this->setSemanticsList($result);
            }
            return $this->respondCreated('Sonuç getirildi', $lexeme);
        }
    }

    private function fetchFromTDK($word) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->tdkDictionaryUri . $word);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function setLexeme($result) {
        $lexeme = new Lexeme();
        if (isset($result[0]->madde)) {
            $lexeme->setLanguageId(TUR_ID);
            $lexeme->setLexeme($result[0]->madde);
            $lexeme->setLatinText($result[0]->madde);
            $lexeme->setPronunciation($result[0]->telaffuz);
            $lexeme->setAlphabet(LATIN);
        }
        return $lexeme->get();
    }

    private function setSemanticsList($result) {
        $semanticsList = array();
        if ($result[0] && isset($result[0]->anlamlarListe)) {
            $type = null;
            foreach ($result[0]->anlamlarListe as $anlam) {
                $semantics = new Semantics();
                if (isset($anlam->ozelliklerListe)) {
                    $type = $anlam->ozelliklerListe[0]->tam_adi;
                    $type = WordType::getTypeByName($type);
                }
                $semantics->setType($type);
                $semantics->setMeaning($anlam->anlam);
                if (isset($anlam->orneklerListe)) {
                    $sample = $anlam->orneklerListe[0]->ornek;
                    $semantics->setSample($sample);
                    if (isset($anlam->orneklerListe[0]->yazar)) {
                        $writer = $anlam->orneklerListe[0]->yazar[0]->tam_adi;
                        $semantics->setReference($writer);
                    }
                }
                array_push($semanticsList, $semantics->get());
            }
        }
        return $semanticsList;
    }

    /**
     * @description fetch given word from local db if exist or get form tdk.
     * @param $word - the searched
     * @return mixed
     */
    private function fetchLexeme($word) {
        $fromDB = ApiQuery::checkLexemeIfExist($word);
        if (isset($fromDB) && isset($fromDB[0])) {
            return $this->setLexemeFromDB($fromDB);
        } else {
            $response = $this->fetchFromTDK($word);
            return $this->convertFromTDKFormat($response);
        }
    }

    /**
     * @description fetch lexeme data from db.
     * @param $fromDB - the query result
     * @return mixed
     */
    private function setLexemeFromDB($fromDB) {
        $lexeme = new Lexeme($fromDB[0]);
        $semanticsList = $this->setSemanticsListFromDB($fromDB);
        $lexeme->setSemanticsList($semanticsList);
        $etymon = $this->setLexemeEtymonFromDB($lexeme->getEtymonId());
        $lexeme->setEtymon($etymon->get());
        return $this->respondCreated('Sonuç getirildi', $lexeme->get());
    }

    /**
     * @description fetch etymon data from db.
     * @param {Integer} $etymonId - the etymon id.
     * @return Etymon
     */
    private function setLexemeEtymonFromDB($etymonId): Etymon {
        $queryResult = ApiQuery::getEtymon($etymonId);
        $etymon = new Etymon();
        if (isset($queryResult) && isset($queryResult[0])) {
            $etymon = new Etymon($queryResult[0]);
            $etymonSources = array();
            foreach ($queryResult as $item) {
                $source = new Source($item);
                array_push($etymonSources, $source->get());
            }
            $etymon->setSources($etymonSources);
        }
        return $etymon;
    }

    /**
     * @description fetch semantics list data from db.
     * @param $fromDB - the query result
     * @return array
     */
    private function setSemanticsListFromDB($fromDB): array {
        $semanticsList = array();
        foreach ($fromDB as $item) {
            $semantics = new Semantics($item);
            $semanticsConnects = $this->setSemanticsConnectsFromDB($semantics);
            $semantics->setConnects($semanticsConnects);
            array_push($semanticsList, $semantics->get());
        }
        return $semanticsList;
    }

    /**
     * @description fetch semantics connect data from db.
     * @param Semantics $semantics - the semantics data.
     * @return array
     */
    private function setSemanticsConnectsFromDB(Semantics $semantics): array {
        $semanticsConnects = array();
        $connects = ApiQuery::getBelong($semantics->getSemanticId());
        foreach ($connects as $connect) {
            $connectLexeme = new Lexeme($connect[0]);
            $connectSemanticsList = array();
            foreach ($connect as $semanticData) {
                $connectSemantics = new Semantics($semanticData);
                array_push($connectSemanticsList, $connectSemantics->get());
            }
            $connectLexeme->setSemanticsList($connectSemanticsList);
            array_push($semanticsConnects, $connectLexeme->get());
        }
        return $semanticsConnects;
    }
}
