<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 16.06.2019
 * Time: 22:33
 */

namespace App\Http\Controllers;

use App\Http\Models\Language;
use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;

class LanguageController extends ApiController {

    public function __construct() { }

    public function get(Request $request) {
        $languages = ApiQuery::getLanguages($request[STATUS]);
        $dataArray = array();
        foreach ($languages as $language) {
            $data = new Language($language);
            array_push($dataArray, $data->get());
        }
        return $this->respondCreated('', $dataArray);
    }
}
