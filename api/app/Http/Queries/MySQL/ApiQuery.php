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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiQuery {

    /** -------------------- ETYMON QUERIES -------------------- **/

    /**
     * @description query to check etymon with etymon id
     * @param {Integer} $etymon - etymon id
     * @return mixed
     */
    public static function getEtymon($etymonId) {
        $queryResult = Etymon::where((DB_ETYMON_TABLE . '.' . ETYMON_ID), EQUAL_SIGN, $etymonId)
            ->leftJoin(DB_SOURCE_TABLE, function ($join) {
                $join->on((DB_SOURCE_TABLE . '.' . ETYMON_ID), EQUAL_SIGN, (DB_ETYMON_TABLE . '.' . ETYMON_ID));
            })
            ->get();
        return $queryResult;
    }

    /**
     * @description query to check etymon is exist with entered parameters
     * @param {Array} $etymon - etymon data
     * @return mixed
     */
    public static function checkEtymonWithoutId($etymon) {
        $queryResult = Etymon::where(function ($query) use ($etymon) {
            $params = array(LANGUAGE_ID, WORD, PRONUNCIATION, TYPE);
            $result = self::appendMultiParams($etymon, $params);
            $query->where($result);
        })
        ->first();
        isset($queryResult) && Log::info('Given etymon already exist: ' . json_encode($queryResult));
        return $queryResult;
    }

    /**
     * @description query to set etymon data
     * @param {Array} $etymon - etymon data
     * @return mixed
     */
    public static function saveEtymon($etymon) {
        $queryResult = Etymon::create($etymon);
        Log::info('Etymon saved successfully: ' . json_encode($queryResult));
        return $queryResult;
    }

    /**
     * @description query to update etymon data
     * @param {Array} $etymon - etymon data
     * @return mixed
     */
    public static function updateEtymon($etymon) {
        Log::info('Etymon updated successfully: ' . json_encode($etymon));
        Etymon::where(ETYMON_ID, EQUAL_SIGN, $etymon[ETYMON_ID])
            ->update([LANGUAGE_ID => $etymon[LANGUAGE_ID], WORD => $etymon[WORD], PRONUNCIATION => $etymon[PRONUNCIATION],
                TYPE => $etymon[TYPE], DESCRIPTION => $etymon[DESCRIPTION]]);
    }

    /** -------------------- BELONG QUERIES -------------------- **/

    /**
     * @description query to save given belong and return created data.
     * @param array $belong - the $belong data
     * @return mixed
     */
    public static function checkBelongWithoutId($belong) {
        $queryResult = Belong::where(function ($query) use ($belong) {
            $params = array(FROM, TO);
            $result = self::appendMultiParams($belong, $params);
            $query->where($result);
        })->first();
        isset($queryResult) && Log::info('Given belong already exist: ' . json_encode($queryResult));
        return $queryResult;
    }

    /**
     * @description query to get belong data as connects
     * @param {Integer} $semanticId - semantic id
     * @return mixed
     */
    public static function getBelong($semanticId) {
        $alternativeId = null;
        $alternative = Belong::where(FROM, EQUAL_SIGN, $semanticId)->first();
        /** if semantics is not turkish language */
        if (isset($alternative)) {
            $alternativeId = $alternative[FROM];
            $semanticId = $alternative[TO];
        }
        $queryResult = Belong::where(TO, EQUAL_SIGN, $semanticId)
            ->leftJoin(DB_SEMANTICS_TABLE, function ($join) use ($semanticId) {
                $join->on((DB_SEMANTICS_TABLE . '.' . SEMANTIC_ID), EQUAL_SIGN, (DB_BELONG_TABLE . '.' . FROM));
                $join->orOn((DB_SEMANTICS_TABLE . '.' . SEMANTIC_ID), EQUAL_SIGN, (DB_BELONG_TABLE . '.' . TO));
            })
            ->where(function ($query) use ($alternativeId, $semanticId) {
                $query->where((DB_SEMANTICS_TABLE . '.' . SEMANTIC_ID), NOT_EQUAL_SIGN, $alternativeId);
                /** if semantics is not turkish language */
                if (is_null($alternativeId)) {
                    $query->where((DB_SEMANTICS_TABLE . '.' . SEMANTIC_ID), NOT_EQUAL_SIGN, $semanticId);
                }
            })
            ->join(DB_LEXEME_TABLE, (DB_LEXEME_TABLE . '.' . LEXEME_ID), EQUAL_SIGN, (DB_SEMANTICS_TABLE . '.' . LEXEME_ID))
            ->get()
            ->unique(SEMANTIC_ID)
            ->groupBy(LEXEME_ID);
        return $queryResult;
    }

    /**
     * @description query to set belong data
     * @param {Array} $belong - belong data
     * @return mixed
     */
    public static function saveBelong($belong) {
        $queryResult = Belong::create($belong);
        Log::info('Belong saved successfully: ' . json_encode($queryResult));
        return $queryResult;
    }

    /**
     * @description query to delete belong data
     * @param $from - the semantic if of from
     * @param $to - the semantic of of to
     */
    public static function deleteBelong($from, $to) {
        Belong::where(function ($query) use ($from, $to) {
            $query->where([[FROM, EQUAL_SIGN, $from], [TO, EQUAL_SIGN, $to]]);
        })->delete();
        Log::info('Belong deleted successfully: from - ' . $from . ' to - ' . $to);
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

    /**
     * @description query to get capital cities of all languages country.
     * @param integer $languageId - the id of given language.
     * @return mixed
     */
    public static function getLanguagesCountryCapital($languageId = null) {
        $queryResult = Language::where(function ($query) use ($languageId) {
                if ($languageId) {
                    $query->where(LANGUAGE_ID, EQUAL_SIGN, $languageId);
                } else {
                    $query->where(COUNTRY, NOT_EQUAL_SIGN, null);
                }
            })
            ->leftJoin(DB_COUNTRY_TABLE, function ($join) {
                $join->on((DB_COUNTRY_TABLE . '.' . COUNTRY_ID), EQUAL_SIGN, (DB_LANGUAGE_TABLE . '.' . COUNTRY));
            })
            ->where(DB_COUNTRY_TABLE, NOT_EQUAL_SIGN, null)
            ->leftJoin(DB_CITY_TABLE, function ($join) {
                $join->on((DB_COUNTRY_TABLE . '.' . CAPITAL_ID), EQUAL_SIGN, (DB_CITY_TABLE . '.' . CITY_ID));
            })
            ->get();
        return $queryResult;
    }

    /** -------------------- LEXEME QUERIES -------------------- **/

    /**
     * @description query to check given word in exist local db.
     * @param string $word - the searched word
     * @return mixed
     */
    public static function checkLexemeIfExist($word) {
        $queryResult = Lexeme::where(function ($query) use ($word) {
            $query->where([[LANGUAGE_ID, EQUAL_SIGN, TUR_ID], [LEXEME, EQUAL_SIGN, $word]]);
        })
        ->leftJoin(DB_SEMANTICS_TABLE, function ($join) {
            $join->on((DB_SEMANTICS_TABLE . '.' . LEXEME_ID), EQUAL_SIGN, (DB_LEXEME_TABLE . '.' . LEXEME_ID));
        })
        ->get();
        return $queryResult;
    }

    /**
     * @description query to check given lexeme.
     * @param array $lexeme - the lexeme data
     * @return mixed
     */
    public static function checkLexemeWithoutId($lexeme) {
        $queryResult = Lexeme::where(function ($query) use ($lexeme) {
            $params = array(ETYMON_ID, LANGUAGE_ID, LEXEME, PRONUNCIATION);
            $result = self::appendMultiParams($lexeme, $params);
            $query->where($result);
        })
        ->first();
        isset($queryResult) && Log::info('Given lexeme already exist: ' . json_encode($queryResult));
        return $queryResult;
    }

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
        Log::info('Lexeme saved successfully: ' . json_encode($queryResult));
        return $queryResult;
    }

    public static function updateLexeme($lexeme) {
        Log::info('Lexeme updated successfully: ' . json_encode($lexeme));
        Lexeme::where(LEXEME_ID, EQUAL_SIGN, $lexeme[LEXEME_ID])
            ->update([LEXEME => $lexeme[LEXEME], PRONUNCIATION => $lexeme[PRONUNCIATION], LATIN_TEXT => $lexeme[LATIN_TEXT]]);
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
    public static function checkSemanticsWithoutId($semantics) {
        $queryResult = Semantics::where(function ($query) use ($semantics) {
            $params = array(LEXEME_ID, TYPE, MEANING);
            $result = self::appendMultiParams($semantics, $params);
            $query->where($result);
        })
        ->first();
        isset($queryResult) && Log::info('Given semantics already exist: ' . json_encode($queryResult));
        return $queryResult;
    }

    /**
     * @description query to get semantics by given semantic id.
     * @param integer $semanticId - the semantic id
     * @return mixed
     */
    public static function getSemanticsById($semanticId) {
        $queryResult = Semantics::where(SEMANTIC_ID, EQUAL_SIGN, $semanticId)
            ->leftJoin(DB_LEXEME_TABLE, function ($join) {
                $join->on(DB_SEMANTICS_TABLE . '.' . LEXEME_ID, EQUAL_SIGN, DB_LEXEME_TABLE . '.' . LEXEME_ID);
            })->first();
        return $queryResult;
    }

    public static function getUnrelatedSemantics($lexeme) {
        $queryResult = Lexeme::where(function ($query) use ($lexeme) {
                $query->where(DB_LEXEME_TABLE . '.' . LATIN_TEXT, EQUAL_SIGN, $lexeme);
            })
            ->leftJoin(DB_SEMANTICS_TABLE, function ($join) {
                $join->on(DB_SEMANTICS_TABLE . '.' . LEXEME_ID, EQUAL_SIGN, DB_LEXEME_TABLE . '.' . LEXEME_ID);
            })
            ->leftJoin(DB_BELONG_TABLE, function ($join) {
                $join->on(DB_BELONG_TABLE . '.' . FROM, EQUAL_SIGN, DB_SEMANTICS_TABLE . '.' . SEMANTIC_ID)
                    ->orOn(DB_BELONG_TABLE . '.' . TO, EQUAL_SIGN, DB_SEMANTICS_TABLE . '.' . SEMANTIC_ID);
            })
            ->where(DB_BELONG_TABLE . '.' . FROM, NOT_EQUAL_SIGN, null)
            ->where(DB_BELONG_TABLE . '.' . TO, NOT_EQUAL_SIGN, null)
            ->get()
            ->groupBy(TO)
        ;
        return $queryResult;
    }

    /**
     * @description query to save given semantics and return created data.
     * @param array $semantics - the semantics data
     * @return mixed
     */
    public static function saveSemantics($semantics) {
        $queryResult = Semantics::create($semantics);
        Log::info('Semantics saved successfully: ' . json_encode($queryResult));
        return $queryResult;
    }

    /**
     * @description query to update given semantics data.
     * @param array $semantics - the semantics data
     */
    public static function updateSemantics($semantics) {
        Log::info('Semantics updated successfully: ' . json_encode($semantics));
        Semantics::where(SEMANTIC_ID, EQUAL_SIGN, $semantics[SEMANTIC_ID])
            ->update([TYPE => $semantics[TYPE], MEANING => $semantics[MEANING], SAMPLE => $semantics[SAMPLE],
                REFERENCE => $semantics[REFERENCE]]);
    }

    /**
     * @description query to delete given semantics id.
     * @param $semanticsId - the semantics id
     */
    public static function deleteSemantics($semanticsId) {
        Semantics::where(SEMANTIC_ID, EQUAL_SIGN, $semanticsId)->delete();
        Log::info('Semantics deleted successfully: ' . json_encode($semanticsId));
    }

    /** -------------------- SOURCE QUERIES -------------------- **/

    /**
     * @description query to save given source and return created data.
     * @param array $source - the source data
     * @return mixed
     */
    public static function checkSourceWithoutId($source) {
        $queryResult = Source::where(function ($query) use ($source) {
            $params = array(ETYMON_ID, SAMPLE, REFERENCE);
            $result = self::appendMultiParams($source, $params);
            $query->where($result);
        })
        ->first();
        isset($queryResult) && Log::info('Given source already exist: ' . json_encode($queryResult));
        return $queryResult;
    }

    /**
     * @description query to save given source and return created data.
     * @param array $source - the source data
     * @return mixed
     */
    public static function saveSource($source) {
        $queryResult = Source::create($source);
        Log::info('Source saved successfully: ' . json_encode($queryResult));
        return $queryResult;
    }

    /**
     * @description query to update given source.
     * @param array $source - the source data
     * @return mixed
     */
    public static function updateSource($source) {
        Log::info('Source updated successfully: ' . json_encode($source));
        Source::where(SOURCE_ID, EQUAL_SIGN, $source[SOURCE_ID])
            ->update([SAMPLE => $source[SAMPLE], REFERENCE => $source[REFERENCE]]);
    }

    /**
     * @description query to delete given source source id.
     * @param int $sourceId - the source id
     * @return mixed
     */
    public static function deleteSource($sourceId) {
        Source::where(SOURCE_ID, EQUAL_SIGN, $sourceId)->delete();
        Log::info('Source deleted successfully: ' . json_encode($sourceId));
    }

    /** -------------------- PRIVATE METHODS -------------------- **/

    /**
     * @description query to save given source and return created data.
     * @param array $data - the given data which checked with parameters
     * @param array $keys - the params which are checked
     * @return mixed
     */
    private static function appendMultiParams($data, $keys) {
        $result = array();
        foreach ($keys as $key) {
            isset($data[$key]) && array_push($result, array($key, EQUAL_SIGN, $data[$key]));
        }
        return $result;
    }

}
