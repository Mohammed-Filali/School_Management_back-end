<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admine;
use App\Models\StudentParent;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('class_types')->insert([

            'name'=>'DEVELOPPEMENT',
            'code'=>'DIA_DEVFS_TS'
        ]);
        DB::table('classes')->insert([
            'name'=>'DIA_DEVFS_TS_G1',
            'code'=>201,
            'class_type_id'=>1
        ]);

        DB::table('courses')->insert([
            'name'=>'devloppement front end',
            'desc'=>'est unr for mation pour le devloppemnt front end'
        ]);
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'date_of_birth'=>fake()->date(),
            'blood_Type'=>'O+',
            'adress'=>fake()->address(),
            'phone'=>substr(fake()->phoneNumber(),10),
            'email' => 'mohammed@gmail.com',
            'password' => Hash::make('123456789'),
            'classe_id'=>1
        ]);

        Admine::factory()->create([
            'firsName' => 'Admine',
            'lastName' => 'Admine',
            'blood_Type'=>'O+',
            'date_of_birth'=>fake()->date(),
            'adress'=>fake()->address(),
            'phone'=>substr(fake()->phoneNumber(),10),
            'email' => 'Admin@gmail.com',
            'password' => Hash::make('123456789'),
        ]);

        Teacher::factory()->create([
            'firsName' => 'Teacher',
            'lastName' => 'Teacher',
            'blood_Type'=>'O+',
            'date_of_birth'=>fake()->date(),
            'adress'=>fake()->address(),
            'phone'=>substr(fake()->phoneNumber(),10),
            'email' => 'Teacher@gmail.com',
            'password' => Hash::make('123456789'),
            'course_id'=>1
        ]);

        StudentParent::factory()->create([
            'firsName' => 'Parent',
            'lastName' =>'Parent',
            'date_of_birth'=>fake()->date(),
            'last_login'=>fake()->date(),
            'adress'=>fake()->address(),
            'phone'=>substr(fake()->phoneNumber(),10),
            'email' => 'Parent@gmail.com',
            'password' => Hash::make('123456789'),
            'blood_Type'=>'O+',
        ]);


    }
}
