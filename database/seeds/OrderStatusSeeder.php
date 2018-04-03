<?php

use Illuminate\Database\Seeder;
use \Modules\Order\Entities\Status;
use \Ramsey\Uuid\Uuid;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Paiement accepté */
         $status = Status::create([
             "uuid" => Uuid::uuid4()->toString(),
             'color' => '#2ab27b',
             'order_accept' => 1,
             'order_refuse' => 0,
         ]);
        $status->setTranslation('name', config('app.locale'), 'Paiement accepté')->update();

        /* Paiement refusé */
        $status = Status::create([
            "uuid" => Uuid::uuid4()->toString(),
            'color' => '#bf5329',
            'order_accept' => 0,
            'order_refuse' => 1,
        ]);
        $status->setTranslation('name', config('app.locale'), 'Paiement refusé')->update();

        /* Commande en préparation */
        $status = Status::create([
            "uuid" => Uuid::uuid4()->toString(),
            'color' => null,
            'order_accept' => 0,
            'order_refuse' => 0,
        ]);
        $status->setTranslation('name', config('app.locale'), 'Commande en préparation')->update();


        $this->command->info('Seeded the order\'s status !');
    }
}
