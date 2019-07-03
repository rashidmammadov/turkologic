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

    public function getLexemesByLanguage(Request $request) {
        $rules = array(
            LEXEME => 'required',
            LANGUAGE_ID => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            return $this->respondCreated('', $this->lexemesByLanguage($request[LEXEME], $request[LANGUAGE_ID]));
        }
    }

    private function lexemesByLanguage($lexeme, $languageId) {
        $lexemes = array();
        $lexemeGroup = ApiQuery::getLexemeSemantics($lexeme, $languageId);
        foreach ($lexemeGroup as $semanticsList) {
            if ($semanticsList && count($semanticsList) > 0) {
                $newLexeme = new Lexeme($semanticsList[0]);
                $preparedSemanticsList = array();
                foreach ($semanticsList as $semantics) {
                    $newSemantics = new Semantics($semantics);
                    array_push($preparedSemanticsList, $newSemantics->get());
                }
                $newLexeme->setSemanticsList($preparedSemanticsList);
                array_push($lexemes, $newLexeme->get());
            }
        }
        return $lexemes;
    }
}
