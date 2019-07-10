<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 16.06.2019
 * Time: 22:49
 */

namespace App\Http\Queries\MySQL;

use App\Belong;
use App\Semantics;
use App\Language;
use App\Lexeme;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class ApiQuery {

    /** -------------------- BELONG QUERIES -------------------- **/

    /**
     * @description query to set belong data
     * @param {Array} $belong - belong data
     * @return mixed
     */
    public static function saveBelong($belong) {
        try {

            $queryResult = Belong::create($belong);
            return $queryResult;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

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
     * @description query to save given lexeme and return created data.
     * @param array $lexeme - the lexeme data
     * @return mixed
     */
    public static function saveLexeme($lexeme) {
        $queryResult = Lexeme::create($lexeme);
        return $queryResult;
    }

    /**
     * @description query to get semantics of searching lexeme
     * @param string $lexeme - the searching lexeme
     * @param integer $languageId - the language id
     * @return mixed
     */
    public static function getLexemeSemantics($lexeme, $languageId) {
        $queryResult = Lexeme::where(LANGUAGE_ID, EQUAL_SIGN, $languageId)
        ->where(function ($query) use ($lexeme, $languageId) {
            $query->where(LEXEME, REGEXP_SIGN, $lexeme)
                ->orWhere(PRONUNCIATION, REGEXP_SIGN, $lexeme);
        })
        ->join(DB_SEMANTICS_TABLE, (DB_SEMANTICS_TABLE . '.' . LEXEME_ID), EQUAL_SIGN, (DB_LEXEME_TABLE . '.' . LEXEME_ID))
        ->get()
        ->groupBy(LEXEME_ID);

        return $queryResult;
    }

    /** -------------------- SEMANTICS QUERIES -------------------- **/

    /**
     * @description query to save given semantics and return created data.
     * @param array $semantics - the semantics data
     * @return mixed
     */
    public static function saveSemantics($semantics) {
        $queryResult = Semantics::create($semantics);
        return $queryResult;
    }

}
