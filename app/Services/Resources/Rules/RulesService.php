<?php

namespace App\Services\Resources\Rules;

use App\{
    Repositories\Resources\Rules\RulesRepositoryInterface,
    Traits\ApiResponse,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class RulesService
{
    use ApiResponse;

    public function __construct(protected RulesRepositoryInterface $rulesRepos) {}

    public function index()
    {
        return $this->successResponse([
            'rules' => $this->rulesRepos->getAll()
        ]);
    }

    public function update(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'content' => 'sometimes|string',
        ]);

       if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $rules = $this->rulesRepos->update($req);
            return $this->successResponse(['rules' => $rules], 'Rules updated successfully');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update rules: ' . $e->getMessage(), 500);
        }
    }
}
