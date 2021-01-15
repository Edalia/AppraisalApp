<?php

use Illuminate\Database\Seeder;

class ObjectivePrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priorities = [
            ['id' => 1, 'priority_type' => 'Low'],
            ['id' => 2, 'priority_type' => 'Moderate'],
            ['id' => 3, 'priority_type' => 'High'],    
        ];

        DB::table('objectivepriority')->insert($priorities);
    }
}
