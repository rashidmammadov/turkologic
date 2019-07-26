<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 25.07.2019
 * Time: 15:51
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model {

    protected $table = DB_CITY_TABLE;
    protected $primaryKey = CITY_ID;

    protected $fillable = [
        CITY_ID, CITY_NAME, LATITUDE, LONGITUDE
    ];

    public function country() {
        return $this->belongsTo('App\Country');
    }
}
