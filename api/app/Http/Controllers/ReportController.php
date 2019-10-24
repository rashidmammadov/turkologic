<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 25.07.2019
 * Time: 16:57
 */

namespace App\Http\Controllers;

use App\Http\Models\Country;
use App\Http\Models\Language;
use App\Http\Models\Lexeme;
use App\Http\Models\Semantics;
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
            $response = null;
            if ($request[REPORT_TYPE] == CORRELATION_DISTRIBUTION) {
                $response = $this->correlationDistributionReport($request);
            } else if ($request[REPORT_TYPE] == COUNTRY_INFO) {
                $response = $this->countryInfo($request);
            } else if ($request[REPORT_TYPE] == FAKE_EQUIVALENT) {
                $response = $this->fakeEquivalentReport($request);
            } else if ($request[REPORT_TYPE] == SIMILARITY_RATIO) {
                $response = $this->similarityRationReport($request);
            }
            return $this->respondCreated('', $response);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function correlationDistributionReport(Request $request) {
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
        return $response;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function countryInfo(Request $request) {
        $response = new Country();
        $queryResult = ApiQuery::getLanguagesCountryCapital($request[LANGUAGE_ID]);
        if ($queryResult && $queryResult->first()) {
            $result = $queryResult->first();
            $response->setCountryName($result[COUNTRY_NAME]);
            $response->setFlag($result[FLAG]);
            $response->setDescription($result[DESCRIPTION]);
            $response->setCapital($result[CITY_NAME]);
//            $response->setCurrencyUnit($result[CURRENCY_UNIT]);
//            $response->setPopulation($result[POPULATION]);
            $response->setLanguage($result[NAME]);
            $languages = ApiQuery::getLanguagesCountryCapital();
            $languageRelation = array();
            $searchingLanguageId = $request[LANGUAGE_ID];
            foreach ($languages as $language) {
                if ($language[LANGUAGE_ID] == $searchingLanguageId) {
                    array_push($languageRelation, $language[NAME]);
                }
            }
//            $response->setLanguageRelation($languageRelation);
        }
        return $response->get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function fakeEquivalentReport(Request $request) {
        $response = array();
        $queryResult = ApiQuery::getUnrelatedSemantics($request[LEXEME]);
        foreach ($queryResult as $items) {
            $exceptSemanticId = null;
            foreach ($items as $item) {
                $lexeme = new Lexeme($item);
                $semantics = new Semantics($item);
                if ($lexeme->getLanguageId() === TUR_ID) {
                    $exceptSemanticId = $semantics->getSemanticId();
                    break;
                }
            }
            if (is_null($exceptSemanticId)) {
                foreach ($items as $item) {
                    $lexeme = new Lexeme($item);
                    $semanticsList = array();
                    foreach ($items as $i) {
                        $semantics = new Semantics($i);
                        if ($lexeme->getLexemeId() === $semantics->getLexemeId()) {
                            array_push($semanticsList, $semantics->get());
                        }
                    }
                    $lexeme->setSemanticsList($semanticsList);
                    array_push($response, $lexeme->get());
                }
            }
        }
        return $response;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function similarityRationReport(Request $request) {
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
                if (strtolower($fromLexeme->getLexeme()) == strtolower($toLexeme->getLexeme())) {
                    if ($fromLanguage->getLanguageId() === $toLanguage->getLanguageId()) {
                        $ratio = $ratio - 0;
                    } else if ($fromLanguage->getParentLanguageId() === $toLanguage->getParentLanguageId()) {
                        $ratio = $ratio - 0.01;
                    } else {
                        $ratio = $ratio - 0.02;
                    }
                } else if (strtolower($fromLexeme->getLatinText()) == strtolower($toLexeme->getLatinText())) {
                    if ($fromLanguage->getLanguageId() === $toLanguage->getLanguageId()) {
                        $ratio = $ratio - 0.03;
                    } else if ($fromLanguage->getParentLanguageId() === $toLanguage->getParentLanguageId()) {
                        $ratio = $ratio - 0.04;
                    } else {
                        $ratio = $ratio - 0.05;
                    }
                } else {
                    $difference = abs(strcmp($fromLexeme->getLatinText(), $toLexeme->getLatinText()));
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
        return $response;
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
