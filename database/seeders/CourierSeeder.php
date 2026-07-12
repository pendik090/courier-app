<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        $couriers = [
            [
                'name' => 'Ahmad Rizki Pratama',
                'email' => 'ahmad.rizki@gmail.com',
                'phone' => '081234567890',
                'level' => 3,
            ],
            [
                'name' => 'Budi Hadi Agung',
                'email' => 'budiagung@gmail.com',
                'phone' => '081234567891',
                'level' => 2,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@gmail.com',
                'phone' => '081234567892',
                'level' => 4,
            ],
            [
                'name' => 'Eko Wahyudi',
                'email' => 'eko.wahyudi@gmail.com',
                'phone' => '081234567893',
                'level' => 1,
            ],
            [
                'name' => 'Fitri Handayani',
                'email' => 'fitri.handayani@gmail.com',
                'phone' => '081234567894',
                'level' => 5,
            ],
            [
                'name' => 'Gunawan Wijaya',
                'email' => 'gunawan.wijaya@gmail.com',
                'phone' => '081234567895',
                'level' => 3,
            ],
            [
                'name' => 'Hendra Kusuma',
                'email' => 'hendra.kusuma@gmail.com',
                'phone' => '081234567896',
                'level' => 2,
            ],
            [
                'name' => 'Indah Permatasari',
                'email' => 'indah.permatasari@gmail.com',
                'phone' => '081234567897',
                'level' => 4,
            ],
            [
                'name' => 'Joko Pramono',
                'email' => 'joko.pramono@gmail.com',
                'phone' => '081234567898',
                'level' => 1,
            ],
            [
                'name' => 'Kartika Sari',
                'email' => 'kartika.sari@gmail.com',
                'phone' => '081234567899',
                'level' => 3,
            ],
        ];

        foreach ($couriers as $courier) {
            Courier::create($courier);
        }
    }
}
