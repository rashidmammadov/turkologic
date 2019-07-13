<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 12.07.2019
 * Time: 16:53
 */

namespace App\Http\Models;


class Etymon {

    private $etymonId;
    private $languageId;
    private $word;
    private $pronunciation;
    private $type;
    private $description;
    private $sources = array();

    public function __construct($parameters = null) {
        !empty($parameters[ETYMON_ID])      && self::setEtymonId($parameters[ETYMON_ID]);
        !empty($parameters[LANGUAGE_ID])    && self::setLanguageId($parameters[LANGUAGE_ID]);
        !empty($parameters[WORD])           && self::setWord($parameters[WORD]);
        !empty($parameters[PRONUNCIATION])  && self::setPronunciation($parameters[PRONUNCIATION]);
        !empty($parameters[TYPE])           && self::setType($parameters[TYPE]);
        !empty($parameters[DESCRIPTION])    && self::setDescription($parameters[DESCRIPTION]);
        !empty($parameters[SOURCES])        && self::setSources($parameters[SOURCES]);
    }

    public function get() {
        return array(
            ETYMON_ID => $this->getEtymonId(),
            LANGUAGE_ID => $this->getLanguageId(),
            WORD => $this->getWord(),
            PRONUNCIATION => $this->getPronunciation(),
            TYPE => $this->getType(),
            DESCRIPTION => $this->getDescription(),
            SOURCES => $this->getSources(),
        );
    }

    public function getEtymonId() { return $this->etymonId; }

    public function setEtymonId($etymonId): void { $this->etymonId = $etymonId; }

    public function getLanguageId() { return $this->languageId; }

    public function setLanguageId($languageId): void { $this->languageId = $languageId; }

    public function getWord() { return $this->word; }

    public function setWord($word): void { $this->word = $word; }

    public function getPronunciation() { return $this->pronunciation; }

    public function setPronunciation($pronunciation): void { $this->pronunciation = $pronunciation; }

    public function getType() { return $this->type; }

    public function setType($type): void { $this->type = $type; }

    public function getDescription() { return $this->description; }

    public function setDescription($description): void { $this->description = $description; }

    public function getSources() { return $this->sources; }

    public function setSources($sources): void { $this->sources = $sources; }

}
