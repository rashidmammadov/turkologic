<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 25.07.2019
 * Time: 15:41
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {

    protected $table = DB_COUNTRY_TABLE;
    protected $primaryKey = COUNTRY_ID;

    protected $fillable = [
        COUNTRY_ID, COUNTRY_NAME, CAPITAL_ID
    ];

    public function city() {
        return $this->hasMany('App\City');
    }

    public function language() {
        return $this->belongsTo('App\Language');
    }
}
