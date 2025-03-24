<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SNMP;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $salt = Str::random(64);

        User::create([
            'name' => 'kml',
            'email' => 'kml@tec.dk',
            'salt' => $salt,
            'password' => Hash::make('Test1234!?'.$salt),
            'about_me' => 'This is about me'
        ]);
        User::create([
            'name' => 'ws',
            'email' => 'ws@tec.dk',
            'salt' => $salt,
            'password' => Hash::make('Test1234!?'.$salt),
            'about_me' => 'This is about me'
        ]);
        User::create([
            'name' => 'mrv',
            'email' => 'mrv@tec.dk',
            'salt' => $salt,
            'password' => Hash::make('Test1234!?'.$salt),
            'about_me' => 'This is about me'
        ]);

        Category::create([
            'name' => 'Woodworking'
        ]);
        Category::create([
            'name' => 'SAHM'
        ]);

        Group::create([
            'user_id' => 2,
            'category_id' => 1,
            'image_id' => null,
            'name' => 'Carpenters united',
            'description' => 'We are the union of carpenters that are united'
        ]);
        Group::create([
            'user_id' => 3,
            'category_id' => 2,
            'image_id' => null,
            'name' => 'Working moms',
            'description' => 'We are the Stay At Home Moms'
        ]);

        Post::create([
            'title' => 'This is about my past work experiences',
            'content' => 'Lorem ipsum doler sit ammet',
            'user_id' => 1
        ]);
        Post::create([
            'title' => 'This is about my last job',
            'content' => 'Lorem ipsum doler sit ammet',
            'user_id' => 2,
            'group_id' => 1
        ]);
        Post::create([
            'title' => 'My experience with watching two toddlers while cleaning',
            'content' => 'Lorem ipsum doler sit ammet',
            'user_id' => 3,
            'group_id' => 2
        ]);
    }
}
