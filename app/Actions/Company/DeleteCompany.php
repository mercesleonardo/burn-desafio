<?php

namespace App\Actions\Company;

use App\Models\Company;

class DeleteCompany
{
    /**
     * Delete the given company instance.
     *
     * @param  Company  $company
     */
    public function __invoke(Company $company): void
    {
        $company->delete();
    }

}
