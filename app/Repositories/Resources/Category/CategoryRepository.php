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
        return Category::where('status', Category::STATUS_ACTIVE)->get();
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
        $category = Category::findOrFail($id);
        $category->update($req->only(['name', 'type']));
        $category->category->save();
        return $category;
    }

    public function delete($id)
    {
        $category         = Category::findOrFail($id);
        $category->status = Category::STATUS_INACTIVE;
        $category->save();

        return $category;
    }
}
