<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 15.07.2019
 * Time: 01:40
 */

namespace App\Http\Controllers;

use App\Http\Models\Lexeme;
use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class SearchController extends ApiController {

    public function __construct() { }

    public function get(Request $request) {
        $rules = array(
            LEXEME => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            return $this->getLexemes($request);
        }
    }

    /**
     * @description prepare regular expression for db query.
     * @param Request $request - the lexeme of request params.
     * @return string
     */
    private function prepareKeyRegex(Request $request): string {
        $limit = floor((strlen($request[LEXEME]) / 4) * 3);
        $limit  = $limit >= 1 ? $limit : 1;
        $key = '[' . $request[LEXEME] . ']{' . $limit . ',}';
        return $key;
    }

    /**
     * @description get lexeme from db which are match with entered request param.
     * @param Request $request - the lexeme of request params.
     * @return mixed
     */
    private function getLexemes(Request $request) {
        $lexemes = array();
        $key = $this->prepareKeyRegex($request);
        $queryResult = ApiQuery::getLexemes($key);
        foreach ($queryResult as $lexeme) {
            $newLexeme = new Lexeme($lexeme);
            array_push($lexemes, $newLexeme->get());
        }
        return $this->respondCreated('', $lexemes);
    }
}
