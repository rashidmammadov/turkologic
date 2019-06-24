<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 24.06.2019
 * Time: 11:54
 */

namespace App\Http\Controllers;

use App\Http\Models\Lexeme;
use App\Http\Models\Semantics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class TDKController extends ApiController {

    private $tdkDictionaryUri = 'http://sozluk.gov.tr/gts?ara=';

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
            $response = $this->fetchFromTDK($word);
            return $this->convertFromTDKFormat($response);
        }
    }

    private function convertFromTDKFormat($response) {
        $result = json_decode($response);
        if (isset($result->error)) {
            return $this->respondWithError($result->error);
        } else {
            $data = array();
            $data[LEXEME] = $this->setLexeme($result);
            $data[SEMANTICS] = $this->setSemantics($result);
            return $this->respondCreated('Sonuç getirildi', $data);
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
        $lexeme->setLexeme($result[0]->madde);
        $lexeme->setLatinText($result[0]->madde);
        $lexeme->setPronunciation($result[0]->telaffuz);
        $lexeme->setAlphabet(LATIN);
        return $lexeme->get();
    }

    private function setSemantics($result) {
        $semanticsList = array();
        if ($result[0] && isset($result[0]->anlamlarListe)) {
            $type = null;
            foreach ($result[0]->anlamlarListe as $anlam) {
                $semantics = new Semantics();
                if (isset($anlam->ozelliklerListe)) {
                    $type = $anlam->ozelliklerListe[0]->tam_adi;
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
}
