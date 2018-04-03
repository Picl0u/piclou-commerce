<?php

use Illuminate\Database\Seeder;

class CarriersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Modules\Order\Entities\Carriers::create([
            'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'free' => 0,
            'price' => 1,
            'weight' => 0,
            'name' => 'Transporteur',
            'delay' => '5 jours',
            'url' => 'http://www.suiviscolis.fr?id=',
            'published' => 1,
            'default' => 1,
            'default_price' => 0
        ]);

        $this->command->info('Seeded the default carrier !');
    }
}
