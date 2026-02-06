<?php

namespace App\Actions\Company;

use App\Models\Company;

class UpdateCompany
{
    /**
     * Update the given user instance.
     * @param  Company  $company
     * @param  array<string, mixed>  $data
     *
     */
    public function __invoke(Company $company, array $data): Company
    {
        $company->update($data);

        return $company;
    }
}
