<?php

namespace App\Services\Resources\Company;

use App\{
    Repositories\Resources\Company\CompanyRepositoryInterface,
    Traits\ApiResponse,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class CompanyService
{
    use ApiResponse;

    public function __construct(protected CompanyRepositoryInterface $companyRepo) {}

    public function index()
    {
        return $this->successResponse([
            'company' => $this->companyRepo->getAll()
        ]);
    }

    public function update(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name'    => 'sometimes|string|max:75',
            'web'     => 'sometimes|string|max:255',
            'phone'   => ['sometimes', 'string', 'max:20', 'regex:/^\+?[0-9\s\-]+$/'],
            'address' => 'sometimes|string|max:255',
            'photo'   => 'sometimes|image|mimes:jpg,jpeg,png|max:2048'
        ]);

       if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $company = $this->companyRepo->update($req);
            return $this->successResponse(['company' => $company], 'Company updated successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update company: ' . $e->getMessage(), 500);
        }
    }
}
