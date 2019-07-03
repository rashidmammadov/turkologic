<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 16.06.2019
 * Time: 22:49
 */

namespace App\Http\Queries\MySQL;

use App\Language;
use App\Lexeme;

class ApiQuery {

    /** -------------------- LANGUAGE QUERIES -------------------- **/

    /**
     * @description query to get language data
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

    /** -------------------- LEXEME QUERIES -------------------- **/

    /**
     * @description query to get semantics of searching lexeme
     * @param string $lexeme - the searching lexeme
     * @param integer $languageId - the language id
     * @return mixed
     */
    public static function getLexemeSemantics($lexeme, $languageId) {
        $queryResult = Lexeme::distinct()->where(function ($query) use ($lexeme, $languageId) {
            $query->where(LEXEME, REGEXP_SIGN, $lexeme)
                ->orWhere(PRONUNCIATION, REGEXP_SIGN, $lexeme)
                ->where(LANGUAGE_ID, EQUAL_SIGN, $languageId);
        })
        ->join(DB_SEMANTICS_TABLE, (DB_SEMANTICS_TABLE . '.' . LEXEME_ID), EQUAL_SIGN, (DB_LEXEME_TABLE . '.' . LEXEME_ID))
        ->get()
        ->groupBy(LEXEME_ID);

        return $queryResult;
    }

}
