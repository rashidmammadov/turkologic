<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 12.07.2019
 * Time: 16:48
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model {

    protected $table = DB_SOURCE_TABLE;
    protected $primaryKey = SOURCE_ID;

    protected $fillable = [SOURCE_ID, ETYMON_ID, SAMPLE, REFERENCE];

    public function etymon() {
        return $this->belongsTo('App\Etymon');
    }
}
