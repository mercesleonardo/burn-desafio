<?php

namespace App\Models;

use App\Enums\CompanyPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

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

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function canCreateJob(): bool
    {
        return $this->positions()->count() < $this->plan->jobLimit();
    }
}
