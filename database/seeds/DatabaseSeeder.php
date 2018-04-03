<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        $this->call('CountriesSeeder');
        $this->call('AdminSeeder');
        $this->call('OrderStatusSeeder');
        $this->call('CarriersSeeder');
        $this->call('ContentsSeeder');
    }
}
