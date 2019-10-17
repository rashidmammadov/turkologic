<?php

namespace App\Http\Models;

class Country {

    private $countryName;
    private $flag;
    private $description;
    private $capital;
    private $currencyUnit;
    private $population;
    private $language;
    private $languageRelation = array();

    public function __construct($parameters = null) {
        !empty($parameters[COUNTRY_NAME])       && self::setCountryName($parameters[COUNTRY_NAME]);
        !empty($parameters[FLAG])               && self::setFlag($parameters[FLAG]);
        !empty($parameters[DESCRIPTION])        && self::setDescription($parameters[DESCRIPTION]);
        !empty($parameters[CAPITAL])            && self::setCapital($parameters[CAPITAL]);
        !empty($parameters[CURRENCY_UNIT])      && self::setCurrencyUnit($parameters[CURRENCY_UNIT]);
        !empty($parameters[POPULATION])         && self::setPopulation($parameters[POPULATION]);
        !empty($parameters[LANGUAGE])           && self::setLanguage($parameters[LANGUAGE]);
        !empty($parameters[LANGUAGE_RELATION])  && self::setLanguageRelation($parameters[LANGUAGE_RELATION]);
    }

    public function get() {
        return array(
            COUNTRY_NAME => $this->getCountryName(),
            FLAG => $this->getFlag(),
            DESCRIPTION => $this->getDescription(),
            CAPITAL => $this->getCapital(),
            CURRENCY_UNIT => $this->getCurrencyUnit(),
            POPULATION => $this->getPopulation(),
            LANGUAGE => $this->getLanguage(),
            LANGUAGE_RELATION => $this->getLanguageRelation()
        );
    }

    public function getCountryName() { return $this->countryName; }

    public function setCountryName($countryName): void { $this->countryName = $countryName; }

    public function getFlag() { return $this->flag; }

    public function setFlag($flag): void { $this->flag = $flag; }

    public function getDescription() { return $this->description; }

    public function setDescription($description): void { $this->description = $description; }

    public function getCapital() { return $this->capital; }

    public function setCapital($capital): void { $this->capital = $capital; }

    public function getCurrencyUnit() { return $this->currencyUnit; }

    public function setCurrencyUnit($currencyUnit): void { $this->currencyUnit = $currencyUnit; }

    public function getPopulation() { return $this->population; }

    public function setPopulation($population): void { $this->population = $population; }

    public function getLanguage() { return $this->language; }

    public function setLanguage($language): void { $this->language = $language; }

    public function getLanguageRelation() { return $this->languageRelation; }

    public function setLanguageRelation($languageRelation): void { $this->languageRelation = $languageRelation; }


}
