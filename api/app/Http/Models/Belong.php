<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 10.07.2019
 * Time: 12:57
 */

namespace App\Http\Models;


class Belong {

    private $from;
    private $to;

    public function __construct($parameters = null) {
        !empty($parameters[FROM])    && self::setFrom($parameters[FROM]);
        !empty($parameters[TO])      && self::setTo($parameters[TO]);
    }

    public function get() {
        return array(
            FROM => $this->getFrom(),
            TO => $this->getTo()
        );
    }

    public function getFrom() { return $this->from; }

    public function setFrom($from): void { $this->from = $from; }

    public function getTo() { return $this->to; }

    public function setTo($to): void { $this->to = $to; }


}
