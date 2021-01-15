<?php

use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [
            ['id' => 1, 'skill_name' => 'Teamwork'],
            ['id' => 2, 'skill_name' => 'Problem-Solving'],
            ['id' => 3, 'skill_name' => 'Communication'],
            ['id' => 4, 'skill_name' => 'Professionalism/Work-ethic'],   
        ];
        
        DB::table('skill')->insert($skills);
    }
}
