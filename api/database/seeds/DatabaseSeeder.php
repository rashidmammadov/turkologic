<?php

use App\City;
use App\Country;
use App\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call(CityTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
    }
}

class CityTableSeeder extends Seeder {

    public function run() {
        DB::table(DB_CITY_TABLE)->delete();

        City::create([CITY_ID => 1, CITY_NAME => 'Ankara', LATITUDE => 39.925533, LONGITUDE => 32.866287]);
        City::create([CITY_ID => 2, CITY_NAME => 'Aşkabat', LATITUDE => 37.862499, LONGITUDE => 58.238056]);
        City::create([CITY_ID => 3, CITY_NAME => 'Bakü', LATITUDE => 40.409264, LONGITUDE => 49.867092]);
        City::create([CITY_ID => 4, CITY_NAME => 'Bişkek', LATITUDE => 42.882004, LONGITUDE => 74.582748]);
        City::create([CITY_ID => 5, CITY_NAME => 'Nur-Sultan (Astana)', LATITUDE => 51.169392, LONGITUDE => 71.449074]);
        City::create([CITY_ID => 6, CITY_NAME => 'Taşkent', LATITUDE => 41.311081, LONGITUDE => 69.240562]);
        City::create([CITY_ID => 7, CITY_NAME => 'Urumçi', LATITUDE => 43.825592, LONGITUDE => 87.616852]);
    }

}

class CountryTableSeeder extends Seeder {

    public function run() {
        DB::table(DB_COUNTRY_TABLE)->delete();

        Country::create([COUNTRY_ID => 1, COUNTRY_NAME => 'Azerbaycan', CAPITAL_ID => 3]);
        Country::create([COUNTRY_ID => 2, COUNTRY_NAME => 'Kazakistan', CAPITAL_ID => 5]);
        Country::create([COUNTRY_ID => 3, COUNTRY_NAME => 'Kırgızistan', CAPITAL_ID => 4]);
        Country::create([COUNTRY_ID => 4, COUNTRY_NAME => 'Türkiye', CAPITAL_ID => 1]);
        Country::create([COUNTRY_ID => 5, COUNTRY_NAME => 'Türkmenistan', CAPITAL_ID => 2]);
        Country::create([COUNTRY_ID => 6, COUNTRY_NAME => 'Özbekistan', CAPITAL_ID => 6]);
        Country::create([COUNTRY_ID => 7, COUNTRY_NAME => 'Uygur', CAPITAL_ID => 7]);
    }

}

class LanguageTableSeeder extends Seeder {

    public function run() {
        DB::table(DB_LANGUAGE_TABLE)->delete();

        /** First Level **/
        Language::create([LANGUAGE_ID => 1, NAME => 'İlk Türkçe', CODE => 'itr', FLAG => null, COUNTRY => null, CENTURY => null, PARENT_LANGUAGE_ID => null, STATUS => false ]);
        /** Second Level **/
        Language::create([LANGUAGE_ID => 2, NAME => 'Ana Bulgarca', CODE => 'abg', FLAG => null, COUNTRY => null, CENTURY => 1, PARENT_LANGUAGE_ID => 1, STATUS => false ]);
        Language::create([LANGUAGE_ID => 3, NAME => 'Ana Türkçe', CODE => 'atr', FLAG => null, COUNTRY => null, CENTURY => 1, PARENT_LANGUAGE_ID => 1, STATUS => false ]);
        /** Third Level **/
        Language::create([LANGUAGE_ID => 4, NAME => 'Tuna Bulgar Türkçesi', CODE => 'tbtr', FLAG => null, COUNTRY => null, CENTURY => 6, PARENT_LANGUAGE_ID => 2, STATUS => false ]);
        Language::create([LANGUAGE_ID => 5, NAME => 'Yakut (Saha) Türkçesi', CODE => 'ykt', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 3, STATUS => true ]);
        Language::create([LANGUAGE_ID => 6, NAME => 'Göktürkçe', CODE => 'gktr', FLAG => null, COUNTRY => null, CENTURY => 6, PARENT_LANGUAGE_ID => 3, STATUS => false ]);
        /** Fourth Level **/
        Language::create([LANGUAGE_ID => 7, NAME => 'İdil Bulgar Türkçesi', CODE => 'idlb', FLAG => null, COUNTRY => null, CENTURY => 13, PARENT_LANGUAGE_ID => 4, STATUS => false ]);
        Language::create([LANGUAGE_ID => 8, NAME => 'Çuvaş Türkçesi', CODE => 'cvs', FLAG => null, COUNTRY => null, CENTURY => 19, PARENT_LANGUAGE_ID => 4, STATUS => true ]);
        Language::create([LANGUAGE_ID => 9, NAME => 'Altay Türkçesi', CODE => 'alty', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 6, STATUS => true ]);
        Language::create([LANGUAGE_ID => 10, NAME => 'Tuva Türkçesi', CODE => 'tuva', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 6, STATUS => true ]);
        Language::create([LANGUAGE_ID => 11, NAME => 'Hakas Türkçesi', CODE => 'hks', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 6, STATUS => true ]);
        Language::create([LANGUAGE_ID => 12, NAME => 'Ana Oğuz Türkçesi', CODE => 'aogt', FLAG => null, COUNTRY => null, CENTURY => 10, PARENT_LANGUAGE_ID => 6, STATUS => false ]);
        Language::create([LANGUAGE_ID => 13, NAME => 'Uygur Türkçesi', CODE => 'ugr', FLAG => null, COUNTRY => null, CENTURY => 9, PARENT_LANGUAGE_ID => 6, STATUS => false ]);
        /** Fifth Level **/
        Language::create([LANGUAGE_ID => 14, NAME => 'Türkmen Türkçesi', CODE => 'trkm', FLAG => 'turkmenistan', COUNTRY => 5, CENTURY => 20, PARENT_LANGUAGE_ID => 12, STATUS => true ]);
        Language::create([LANGUAGE_ID => 15, NAME => 'Eski Oğuz (Anadolu) Türkçesi', CODE => 'eogt', FLAG => null, COUNTRY => null, CENTURY => 13, PARENT_LANGUAGE_ID => 12, STATUS => false ]);
        Language::create([LANGUAGE_ID => 16, NAME => 'Karahanlı Türkçesi', CODE => 'krhn', FLAG => null, COUNTRY => null, CENTURY => 11, PARENT_LANGUAGE_ID => 13, STATUS => false ]);
        /** Sixth Level **/
        Language::create([LANGUAGE_ID => 17, NAME => 'Osmanlı Türkçesi', CODE => 'osmn', FLAG => null, COUNTRY => null, CENTURY => 16, PARENT_LANGUAGE_ID => 15, STATUS => false ]);
        Language::create([LANGUAGE_ID => 18, NAME => 'Klasik Azerbaycan Türkçesi', CODE => 'klaz', FLAG => null, COUNTRY => null, CENTURY => 16, PARENT_LANGUAGE_ID => 15, STATUS => false ]);
        Language::create([LANGUAGE_ID => 19, NAME => 'Harezm-Kıpçak Türkçesi', CODE => 'hrzm', FLAG => null, COUNTRY => null, CENTURY => 13, PARENT_LANGUAGE_ID => 16, STATUS => false ]);
        /** Seventh Level **/
        Language::create([LANGUAGE_ID => 20, NAME => 'Gagauz Türkçesi', CODE => 'ggz', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 17, STATUS => true ]);
        Language::create([LANGUAGE_ID => TUR_ID, NAME => 'Türkiye Türkçesi', CODE => 'tur', FLAG => 'turkey', COUNTRY => 4, CENTURY => 20, PARENT_LANGUAGE_ID => 17, STATUS => true ]);
        Language::create([LANGUAGE_ID => 22, NAME => 'Azerbaycan Türkçesi', CODE => 'aze', FLAG => 'azerbaijan', COUNTRY => 1, CENTURY => 20, PARENT_LANGUAGE_ID => 18, STATUS => true ]);
        Language::create([LANGUAGE_ID => 23, NAME => 'Çağatay Türkçesi', CODE => 'cgty', FLAG => null, COUNTRY => null, CENTURY => 15, PARENT_LANGUAGE_ID => 19, STATUS => false ]);
        /** Eighth Level **/
        Language::create([LANGUAGE_ID => 24, NAME => 'Özbek Türkçesi', CODE => 'uzb', FLAG => 'uzbekistan', COUNTRY => 6, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 25, NAME => 'Çağdaş Uygur Türkçesi', CODE => 'cugr', FLAG => 'uyghur', COUNTRY => 7, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 26, NAME => 'Kırgız Türkçesi', CODE => 'kgz', FLAG => 'kyrgyzstan', COUNTRY => 3, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 27, NAME => 'Karakalpak Türkçesi', CODE => 'krk', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 28, NAME => 'Kazak Türkçesi', CODE => 'kaz', FLAG => 'kazakhstan', COUNTRY => 2, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 29, NAME => 'Nogay Türkçesi', CODE => 'nog', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 30, NAME => 'Kumuk Türkçesi', CODE => 'kmk', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 31, NAME => 'Karaçay-Malkar Türkçesi', CODE => 'kcmk', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 32, NAME => 'Kırım Tatarcası', CODE => 'krm', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 33, NAME => 'Karay Türkçesi', CODE => 'kry', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 34, NAME => 'Kazan Tatarcası', CODE => 'kzn', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 35, NAME => 'Başkurt Türkçesi', CODE => 'bskt', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
    }
}
