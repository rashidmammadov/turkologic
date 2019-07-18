<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 16.06.2019
 * Time: 22:49
 */

namespace App\Http\Queries\MySQL;

use App\Belong;
use App\Etymon;
use App\Semantics;
use App\Language;
use App\Lexeme;
use App\Source;
use Illuminate\Support\Facades\Log;

class ApiQuery {

    /** -------------------- ETYMON QUERIES -------------------- **/

    /**
     * @description query to set etymon data
     * @param {Array} $etymon - etymon data
     * @return mixed
     */
    public static function saveEtymon($etymon) {
        $queryResult = Etymon::create($etymon);
        return $queryResult;
    }

    /** -------------------- BELONG QUERIES -------------------- **/

    /**
     * @description query to set belong data
     * @param {Array} $belong - belong data
     * @return mixed
     */
    public static function saveBelong($belong) {
        $queryResult = Belong::create($belong);
        return $queryResult;
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
     * @description query to get lexeme which is matched given lexeme parameter.
     * @param string $key - the lexeme data
     * @return mixed
     */
    public static function getLexemes($key) {
        // TODO: optimize query..
        $queryResult = Lexeme::where(LEXEME, REGEXP_SIGN, $key)
            ->orWhere(PRONUNCIATION, REGEXP_SIGN, $key)->get();
        return $queryResult;
    }

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
     * @description query to get semantics of given lexeme id
     * @param integer $lexemeId - the given lexeme id.
     * @return mixed
     */
    public static function getLexemeSemanticsByLexemeId($lexemeId) {
        $queryResult = Lexeme::where((DB_LEXEME_TABLE . '.' . LEXEME_ID), EQUAL_SIGN, $lexemeId)
            ->join(DB_SEMANTICS_TABLE, (DB_SEMANTICS_TABLE . '.' . LEXEME_ID), EQUAL_SIGN, (DB_LEXEME_TABLE . '.' . LEXEME_ID))
            ->get();

        return $queryResult;
    }

    /**
     * @description query to get semantics of searching lexeme
     * @param string $lexeme - the searching lexeme
     * @param integer $languageId - the language id
     * @return mixed
     */
    public static function getLexemeSemanticsByLanguageId($lexeme, $languageId) {
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

    /** -------------------- SOURCE QUERIES -------------------- **/

    /**
     * @description query to save given source and return created data.
     * @param array $source - the source data
     * @return mixed
     */
    public static function saveSource($source) {
        $queryResult = Source::create($source);
        return $queryResult;
    }

}
