<?php

namespace App\Models;

use App\Observers\JobObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

#[ObservedBy(JobObserver::class)]
class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function hasUserApplied(User|int $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $this->jobApplications()->where('user_id', $userId)->exists();
    }

    protected $fillable = [
        'title',
        'description',
        'salary',
        'category',
        'location',
        'experience',
        'employer_id',
    ];

    protected $table = 'job-listings';

    public static array $experiences = ['entry', 'intermediate', 'senior'];
    public static array $categories = ['IT', 'Finance', 'Marketing', 'Sales', 'Human Resources', 'Engineering', 'Healthcare', 'Education', 'Legal', 'Other'];

    /**
     * Filter jobs by multiple criteria.
     *
     * @param  array{search?: ?string, min_salary?: ?int, max_salary?: ?int, experience?: ?string, category?: ?string}  $filters
     */
    public function scopeFilter(Builder|QueryBuilder $query, array $filters): Builder|QueryBuilder
    {
        return $query
            ->when($filters['search'] ?? null, fn (Builder|QueryBuilder $query, string $search) => $query->search($search))
            ->when($filters['min_salary'] ?? null, fn (Builder|QueryBuilder $query, int|string $minSalary) => $query->minSalary((int) $minSalary))
            ->when($filters['max_salary'] ?? null, fn (Builder|QueryBuilder $query, int|string $maxSalary) => $query->maxSalary((int) $maxSalary))
            ->when($filters['experience'] ?? null, fn (Builder|QueryBuilder $query, string $experience) => $query->experience($experience))
            ->when($filters['category'] ?? null, fn (Builder|QueryBuilder $query, string $category) => $query->category($category));
    }

    public function scopeSearch(Builder|QueryBuilder $query, string $search): Builder|QueryBuilder
    {
        return $query->where(function (Builder|QueryBuilder $query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhereHas('employer', function (Builder|QueryBuilder $query) use ($search) {
                    $query->where('company_name', 'like', '%' . $search . '%');
                });
        });
    }

    public function scopeMinSalary(Builder|QueryBuilder $query, int $minSalary): Builder|QueryBuilder
    {
        return $query->where('salary', '>=', $minSalary);
    }

    public function scopeMaxSalary(Builder|QueryBuilder $query, int $maxSalary): Builder|QueryBuilder
    {
        return $query->where('salary', '<=', $maxSalary);
    }

    public function scopeExperience(Builder|QueryBuilder $query, string $experience): Builder|QueryBuilder
    {
        return $query->where('experience', $experience);
    }

    public function scopeCategory(Builder|QueryBuilder $query, string $category): Builder|QueryBuilder
    {
        return $query->where('category', 'like', '%' . $category . '%');
    }
}
