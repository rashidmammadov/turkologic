<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 16.06.2019
 * Time: 19:27
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semantics extends Model {

    protected $table = DB_SEMANTICS_TABLE;
    protected $primaryKey = SEMANTIC_ID;

    protected $fillable = [
        SEMANTIC_ID, LEXEME_ID, TYPE, MEANING, SAMPLE, REFERENCE, BELONG_TO, METAPHOR
    ];

    public function lexeme() {
        return $this->belongsTo('App\Lexeme');
    }
}
