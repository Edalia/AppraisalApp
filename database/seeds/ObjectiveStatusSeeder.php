<?php

use Illuminate\Database\Seeder;

class ObjectiveStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            ['id' => 1, 'status_type' => 'Completed'],
            ['id' => 2, 'status_type' => 'In Progress'],
            ['id' => 3, 'status_type' => 'Canceled'],   
        ];
        
        DB::table('status')->insert($status);
    }
}
