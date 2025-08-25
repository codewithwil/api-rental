<?php

namespace App\Repositories\Resources\Category;

use App\{
    Repositories\Resources\Category\CategoryRepositoryInterface,
    Models\Resources\Category\Category,
    Traits\DbTransaction,
};

use Illuminate\{
    Http\Request,
};

class CategoryRepository implements CategoryRepositoryInterface
{
    use DbTransaction;
    public function getAll()
    {
        return Category::where('status', Category::STATUS_ACTIVE)
            ->get()
            ->map(function ($category) {
                $category->type_name = match($category->type) {
                    Category::TYPE_CAR => 'Mobil',
                    Category::TYPE_MOTORCYCLE => 'Motor',
                    default => 'Unknown',
                };
                return $category;
            });
    }

    public function find($id)
    {
        return Category::findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $category = Category::create([
                'name'    => $req->input('name'),
                'type'    => $req->input('type'),
            ]);

            return $category;
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $category = Category::findOrFail($id);
            $category->update($req->only(['name', 'type']));
            return $category;
        });
    }

    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $category         = Category::findOrFail($id);
            $category->status = Category::STATUS_INACTIVE;
            $category->save();

            return $category;
        });
    }
}
