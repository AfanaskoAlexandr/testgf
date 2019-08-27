<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TariffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tariffs = [];

        $tariffs[] = [
            'id' => 1,
            'name' => 'Полезное меню',
            'price' => 2499,
            'available_days' => json_encode(['1' => 'Mon', '3' => 'Wed', '5' => 'Fri'])
        ];

        $tariffs[] = [
            'id' => 2,
            'name' => 'Вредное меню',
            'price' => 2099,
            'available_days' => json_encode(['2' => 'Tue', '4' => 'Thu'])
        ];

        $tariffs[] = [
            'id' => 3,
            'name' => 'Вегетарианское меню',
            'price' => 3499,
            'available_days' => json_encode(['6' => 'Sat', '7' => 'Sun'])
        ];

        DB::table('tariffs')->insert($tariffs);
    }
}
