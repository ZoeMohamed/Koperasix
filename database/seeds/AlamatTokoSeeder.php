<?php

namespace Database\Seeders;

use App\AlamatToko;
use Illuminate\Database\Seeder;

class AlamatTokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['city_id' => 152,'detail' => '']
        ];
        AlamatToko::insert($data);

    }
}
