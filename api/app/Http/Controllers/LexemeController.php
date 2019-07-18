<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 03.07.2019
 * Time: 17:22
 */

namespace App\Http\Controllers;

use App\Http\Models\Lexeme;
use App\Http\Models\Semantics;
use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class LexemeController extends ApiController {

    public function __construct() { }

    public function getLexemeById(Request $request) {
        $rules = array(
            LEXEME_ID => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            return $this->lexemeById($request);
        }
    }

    public function getLexemesByLanguage(Request $request) {
        $rules = array(
            LEXEME => 'required',
            LANGUAGE_ID => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            return $this->lexemesByLanguage($request[LEXEME], $request[LANGUAGE_ID]);
        }
    }

    /**
     * @description get lexemes and semantics list by given language id and lexeme.
     * @param String $lexeme - the searching lexeme text.
     * @param Integer $languageId - the language id which lexeme searching in.
     * @return mixed
     */
    private function lexemesByLanguage($lexeme, $languageId) {
        $lexemes = array();
        $lexemeGroup = ApiQuery::getLexemeSemanticsByLanguageId($lexeme, $languageId);
        foreach ($lexemeGroup as $semanticsArray) {
            if ($semanticsArray && count($semanticsArray) > 0) {
                $newLexeme = $this->setLexeme($semanticsArray);
                array_push($lexemes, $newLexeme->get());
            }
        }
        return $this->respondCreated('', $lexemes);
    }

    /**
     * @description get lexeme and semantics list by given lexeme id.
     * @param Request $request - the request data.
     * @return mixed
     */
    private function lexemeById(Request $request) {
        $semanticsArray = ApiQuery::getLexemeSemanticsByLexemeId($request[LEXEME_ID]);
        if ($semanticsArray && isset($semanticsArray[0])) {
            $newLexeme = $this->setLexeme($semanticsArray);
            return $this->respondCreated('', $newLexeme->get());
        } else {
            return $this->respondWithError(NO_ANY_RESULT);
        }
    }

    /**
     * @description get lexeme and semantics list by given lexeme id.
     * @param $semanticsArray - the result of db query.
     * @return Lexeme
     */
    private function setLexeme($semanticsArray): Lexeme {
        $newLexeme = new Lexeme($semanticsArray[0]);
        $semanticsList = $this->setSemanticsList($semanticsArray);
        $newLexeme->setSemanticsList($semanticsList);
        return $newLexeme;
    }

    /**
     * @description prepare semantics list of lexeme.
     * @param array $semanticsArray - the semantics list data of lexeme.
     * @return array
     */
    private function setSemanticsList($semanticsArray): array {
        $semanticsList = array();
        foreach ($semanticsArray as $semantics) {
            $newSemantics = new Semantics($semantics);
            array_push($semanticsList, $newSemantics->get());
        }
        return $semanticsList;
    }
}
