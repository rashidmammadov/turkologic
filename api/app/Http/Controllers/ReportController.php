<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 25.07.2019
 * Time: 16:57
 */

namespace App\Http\Controllers;

use App\Http\Models\Language;
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
            } else if ($request[REPORT_TYPE] == SIMILARITY_RATIO) {
                $response = array();
                $languages = ApiQuery::getLanguages(1);
                $connects = ApiQuery::getBelong($request[SEMANTIC_ID]);
                foreach ($connects as $from) {
                    $responseData = array();
                    $fromLexeme = new Lexeme($from[0]);
                    $fromLexemeLanguageId = $fromLexeme->getLanguageId();
                    $fromLanguage = new Language($this->findLanguageById($languages, $fromLexemeLanguageId));

                    $responseData['fromLexeme'] = $fromLexeme->getLexeme();
                    $responseData['from'] = $fromLanguage->getLanguageId();
                    $responseData['fromPronunciation'] = $fromLexeme->getPronunciation();
                    $responseData['fromLanguage'] = $fromLanguage->getName();
                    foreach ($connects as $to) {
                        $toLexeme = new Lexeme($to[0]);
                        $toLexemeLanguageId = $toLexeme->getLanguageId();
                        $toLanguage = new Language($this->findLanguageById($languages, $toLexemeLanguageId));

                        $responseData['toLexeme'] = $toLexeme->getLexeme();
                        $responseData['to'] = $toLanguage->getLanguageId();
                        $responseData['toPronunciation'] = $toLexeme->getPronunciation();
                        $responseData['toLanguage'] = $toLanguage->getName();

                        $ratio = 1;
                        $a = json_encode('ǎ', JSON_UNESCAPED_UNICODE);
                        $b = json_encode('a', JSON_UNESCAPED_UNICODE);
                        $d = strcmp($a, $b);
//                        Log::info(transliterator_transliterate(
//                            'Any-Latin; Latin-ASCII; Lower()', "A æ Übérmensch på høyeste nivå! И я люблю PHP! ﬁ"));
                        if (strtolower($fromLexeme->getLexeme()) == strtolower($toLexeme->getLexeme())) {
                            if ($fromLanguage->getLanguageId() === $toLanguage->getLanguageId()) {
                                $ratio = $ratio - 0;
                            } else if ($fromLanguage->getParentLanguageId() === $toLanguage->getParentLanguageId()) {
                                $ratio = $ratio - 0.01;
                            } else {
                                $ratio = $ratio - 0.02;
                            }
                        } else if (strtolower($fromLexeme->getPronunciation()) == strtolower($toLexeme->getPronunciation())) {
                            if ($fromLanguage->getLanguageId() === $toLanguage->getLanguageId()) {
                                $ratio = $ratio - 0.03;
                            } else if ($fromLanguage->getParentLanguageId() === $toLanguage->getParentLanguageId()) {
                                $ratio = $ratio - 0.04;
                            } else {
                                $ratio = $ratio - 0.05;
                            }
                        } else {
                            $difference = abs(strcmp($fromLexeme->getPronunciation(), $toLexeme->getPronunciation()));
                            if ($difference > 100) {
                                $ratio = $ratio - $difference / 1000000;
                            } else {
                                $ratio = $ratio - $difference / 100;
                            }
                        }
                        $responseData['ratio'] = number_format((float)$ratio, 2, '.', '');
                        array_push($response, $responseData);
                    }
                }
                return $this->respondCreated('', $response);
            }
        }
    }

    /**
     * @description find and return language by given language id
     * @param $languages - the array of active languages
     * @param $fromLexemeLanguageId - the given language id
     * @return mixed
     */
    private function findLanguageById($languages, $fromLexemeLanguageId) {
        $result = null;
        foreach ($languages as $language) {
            if ($language[LANGUAGE_ID] == $fromLexemeLanguageId) {
                $result = $language;
                break;
            }
        }
        return $result;
    }

}
