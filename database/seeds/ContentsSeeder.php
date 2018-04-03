<?php

use Illuminate\Database\Seeder;

use anlutro\LaravelSettings\Facade as Setting;
use \Modules\Content\Entities\Content;

class ContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        Setting::set('orders.cgv ', 1);

        /* Paiement accepté */
        $accept = Content::create([
            'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'published' => 1,
        ]);
        $accept->setTranslation('name', config('app.locale'), 'Paiement accepté')
            ->setTranslation('slug', config('app.locale'), str_slug('paiement-accepte'))
            ->setTranslation('description', config('app.locale'), $faker->text(300))
            ->update();
        Setting::set('orders.acceptId', $accept->id);
        $this->command->info('Seeded the content of payment accept !');

        /* Paiement refusé */
        $refuse = Content::create([
            'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'published' => 1,
        ]);
        $refuse->setTranslation('name', config('app.locale'), 'Paiement refusé')
            ->setTranslation('slug', config('app.locale'), str_slug('paiement-refuse'))
            ->setTranslation('description', config('app.locale'), $faker->text(300))
            ->update();
        Setting::set('orders.refuseId', $refuse->id);
        $this->command->info('Seeded the content of payment refuse !');

        /* CGV */
        $cgv = Content::create([
            'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'published' => 1,
            'on_footer' => 1
        ]);
        $cgv->setTranslation('name', config('app.locale'), 'Conditions Générales de vente')
            ->setTranslation('slug', config('app.locale'), str_slug('conditions-generals-de-vente'))
            ->setTranslation('description', config('app.locale'), $faker->text(300))
            ->update();

        Setting::set('orders.cgvId', $cgv->id);
        $this->command->info('Seeded the content of CGV !');

    }
}
