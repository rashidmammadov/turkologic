<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 25.07.2019
 * Time: 16:57
 */

namespace App\Http\Controllers;

use App\Http\Models\Lexeme;
use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class ReportController extends ApiController {

    public function __construct() { }

    public function get(Request $request) {
        $rules = array(
            REPORT_TYPE => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            // TODO: refactor code..
            if ($request[REPORT_TYPE] == CORRELATION_DISTRIBUTION) {
                $response = array();
                $cities = ApiQuery::getLanguagesCountryCapital();
                $connects = ApiQuery::getBelong($request[SEMANTIC_ID]);
                foreach ($cities as $city) {
                    $responseData = array(
                        COUNTRY => $city[COUNTRY_NAME],
                        NAME => $city[CITY_NAME],
                        LATITUDE => $city[LATITUDE],
                        LONGITUDE => $city[LONGITUDE],
                        VALUE => 0
                    );
                    foreach ($connects as $connect) {
                        $lexeme = new Lexeme($connect[0]);
                        $city[LANGUAGE_ID] == $lexeme->getLanguageId() && ($responseData[VALUE]++);
                    }
                    $responseData[VALUE] > 0 && array_push($response, $responseData);
                }
                return $this->respondCreated('', $response);
            }
        }
    }

}
