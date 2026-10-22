<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'salary',
        'category',
        'location',
        'experience'
    ];

    protected $table = 'job-listings';

    public static array $experience = ['entry', 'intermediate', 'senior'];
    public static array $categories = ['IT', 'Finance', 'Marketing', 'Sales', 'Human Resources', 'Engineering', 'Healthcare', 'Education', 'Legal', 'Other'];
}
