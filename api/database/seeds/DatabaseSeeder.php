<?php

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
         $this->call(LanguageTableSeeder::class);
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
        Language::create([LANGUAGE_ID => 14, NAME => 'Türkmen Türkçesi', CODE => 'trkm', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 12, STATUS => true ]);
        Language::create([LANGUAGE_ID => 15, NAME => 'Eski Oğuz (Anadolu) Türkçesi', CODE => 'eogt', FLAG => null, COUNTRY => null, CENTURY => 13, PARENT_LANGUAGE_ID => 12, STATUS => false ]);
        Language::create([LANGUAGE_ID => 16, NAME => 'Karahanlı Türkçesi', CODE => 'krhn', FLAG => null, COUNTRY => null, CENTURY => 11, PARENT_LANGUAGE_ID => 13, STATUS => false ]);
        /** Sixth Level **/
        Language::create([LANGUAGE_ID => 17, NAME => 'Osmanlı Türkçesi', CODE => 'osmn', FLAG => null, COUNTRY => null, CENTURY => 16, PARENT_LANGUAGE_ID => 15, STATUS => false ]);
        Language::create([LANGUAGE_ID => 18, NAME => 'Klasik Azerbaycan Türkçesi', CODE => 'klaz', FLAG => null, COUNTRY => null, CENTURY => 16, PARENT_LANGUAGE_ID => 15, STATUS => false ]);
        Language::create([LANGUAGE_ID => 19, NAME => 'Harezm-Kıpçak Türkçesi', CODE => 'hrzm', FLAG => null, COUNTRY => null, CENTURY => 13, PARENT_LANGUAGE_ID => 16, STATUS => false ]);
        /** Seventh Level **/
        Language::create([LANGUAGE_ID => 20, NAME => 'Gagauz Türkçesi', CODE => 'ggz', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 17, STATUS => true ]);
        Language::create([LANGUAGE_ID => 21, NAME => 'Türkiye Türkçesi', CODE => 'tur', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 17, STATUS => true ]);
        Language::create([LANGUAGE_ID => 22, NAME => 'Azerbaycan Türkçesi', CODE => 'aze', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 18, STATUS => true ]);
        Language::create([LANGUAGE_ID => 23, NAME => 'Çağatay Türkçesi', CODE => 'cgty', FLAG => null, COUNTRY => null, CENTURY => 15, PARENT_LANGUAGE_ID => 19, STATUS => false ]);
        /** Eighth Level **/
        Language::create([LANGUAGE_ID => 24, NAME => 'Özbek Türkçesi', CODE => 'uzb', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 25, NAME => 'Yeni Uygur Türkçesi', CODE => 'yugr', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 26, NAME => 'Kırgız Türkçesi', CODE => 'kgz', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 27, NAME => 'Karakalpak Türkçesi', CODE => 'krk', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 28, NAME => 'Kazak Türkçesi', CODE => 'kaz', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 29, NAME => 'Nogay Türkçesi', CODE => 'nog', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 30, NAME => 'Kumuk Türkçesi', CODE => 'kmk', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 31, NAME => 'Karaçay-Malkar Türkçesi', CODE => 'kcmk', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 32, NAME => 'Kırım Tatarcası', CODE => 'krm', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 33, NAME => 'Karay Türkçesi', CODE => 'kry', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 34, NAME => 'Kazan Tatarcası', CODE => 'kzn', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
        Language::create([LANGUAGE_ID => 35, NAME => 'Başkurt Türkçesi', CODE => 'bskt', FLAG => null, COUNTRY => null, CENTURY => 20, PARENT_LANGUAGE_ID => 23, STATUS => true ]);
    }
}
