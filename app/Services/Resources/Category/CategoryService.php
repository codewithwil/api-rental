<?php

namespace App\Services\Resources\Category;

use App\{
    Repositories\Resources\Category\CategoryRepositoryInterface,
    Traits\ApiResponse,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class CategoryService
{
    use ApiResponse;

    public function __construct(protected CategoryRepositoryInterface $categoryRepo) {}

    public function index()
    {
        return $this->successResponse([
            'category' => $this->categoryRepo->getAll()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'category' => $this->categoryRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:75',
            'type' => 'required|integer|in:1,2',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $category = $this->categoryRepo->store($req);
            return $this->successResponse(['category' => $category], 'Category created');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create category: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $category = $this->categoryRepo->find($id); 

        $validator = Validator::make($req->all(), [
            'name' => 'sometimes|string|max:75',
            'type' => 'sometimes|integer|in:1,2',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $category = $this->categoryRepo->update($req, $id);
            return $this->successResponse(['category' => $category], 'Category updated');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update category: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        $this->categoryRepo->delete($id);
        return $this->successResponse([], 'Category deleted');
    }
}
