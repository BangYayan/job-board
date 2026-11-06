<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::all()->shuffle();
        
        for($i = 0; $i < 20; $i++) {
            Employer::factory()->create([
                'user_id' => $user->pop()->id,
            ]);
        }
        $jobs = Job::all();
        
        foreach($jobs as $job) {
            $job->employer_id = Employer::all()->random()->id;
            $job->save();
        }
    }
}
