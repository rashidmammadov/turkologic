<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 16.06.2019
 * Time: 22:37
 */

namespace App\Http\Models;

class Language {

    private $languageId;
    private $name;
    private $code;
    private $flag;
    private $country;
    private $century;
    private $parentLanguageId;
    private $status;

    public function __construct($parameters = null) {
        !empty($parameters[LANGUAGE_ID])    && self::setLanguageId($parameters[LANGUAGE_ID]);
        !empty($parameters[NAME])           && self::setName($parameters[NAME]);
        !empty($parameters[CODE])           && self::setCode($parameters[CODE]);
        !empty($parameters[FLAG])           && self::setFlag($parameters[FLAG]);
        !empty($parameters[COUNTRY])        && self::setCountry($parameters[COUNTRY]);
        !empty($parameters[CENTURY])        && self::setCentury($parameters[CENTURY]);
        !empty($parameters[PARENT_LANGUAGE_ID]) && self::setParentLanguageId($parameters[PARENT_LANGUAGE_ID]);
        !empty($parameters[STATUS])         && self::setStatus($parameters[STATUS]);
    }

    public function get() {
        return array(
            LANGUAGE_ID => $this->getLanguageId(),
            NAME => $this->getName(),
            CODE => $this->getCode(),
            FLAG => $this->getFlag(),
            COUNTRY => $this->getCountry(),
            CENTURY => $this->getCentury(),
            PARENT_LANGUAGE_ID => $this->getParentLanguageId(),
            STATUS => $this->getStatus()
        );
    }

    public function getLanguageId() { return $this->languageId; }

    public function setLanguageId($languageId): void { $this->languageId = $languageId; }

    public function getName() { return $this->name; }

    public function setName($name): void { $this->name = $name; }

    public function getCode() { return $this->code; }

    public function setCode($code): void { $this->code = $code; }

    public function getFlag() { return $this->flag; }

    public function setFlag($flag): void { $this->flag = $flag; }

    public function getCountry() { return $this->country; }

    public function setCountry($country): void { $this->country = $country; }

    public function getCentury() { return $this->century; }

    public function setCentury($century): void { $this->century = $century; }

    public function getParentLanguageId() { return $this->parentLanguageId; }

    public function setParentLanguageId($parentLanguageId): void { $this->parentLanguageId = $parentLanguageId; }

    public function getStatus() { return $this->status; }

    public function setStatus($status): void { $this->status = $status; }

}
