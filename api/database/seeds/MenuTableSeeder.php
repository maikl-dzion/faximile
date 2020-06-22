<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu')->delete();
        Menu::create([
            'title' => 'Доставка',
            'name'  => 'Deliver',
            'type' => '1',
            'parent_id' => 0,
            'note' => 'Content First Post body',
        ]);

        Menu::create([
            'title' => 'Контакты',
            'name'  => 'Contacts',
            'type' => '1',
            'parent_id' => 0,
            'note' => 'Content First Post body',
        ]);

        Menu::create([
            'title' => 'О компании',
            'name'  => 'About',
            'type' => '1',
            'parent_id' => 0,
            'note' => 'Content First Post body',
        ]);
    }
}
