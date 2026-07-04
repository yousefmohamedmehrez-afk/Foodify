<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PaymentMethodSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PaymentMethodSeeder::class,
        ]);
    }
}