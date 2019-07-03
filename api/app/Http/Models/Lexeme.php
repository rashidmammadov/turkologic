<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 24.06.2019
 * Time: 14:55
 */

namespace App\Http\Models;


class Lexeme {

    private $lexemeId;
    private $lexeme;
    private $pronunciation;
    private $latinText;
    private $alphabet;
    private $languageId;
    private $etymonId;
    private $semanticsList = array();

    public function __construct($parameters = null) {
        !empty($parameters[LEXEME_ID])      && self::setLexemeId($parameters[LEXEME_ID]);
        !empty($parameters[LEXEME])         && self::setLexeme($parameters[LEXEME]);
        !empty($parameters[PRONUNCIATION])  && self::setPronunciation($parameters[PRONUNCIATION]);
        !empty($parameters[LATIN_TEXT])     && self::setLatinText($parameters[LATIN_TEXT]);
        !empty($parameters[ALPHABET])       && self::setAlphabet($parameters[ALPHABET]);
        !empty($parameters[LANGUAGE_ID])    && self::setLanguageId($parameters[LANGUAGE_ID]);
        !empty($parameters[ETYMON_ID])      && self::setEtymonId($parameters[ETYMON_ID]);
        !empty($parameters[SEMANTICS_LIST]) && self::setSemanticsList($parameters[SEMANTICS_LIST]);
    }

    public function get() {
        return array(
            LEXEME_ID => $this->getLexemeId(),
            LEXEME => $this->getLexeme(),
            PRONUNCIATION => $this->getPronunciation(),
            LATIN_TEXT => $this->getLatinText(),
            ALPHABET => $this->getAlphabet(),
            LANGUAGE_ID => $this->getLanguageId(),
            ETYMON_ID => $this->getEtymonId(),
            SEMANTICS_LIST => $this->getSemanticsList()
        );
    }

    public function getLexemeId() { return $this->lexemeId; }

    public function setLexemeId($lexemeId): void { $this->lexemeId = $lexemeId; }

    public function getLexeme() { return $this->lexeme; }

    public function setLexeme($lexeme): void { $this->lexeme = $lexeme; }

    public function getPronunciation() { return $this->pronunciation; }

    public function setPronunciation($pronunciation): void { $this->pronunciation = $pronunciation; }

    public function getLatinText() { return $this->latinText; }

    public function setLatinText($latinText): void { $this->latinText = $latinText; }

    public function getAlphabet() { return $this->alphabet; }

    public function setAlphabet($alphabet): void { $this->alphabet = $alphabet; }

    public function getLanguageId() { return $this->languageId; }

    public function setLanguageId($languageId): void { $this->languageId = $languageId; }

    public function getEtymonId() { return $this->etymonId; }

    public function setEtymonId($etymonId): void { $this->etymonId = $etymonId; }

    public function getSemanticsList() { return $this->semanticsList; }

    public function setSemanticsList($semanticsList): void { $this->semanticsList = $semanticsList; }

}
