<?php

namespace App\Repositories\Resources\Brand;

use App\{
    Repositories\Resources\Brand\BrandRepositoryInterface,
    Models\Resources\Brand\Brand,
    Traits\DbTransaction,
};

use Illuminate\{
    Http\Request,
};

class BrandRepository implements BrandRepositoryInterface
{
    use DbTransaction;
    public function getAll()
    {
        return Brand::where('status', Brand::STATUS_ACTIVE)->get();
    }

    public function find($id)
    {
        return Brand::findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $brand = Brand::create([
                'name'    => $req->input('name'),
            ]);

            return $brand;
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $brand = Brand::findOrFail($id);
            $brand->update($req->only(['name']));
            return $brand;
        });
    }

    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $brand         = Brand::findOrFail($id);
            $brand->status = Brand::STATUS_INACTIVE;
            $brand->save();

            return $brand;
        });
    }
}
