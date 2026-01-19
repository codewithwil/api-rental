<?php

namespace App\Repositories\Report\Kas;

use Illuminate\Http\Request;

interface KasRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function getTotalMasuk();
    public function getTotalKeluar();
    public function getSaldo();
}
