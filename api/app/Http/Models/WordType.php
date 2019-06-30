<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 30.06.2019
 * Time: 16:56
 */

namespace App\Http\Models;


class WordType {

    private static $wordTypes = array(
        array( ID => 0, NAME => 'bağlaç' ),
        array( ID => 1, NAME => 'edat' ),
        array( ID => 2, NAME => 'fiil' ),
        array( ID => 3, NAME => 'isim' ),
        array( ID => 4, NAME => 'sıfat' ),
        array( ID => 5, NAME => 'zamir' ),
        array( ID => 6, NAME => 'zarf' ),
        array( ID => 7, NAME => 'ünlem' ),
        array( ID => 8, NAME => 'mecaz')
    );

    private $id;
    private $name;

    public function __construct($parameters) {
        !empty($parameters[ID])    && self::setId($parameters[ID]);
        !empty($parameters[NAME])  && self::setName($parameters[NAME]);
    }

    public function get() {
        return array(
            ID => $this->getId(),
            NAME => $this->getName()
        );
    }

    public function getId() { return $this->id; }

    public function setId($id): void { $this->id = $id; }

    public function getName() { return $this->name; }

    public function setName($name): void { $this->name = $name; }

    public static function getTypeByName($name) {
        $result = null;
        foreach (self::$wordTypes as $type) {
            if (strtolower($type[NAME]) == strtolower($name)) {
                $result = $type[ID];
            }
        }
        return $result;
    }

}
