<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 22.07.2019
 * Time: 11:25
 */

namespace App\Http\Controllers;

use App\Http\Models\Lexeme;
use App\Http\Models\Semantics;
use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class SemanticsController extends ApiController {

    public function __construct() { }

    public function getSemanticsById(Request $request) {
        $rules = array(
            SEMANTIC_ID => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            return $this->getSemantics($request);
        }
    }

    /**
     * @description get semantics and connects by given semantic id
     * @param Request $request - the data of request
     * @return mixed
     */
    private function getSemantics(Request $request) {
        $queryResult = ApiQuery::getSemanticsById($request[SEMANTIC_ID]);
        if (isset($queryResult)) {
            $newLexeme = new Lexeme($queryResult);
            $newLexemeSemanticsList = array();
            $newSemantics = new Semantics($queryResult);
            $newConnects = $this->getConnects($request);
            $newSemantics->setConnects($newConnects);
            array_push($newLexemeSemanticsList, $newSemantics->get());
            $newLexeme->setSemanticsList($newLexemeSemanticsList);
            return $this->respondCreated('', $newLexeme->get());
        } else {
            return $this->respondWithError(NO_ANY_RESULT);
        }
    }

    /**
     * @description get connects by given semantic id
     * @param Request $request - the data of request
     * @return mixed
     */
    private function getConnects(Request $request) {
        $newConnects = array();
        $connects = ApiQuery::getBelong($request[SEMANTIC_ID]);
        foreach ($connects as $connect) {
            $semanticsList = array();
            $connectLexeme = new Lexeme($connect[0]);
            foreach ($connect as $semanticsData) {
                $newConnectSemantics = new Semantics($semanticsData);
                array_push($semanticsList, $newConnectSemantics->get());
            }
            $connectLexeme->setSemanticsList($semanticsList);
            array_push($newConnects, $connectLexeme->get());
        }
        return $newConnects;
    }
}
