<?php

namespace App\Http\Controllers;

use App\Actions\Company\{CreateCompany, DeleteCompany, UpdateCompany};
use App\Http\Requests\{StoreCompanyRequest, UpdateCompanyRequest};
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Company::class, 'company');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return CompanyResource::collection(Company::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request, CreateCompany $createCompany)
    {
        $company = $createCompany($request->validated());

        return (new CompanyResource($company))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company): CompanyResource
    {
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company, UpdateCompany $updateCompany): CompanyResource
    {
        $company = $updateCompany($company, $request->validated());

        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, DeleteCompany $deleteCompany): Response
    {
        $deleteCompany($company);

        return response()->noContent();
    }
}
