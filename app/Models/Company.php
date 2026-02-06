<?php

namespace App\Models;

use App\Enums\CompanyPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cnpj',
        'plan',
    ];

    protected $casts = [
        'plan' => CompanyPlan::class,
    ];

    // public function jobs()
    // {
    //     return $this->hasMany(Job::class);
    // }

    public function canCreateJob(): bool
    {
        return $this->jobs()->count() < $this->plan->jobLimit();
    }
}
