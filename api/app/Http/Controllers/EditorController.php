<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 08.07.2019
 * Time: 17:07
 */

namespace App\Http\Controllers;

use App\Http\Models\Belong;
use App\Http\Models\Etymon;
use App\Http\Models\Lexeme;
use App\Http\Models\Semantics;
use App\Http\Models\Source;
use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class EditorController extends ApiController {

    public function __construct() { }

    public function post(Request $request) {
        try {
            $this->saveLexeme($request);
            return $this->respondCreated(DATA_SAVED_SUCCESSFULLY);
        } catch (Exception $e) {
            return $this->respondWithError(SOMETHING_WENT_WRONG_WHILE_SAVING_DATA);
        }
    }

    public function put(Request $request) {
        try {
            $this->updateLexeme($request);
            return $this->respondCreated(DATA_UPDATED_SUCCESSFULLY);
        } catch (Exception $e) {
            return $this->respondWithError(SOMETHING_WENT_WRONG_WHILE_SAVING_DATA);
        }
    }

    public function delete(Request $request) {
        $rules = array(
            KEY => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            try {
                if (strtolower(KEY) == 'source') {
                    ApiQuery::deleteSource($request[SOURCE_ID]);
                    return $this->respondCreated('kaynak başarıyla silindi');
                } else if (strtolower(KEY) == 'semantics') {
                    ApiQuery::deleteBelong($request[FROM], $request[TO]);
//                    ApiQuery::deleteSemantics($request[SEMANTIC_ID]);
                    return $this->respondCreated('anlam başarıyla silindi');
                }
            } catch (Exception $e) {
                return $this->respondWithError(SOMETHING_WENT_WRONG_WHILE_SAVING_DATA);
            }
        }
    }

    /**
     * @description save the dialect lexeme of saved semantics.
     * @param {Array} $connects - semantics connects data
     * @param {Integer} $semanticId - parent semantics id
     * @return void
     */
    private function saveDialectLexemes($connects, $semanticId): void {
        if (isset($connects)) {
            foreach ($connects as $connect) {
                $dialectLexemeId = null;
                if (empty($connect[LEXEME_ID]) || is_null($connect[LEXEME_ID])) {
                    $dialectLexeme = new Lexeme($connect);
                    $dialectLexeme->setLatinText($this->convertToLatinText($dialectLexeme->getPronunciation()));

                    $checkDialectLexeme = ApiQuery::checkLexemeWithoutId($dialectLexeme->get());
                    if (isset($checkDialectLexeme)) {
                        $dialectLexemeId = $checkDialectLexeme[LEXEME_ID];
                    } else {
                        $queryResult = ApiQuery::saveLexeme($dialectLexeme->get());
                        $dialectLexemeId = $queryResult[LEXEME_ID];
                    }
                } else {
                    $dialectLexemeId = $connect[LEXEME_ID];
                }
                $this->saveDialectSemanticsList($connect[SEMANTICS_LIST], $dialectLexemeId, $semanticId);
            }
        }
    }

    /**
     * @description save the semantics list of saved dialect lexeme.
     * @param {Array} $semanticsList - dialect array of semantics.
     * @param {Integer} $dialectLexemeId - parent lexeme id.
     * @param {Integer} $semanticId - parent semantics id.
     * @return void
     */
    private function saveDialectSemanticsList($semanticsList, $dialectLexemeId, $semanticId): void {
        if (isset($semanticsList)) {
            foreach ($semanticsList as $connectSemantics) {
                $dialectSemanticId = null;
                if (empty($connectSemantics[SEMANTIC_ID]) || is_null($connectSemantics[SEMANTIC_ID])) {
                    $dialectSemantics = new Semantics($connectSemantics);
                    $dialectSemantics->setLexemeId($dialectLexemeId);

                    $checkDialectSemantics = ApiQuery::checkSemanticsWithoutId($dialectSemantics->get());
                    if (isset($checkDialectSemantics)) {
                        $dialectSemanticId = $checkDialectSemantics[SEMANTIC_ID];
                    } else {
                        $queryResult = ApiQuery::saveSemantics($dialectSemantics->get());
                        $dialectSemanticId = $queryResult[SEMANTIC_ID];
                    }
                } else {
                    $dialectSemanticId = $connectSemantics[SEMANTIC_ID];
                }
                $this->saveBelong($semanticId, $dialectSemanticId);
            }
        }
    }

    /**
     * @description save the new lexeme and related semantics with given parameters.
     * @param {Request} $request - semantics data
     * @return void
     */
    private function saveLexeme($request): void {
        $etymonId = $this->saveEtymon($request);
        $lexemeId = null;
        $newLexeme = new Lexeme($request);
        $newLexeme->setEtymonId($etymonId);
        $newLexeme->setLatinText($this->convertToLatinText($newLexeme->getPronunciation()));
        if (is_null($newLexeme->getPronunciation())) {
            $newLexeme->setPronunciation($newLexeme->getLexeme());
        }

        $checkLexeme = ApiQuery::checkLexemeWithoutId($newLexeme->get());
        if (isset($checkLexeme)) {
            $lexemeId = $checkLexeme[LEXEME_ID];
        } else {
            $queryResult = ApiQuery::saveLexeme($newLexeme->get());
            $lexemeId = $queryResult[LEXEME_ID];
        }
        $this->saveSemanticsList($request[SEMANTICS_LIST], $lexemeId);
    }

    /**
     * @description save the related lexeme`s semantics list and dialects.
     * @param {Request} $semanticsList - the lexeme`s semantics list.
     * @param {Integer} $lexemeId - saved lexeme id
     */
    private function saveSemanticsList($semanticsList, $lexemeId): void {
        if (isset($semanticsList)) {
            foreach ($semanticsList as $semantics) {
                $semanticId = null;
                $newSemantics = new Semantics($semantics);
                $newSemantics->setLexemeId($lexemeId);

                $checkSemantics = ApiQuery::checkSemanticsWithoutId($newSemantics->get());
                if (isset($checkSemantics)) {
                    $semanticId = $checkSemantics[SEMANTIC_ID];
                } else {
                    $queryResult = ApiQuery::saveSemantics($newSemantics->get());
                    $semanticId = $queryResult[SEMANTIC_ID];
                }
                $this->saveDialectLexemes($semantics[CONNECTS], $semanticId);
            }
        }
    }

    /**
     * @description save the belong to relation from dialect to semantics.
     * @param {Integer} $semanticId - semantics id
     * @param {Integer} $dialectSemanticId - dialect semantics id
     */
    private function saveBelong($semanticId, $dialectSemanticId): void {
        $belong = new Belong();
        $belong->setFrom($dialectSemanticId);
        $belong->setTo($semanticId);

        $checkBelong = ApiQuery::checkBelongWithoutId($belong->get());
        if (!isset($checkBelong)) {
            ApiQuery::saveBelong($belong->get());
        }
    }

    /**
     * @description save the etymon data and return created etymon`s id.
     * @param {Request} $request - request data.
     * @return mixed
     */
    private function saveEtymon($request) {
        $etymonId = null;
        if (isset($request[ETYMON])) {
            $newEtymon = new Etymon($request[ETYMON]);

            $checkEtymon = ApiQuery::checkEtymonWithoutId($newEtymon->get());
            if (isset($checkEtymon)) {
                $etymonId = $checkEtymon[ETYMON_ID];
            } else {
                $queryResult = ApiQuery::saveEtymon($newEtymon->get());
                $etymonId = $queryResult[ETYMON_ID];
            }
            $this->saveSource($request[ETYMON][SOURCES], $etymonId);
        }
        return $etymonId;
    }

    /**
     * @description save the source data.
     * @param {Array} $sources - sources data.
     * @param {Integer} $etymonId - the parent etymon id.
     */
    private function saveSource($sources, $etymonId): void {
        if (isset($sources)) {
            foreach ($sources as $source) {
                $newSource = new Source($source);

                $checkSource = ApiQuery::checkSourceWithoutId($newSource->get());
                if (!isset($checkSource)) {
                    if (!is_null($newSource->getSample())) {
                        $newSource->setEtymonId($etymonId);
                        ApiQuery::saveSource($newSource->get());
                    }
                }
            }
        }
    }

    /**
     * @description update the lexeme data.
     * @param {Request} $request - request data.
     */
    private function updateLexeme($request) {
        $this->updateEtymon($request[ETYMON]);
        $this->updateSemantics($request[SEMANTICS_LIST]);
    }

    /**
     * @description update etymon data.
     * @param {Array} $etymonData - the etymon data.
     */
    private function updateEtymon($etymonData): void {
        if (isset($etymonData)) {
            $etymon = new Etymon($etymonData);
            ApiQuery::updateEtymon($etymon->get());
            $sources = $etymon->getSources();
            $this->updateOrSaveSources($sources, $etymon->getEtymonId());
        }
    }

    /**
     * @description update sources or save if not exist on db
     * @param array $sources - the sources data
     * @param int $etymonId - the etymon id
     */
    private function updateOrSaveSources(array $sources, int $etymonId): void {
        foreach ($sources as $sourceData) {
            $source = new Source($sourceData);
            if (is_null($source->getSourceId())) {
                $source->setEtymonId($etymonId);
                ApiQuery::saveSource($source->get());
            } else {
                ApiQuery::updateSource($source->get());
            }
        }
    }

    /**
     * @description update semantics on db with given data
     * @param array $semanticsList - the semantics list data of lexeme
     */
    private function updateSemantics(array $semanticsList): void {
        if (isset($semanticsList)) {
            foreach ($semanticsList as $semanticsData) {
                $semantics = new Semantics($semanticsData);
                if ($semantics->getSemanticId()) {
                    ApiQuery::updateSemantics($semantics->get());
                }
                $this->updateOrSaveDialectLexeme($semantics->getConnects(), $semantics->getSemanticId());
            }
        }
    }

    /**
     * @description update dialect lexeme or save if not exist on db
     * @param array $connects - the connects of semantics data
     * @param int $semanticId - the semantics id of belong semantics data
     */
    private function updateOrSaveDialectLexeme(array $connects, int $semanticId): void {
        foreach ($connects as $connectData) {
            $dialectLexeme = new Lexeme($connectData);
            $dialectLexeme->setLatinText($this->convertToLatinText($dialectLexeme->getPronunciation()));
            $dialectLexemeId = $dialectLexeme->getLexemeId();
            if (is_null($dialectLexemeId)) {
                $queryResult = ApiQuery::saveLexeme($dialectLexeme->get());
                $dialectLexemeId = $queryResult[LEXEME_ID];
            } else {
                ApiQuery::updateLexeme($dialectLexeme->get());
            }
            $this->updateOrSaveDialectSemantics($dialectLexeme->getSemanticsList(), $dialectLexemeId, $semanticId);
        }
    }

    /**
     * @description update dialect semantics or save if not exist on db
     * @param array $dialectSemanticsList - the semantics list of dialect lexeme
     * @param int $dialectLexemeId - the dialect lexeme id
     * @param int $semanticId - the semantics id of belong semantics data
     */
    private function updateOrSaveDialectSemantics(array $dialectSemanticsList, int $dialectLexemeId, int $semanticId): void {
        foreach ($dialectSemanticsList as $semanticsData) {
            if (isset($semanticsData)) {
                $dialectSemantics = new Semantics($semanticsData);
                $dialectSemantics->setLexemeId($dialectLexemeId);
                if (is_null($dialectSemantics->getSemanticId())) {
                    $queryResult = ApiQuery::saveSemantics($dialectSemantics->get());
                    $this->saveBelong($semanticId, $queryResult[SEMANTIC_ID]);
                } else {
                    ApiQuery::updateSemantics($dialectSemantics->get());
                }
            }
        }
    }

    private function convertToLatinText($text) { return iconv('UTF-8', 'ASCII//TRANSLIT', $text); }

}
