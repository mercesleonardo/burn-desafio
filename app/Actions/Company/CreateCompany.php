<?php

namespace App\Actions\Company;

use App\Models\Company;

class CreateCompany
{
    /**
     * Create a new company instance.
     *
     * @param  array<string, mixed>  $data
     */
    public function __invoke(array $data): Company
    {
        return Company::create($data);
    }
}
