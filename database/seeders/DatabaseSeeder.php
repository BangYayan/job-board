<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a dedicated Employer account for testing
        $employerUser = User::factory()->create([
            'name' => 'Demo Employer',
            'email' => 'employer@test.com',
            'password' => 'password',
        ]);

        $demoEmployer = Employer::factory()->create([
            'company_name' => 'TechCorp Solutions',
            'user_id' => $employerUser->id,
        ]);

        // Create initial jobs for the demo employer
        Job::factory()->create([
            'employer_id' => $demoEmployer->id,
            'title' => 'Senior Full Stack Engineer',
            'category' => 'IT',
            'experience' => 'senior',
            'salary' => 120000,
            'location' => 'Remote',
            'description' => "We are looking for a skilled Senior Full Stack Engineer to build and scale our web applications.\n\nRequirements:\n- 5+ years of Laravel & Vue/React experience\n- Strong database architecture knowledge\n- Experience with automated testing and CI/CD pipelines",
        ]);

        Job::factory()->create([
            'employer_id' => $demoEmployer->id,
            'title' => 'Junior Backend Developer',
            'category' => 'IT',
            'experience' => 'entry',
            'salary' => 55000,
            'location' => 'San Francisco, CA',
            'description' => "Great entry-level opportunity for an ambitious developer looking to grow in a fast-paced environment.\n\nRequirements:\n- Basic PHP & Laravel knowledge\n- Eagerness to learn and collaborate\n- Good problem-solving mindset",
        ]);

        // 2. Create a dedicated Candidate account for testing
        $candidateUser = User::factory()->create([
            'name' => 'Demo Candidate',
            'email' => 'candidate@test.com',
            'password' => 'password',
        ]);

        // 3. Create other users and employers
        $users = User::factory(40)->create();

        $employers = collect([$demoEmployer]);
        foreach ($users->take(10) as $user) {
            $employers->push(Employer::factory()->create([
                'user_id' => $user->id,
            ]));
        }

        // 4. Create random jobs assigned to employers
        $jobs = collect();
        for ($i = 0; $i < 50; $i++) {
            $jobs->push(Job::factory()->create([
                'employer_id' => $employers->random()->id,
            ]));
        }

        // 5. Seed sample job applications
        // Candidate applies to some jobs
        foreach ($jobs->take(3) as $job) {
            if ($job->employer_id !== $demoEmployer->id) {
                JobApplication::factory()->create([
                    'job_id' => $job->id,
                    'user_id' => $candidateUser->id,
                ]);
            }
        }

        // Other users apply to random jobs (including demo employer's jobs)
        foreach ($users->skip(10) as $user) {
            $randomJob = $jobs->random();
            if (! $randomJob->hasUserApplied($user)) {
                JobApplication::factory()->create([
                    'job_id' => $randomJob->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
