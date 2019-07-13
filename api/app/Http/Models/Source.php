<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 12.07.2019
 * Time: 17:02
 */

namespace App\Http\Models;


class Source {

    private $etymonId;
    private $sample;
    private $reference;

    public function __construct($parameters = null) {
        !empty($parameters[ETYMON_ID])  && self::setEtymonId($parameters[ETYMON_ID]);
        !empty($parameters[SAMPLE])     && self::setSample($parameters[SAMPLE]);
        !empty($parameters[REFERENCE])  && self::setReference($parameters[REFERENCE]);
    }

    public function get() {
        return array(
            ETYMON_ID => $this->getEtymonId(),
            SAMPLE => $this->getSample(),
            REFERENCE => $this->getReference()
        );
    }

    public function getEtymonId() { return $this->etymonId; }

    public function setEtymonId($etymonId): void { $this->etymonId = $etymonId; }

    public function getSample() { return $this->sample; }

    public function setSample($sample): void { $this->sample = $sample; }

    public function getReference() { return $this->reference; }

    public function setReference($reference): void { $this->reference = $reference; }

}
