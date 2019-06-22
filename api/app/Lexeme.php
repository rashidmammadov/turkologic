<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 16.06.2019
 * Time: 18:05
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lexeme extends Model {

    protected $table = DB_LEXEME_TABLE;
    protected $primaryKey = LEXEME_ID;

    protected $fillable = [
        LEXEME_ID, LEXEME, PRONUNCIATION, LATIN_TEXT, ALPHABET, LANGUAGE_ID, ETYMON_ID
    ];

    public function language() {
        return $this->belongsTo('App\Language');
    }

    public function semantics() {
        return $this->hasMany('App\Semantics');
    }

}
