<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 16.06.2019
 * Time: 22:49
 */

namespace App\Http\Queries\MySQL;

use App\Language;

class ApiQuery {

    /** -------------------- LANGUAGE QUERIES -------------------- **/

    /**
     * @description query to get dialect.ts data
     * @param integer $status - the status if only true of all
     * @return mixed
     */
    public static function getLanguages($status = null) {
        $queryResult = Language::where(function ($query) use ($status) {
            if ($status) {
                $query->where(STATUS, EQUAL_SIGN, $status);
            }
        })->get();

        return $queryResult;
    }

}
