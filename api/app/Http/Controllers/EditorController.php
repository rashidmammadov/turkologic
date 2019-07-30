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
            $this->saveSource($request, $etymonId);
        }
        return $etymonId;
    }

    /**
     * @description save the source data.
     * @param {Request} $request - request data.
     * @param {Integer} $etymonId - the parent etymon id.
     */
    private function saveSource($request, $etymonId): void {
        if (isset($request[ETYMON][SOURCES])) {
            foreach ($request[ETYMON][SOURCES] as $source) {
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

    private function updateLexeme($request) {
        $etymon = new Etymon($request[ETYMON]);
        ApiQuery::updateEtymon($etymon->get());
        $sources = $etymon->getSources();
        foreach ($sources as $source) {
            $sourceData = new Source($source);
            if (is_null($sourceData->getSourceId())) {
                $sourceData->setEtymonId($etymon->getEtymonId());
                ApiQuery::saveSource($sourceData->get());
            } else {
                ApiQuery::updateSource($sourceData->get());
            }
        }
    }

}
