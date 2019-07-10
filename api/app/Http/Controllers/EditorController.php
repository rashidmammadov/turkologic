<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 08.07.2019
 * Time: 17:07
 */

namespace App\Http\Controllers;

use App\Http\Models\Belong;
use App\Http\Models\Lexeme;
use App\Http\Models\Semantics;
use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class EditorController extends ApiController {

    public function __construct() { }

    public function post(Request $request) {
        try {
            $this->saveLexeme($request);
            return $this->respondCreated('Data saved successfully');
        } catch (Exception $e) {
            return $this->respondWithError('Something went wrong while saving data');
        }
    }

    /**
     * @description save the dialect lexeme of saved semantics.
     * @param {Array} $semantics - semantics data
     * @param {Integer} $semanticId - parent semantics id
     * @return void
     */
    private function saveDialectLexemes($semantics, $semanticId): void {
        if (isset($semantics[CONNECTS])) {
            foreach ($semantics[CONNECTS] as $connect) {
                $dialectLexemeId = null;
                if (is_null($connect[LEXEME_ID])) {
                    $dialectLexeme = new Lexeme($connect);
                    $queryResult = ApiQuery::saveLexeme($dialectLexeme->get());
                    $dialectLexemeId = $queryResult[LEXEME_ID];
                    Log::info('Dialect`s lexeme saved successfully: ' . json_encode($queryResult));
                } else {
                    $dialectLexemeId = $connect[LEXEME_ID];
                }
                $this->saveDialectSemanticsList($connect, $dialectLexemeId, $semanticId);
            }
        }
    }

    /**
     * @description save the semantics list of saved dialect lexeme.
     * @param {Array} $connect - dialect array of semantics.
     * @param {Integer} $dialectLexemeId - parent lexeme id.
     * @param {Integer} $semanticId - parent semantics id.
     * @return void
     */
    private function saveDialectSemanticsList($connect, $dialectLexemeId, $semanticId): void {
        if (isset($connect[SEMANTICS_LIST])) {
            foreach ($connect[SEMANTICS_LIST] as $connectSemantics) {
                $dialectSemantics = new Semantics($connectSemantics);
                $dialectSemantics->setLexemeId($dialectLexemeId);
                $queryResult = ApiQuery::saveSemantics($dialectSemantics->get());
                $dialectSemanticId = $queryResult[SEMANTIC_ID];
                Log::info('Dialect`s semantics saved successfully: ' . json_encode($queryResult));
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
        $newLexeme = new Lexeme($request);
        $queryResult = ApiQuery::saveLexeme($newLexeme->get());
        $lexemeId = $queryResult[LEXEME_ID];
        Log::info('Lexeme saved successfully: ' . json_encode($queryResult));
        $this->saveSemanticsList($request, $lexemeId);
    }

    /**
     * @description save the related lexeme`s semantics list and dialects.
     * @param {Request} $request - parameters of request that holds the lexeme`s semantics list.
     * @param {Integer} $lexemeId - saved lexeme id
     */
    private function saveSemanticsList($request, $lexemeId): void {
        if (isset($request[SEMANTICS_LIST])) {
            foreach ($request[SEMANTICS_LIST] as $semantics) {
                $newSemantics = new Semantics($semantics);
                $newSemantics->setLexemeId($lexemeId);
                $queryResult = ApiQuery::saveSemantics($newSemantics->get());
                $semanticId = $queryResult[SEMANTIC_ID];
                Log::info('Semantics saved successfully: ' . json_encode($queryResult));
                $this->saveDialectLexemes($semantics, $semanticId);
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
        $queryResult = ApiQuery::saveBelong($belong->get());
        Log::info('Belong saved successfully: ' . json_encode($queryResult));
    }

}
