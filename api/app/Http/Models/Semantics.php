<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 24.06.2019
 * Time: 13:37
 */

namespace App\Http\Models;


class Semantics {

    private $semanticId;
    private $lexemeId;
    private $type;
    private $meaning;
    private $sample;
    private $reference;
    private $belongTo;
    private $metaphor;

    public function __construct($parameters = null) {
        !empty($parameters[SEMANTIC_ID])    && self::setSemanticId($parameters[SEMANTIC_ID]);
        !empty($parameters[LEXEME_ID])      && self::setLexemeId($parameters[LEXEME_ID]);
        !empty($parameters[TYPE])           && self::setType($parameters[TYPE]);
        !empty($parameters[MEANING])        && self::setMeaning($parameters[MEANING]);
        !empty($parameters[SAMPLE])         && self::setSample($parameters[SAMPLE]);
        !empty($parameters[REFERENCE])      && self::setReference($parameters[REFERENCE]);
        !empty($parameters[BELONG_TO])      && self::setBelongTo($parameters[BELONG_TO]);
        !empty($parameters[METAPHOR])       && self::setMetaphor($parameters[METAPHOR]);
    }

    public function get() {
        return array(
            SEMANTIC_ID => $this->getSemanticId(),
            LEXEME_ID => $this->getLexemeId(),
            TYPE => $this->getType(),
            MEANING => $this->getMeaning(),
            SAMPLE => $this->getSample(),
            REFERENCE => $this->getReference(),
            BELONG_TO => $this->getBelongTo(),
            METAPHOR => $this->getMetaphor()
        );
    }

    public function getSemanticId() { return $this->semanticId; }

    public function setSemanticId($semanticId): void { $this->semanticId = $semanticId; }

    public function getLexemeId() { return $this->lexemeId; }

    public function setLexemeId($lexemeId): void { $this->lexemeId = $lexemeId; }

    public function getType() { return $this->type; }

    public function setType($type): void { $this->type = $type; }

    public function getMeaning() { return $this->meaning; }

    public function setMeaning($meaning): void { $this->meaning = $meaning; }

    public function getSample() { return $this->sample; }

    public function setSample($sample): void { $this->sample = $sample; }

    public function getReference() { return $this->reference; }

    public function setReference($reference): void { $this->reference = $reference; }

    public function getBelongTo() { return $this->belongTo; }

    public function setBelongTo($belongTo): void { $this->belongTo = $belongTo; }

    public function getMetaphor() { return $this->metaphor; }

    public function setMetaphor($metaphor): void { $this->metaphor = $metaphor; }



}
