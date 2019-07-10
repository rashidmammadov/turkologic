<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 10.07.2019
 * Time: 12:43
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Belong extends Model {

    protected $table = DB_BELONG_TABLE;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [FROM, TO];

    public function semantics() {
        return $this->belongsTo('App\Semantics');
    }
}
