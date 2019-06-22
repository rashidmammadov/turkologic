<?php
/**
 * Created by IntelliJ IDEA.
 * User: rashid
 * Date: 10.06.2019
 * Time: 13:47
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model {

    protected $table = DB_LANGUAGE_TABLE;
    protected $primaryKey = LANGUAGE_ID;

    protected $fillable = [
        LANGUAGE_ID, NAME, CODE, FLAG, COUNTRY, CENTURY, PARENT_LANGUAGE_ID, STATUS
    ];

    public function lexeme() {
        return $this->hasMany('App\Lexeme');
    }

    /** SELECT * FROM `users` WHERE `name` REGEXP '[adni]{2,}'
     * girilecek karakter dizisi
     * karakter sayısının en az yarısı kadar uymalıdır
     * öncelikle ünsüz karakter eşleşmesine ikincilikle ise ünlü
     * tekrar olmaması lazım
     * en çok uyandan en az uyana sıralaması lazım
     */
}
