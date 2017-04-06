<?php

use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post')->insert([
            'title'         => 'About Us',
            'slug'          => 'about-us',
            'content'       => 'About Us Content...',
            'image'         => '',
            'post_status'   => 'Publish',
        ]);

        DB::table('post')->insert([
            'title'         => 'Bundlebox',
            'slug'          => 'bundlebox',
            'content'       => 'Bundlebox Content...',
            'image'         => '',
            'post_status'   => 'Publish',
        ]);

        DB::table('post')->insert([
            'title'         => 'Contribute',
            'slug'          => 'contribute',
            'content'       => 'Contribute Content...',
            'image'         => '',
            'post_status'   => 'Publish',
        ]);

        DB::table('post')->insert([
            'title'         => 'Collection',
            'slug'          => 'collection',
            'content'       => 'Collection Content...',
            'image'         => '',
            'post_status'   => 'Publish',
        ]);

        DB::table('post')->insert([
            'title'         => 'Donate',
            'slug'          => 'donate',
            'content'       => 'Donate Content...',
            'image'         => '',
            'post_status'   => 'Publish',
        ]);
    }
}
