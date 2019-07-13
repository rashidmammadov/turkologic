<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 11.07.2019
 * Time: 17:49
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Etymon extends Model {

    protected $table = DB_ETYMON_TABLE;
    protected $primaryKey = ETYMON_ID;

    protected $fillable = [
        ETYMON_ID, LANGUAGE_ID, WORD, PRONUNCIATION, TYPE, DESCRIPTION
    ];

    public function lexeme() {
        return $this->hasOne('App\Lexeme');
    }

    public function language() {
        return $this->belongsTo('App\Language');
    }

    public function source() {
        return $this->hasMany('App\Source');
    }
}
