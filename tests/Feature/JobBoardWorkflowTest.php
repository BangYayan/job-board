<?php

namespace Tests\Feature;

use App\Models\Employer;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobBoardWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_job_index_and_details(): void
    {
        $employer = Employer::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $job = Job::factory()->create([
            'employer_id' => $employer->id,
            'title' => 'Software Craftsman',
        ]);

        $response = $this->get(route('jobs.index'));
        $response->assertStatus(200);
        $response->assertSee('Software Craftsman');

        $detailResponse = $this->get(route('jobs.show', $job));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Software Craftsman');
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post(route('auth.store'), [
            'email' => 'testuser@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('jobs.index'));
        $this->assertAuthenticatedAs($user);

        $logoutResponse = $this->delete(route('auth.destroy', 'auth'));
        $logoutResponse->assertRedirect(route('jobs.index'));
        $this->assertGuest();
    }

    public function test_candidate_can_apply_for_job(): void
    {
        Storage::fake('local');

        $employer = Employer::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $job = Job::factory()->create([
            'employer_id' => $employer->id,
        ]);

        $candidate = User::factory()->create();

        $pdf = UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf');

        $response = $this->actingAs($candidate)
            ->post(route('job.application.store', $job), [
                'expected_salary' => 80000,
                'cv' => $pdf,
            ]);

        $response->assertRedirect(route('jobs.show', $job));
        $this->assertDatabaseHas('job_applications', [
            'job_id' => $job->id,
            'user_id' => $candidate->id,
            'expected_salary' => 80000,
        ]);
    }

    public function test_candidate_cannot_apply_twice_or_to_own_job(): void
    {
        $employerUser = User::factory()->create();
        $employer = Employer::factory()->create(['user_id' => $employerUser->id]);
        $job = Job::factory()->create(['employer_id' => $employer->id]);

        // Employer cannot apply to own job
        $response = $this->actingAs($employerUser)
            ->get(route('job.application.create', $job));
        $response->assertStatus(403);

        // Candidate applies once
        $candidate = User::factory()->create();
        JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $candidate->id,
        ]);

        // Second application attempt is forbidden
        $secondAttempt = $this->actingAs($candidate)
            ->get(route('job.application.create', $job));
        $secondAttempt->assertStatus(403);
    }

    public function test_candidate_can_cancel_job_application(): void
    {
        $candidate = User::factory()->create();
        $employer = Employer::factory()->create(['user_id' => User::factory()->create()->id]);
        $job = Job::factory()->create(['employer_id' => $employer->id]);

        $application = JobApplication::factory()->create([
            'job_id' => $job->id,
            'user_id' => $candidate->id,
        ]);

        $response = $this->actingAs($candidate)
            ->delete(route('my-job-applications.destroy', $application));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('job_applications', [
            'id' => $application->id,
        ]);
    }

    public function test_user_can_create_employer_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('employer.store'), [
                'company_name' => 'Starlight Innovations',
            ]);

        $response->assertRedirect(route('my-jobs.index'));
        $this->assertDatabaseHas('employers', [
            'user_id' => $user->id,
            'company_name' => 'Starlight Innovations',
        ]);
    }

    public function test_employer_can_create_update_and_delete_job(): void
    {
        $user = User::factory()->create();
        $employer = Employer::factory()->create(['user_id' => $user->id]);

        // Create
        $createResponse = $this->actingAs($user)
            ->post(route('my-jobs.store'), [
                'title' => 'DevOps Specialist',
                'location' => 'Remote',
                'salary' => 95000,
                'category' => 'IT',
                'experience' => 'intermediate',
                'description' => 'Great job description with requirements.',
            ]);

        $createResponse->assertRedirect(route('my-jobs.index'));
        $job = Job::where('title', 'DevOps Specialist')->first();
        $this->assertNotNull($job);

        // Update
        $updateResponse = $this->actingAs($user)
            ->put(route('my-jobs.update', $job), [
                'title' => 'Lead DevOps Specialist',
                'location' => 'Austin, TX',
                'salary' => 110000,
                'category' => 'IT',
                'experience' => 'senior',
                'description' => 'Updated job description.',
            ]);

        $updateResponse->assertRedirect(route('my-jobs.index'));
        $this->assertDatabaseHas('job-listings', [
            'id' => $job->id,
            'title' => 'Lead DevOps Specialist',
        ]);

        // Delete
        $deleteResponse = $this->actingAs($user)
            ->delete(route('my-jobs.destroy', $job));

        $deleteResponse->assertRedirect(route('my-jobs.index'));
        $this->assertDatabaseMissing('job-listings', [
            'id' => $job->id,
        ]);
    }

    public function test_job_observer_invalidates_cache_on_job_mutation(): void
    {
        Cache::flush();
        Cache::forever('jobs_list_cache_version', 1);

        $employer = Employer::factory()->create(['user_id' => User::factory()->create()->id]);

        // Creating a job increments list cache version
        $job = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Cached Job Title',
            'location' => 'Remote',
            'salary' => 90000,
            'category' => 'IT',
            'experience' => 'senior',
            'description' => 'Description here.',
        ]);

        $this->assertEquals(2, Cache::get('jobs_list_cache_version'));

        // Caching a single job
        Cache::put("job:{$job->id}", 'cached-content');

        // Updating the job clears its key and increments list version
        $job->update(['salary' => 95000]);
        $this->assertNull(Cache::get("job:{$job->id}"));
        $this->assertEquals(3, Cache::get('jobs_list_cache_version'));
    }
}
