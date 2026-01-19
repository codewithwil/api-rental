<?php

namespace App\Http\Controllers\API\Report\Kas;

use App\{
    Http\Controllers\Controller,
    Services\Report\Kas\KasService
};

use Illuminate\{
    Http\Request
};

class KasC extends Controller
{
    public function __construct(protected KasService $service) {}

    public function index() { return $this->service->index(); }
    public function show($id) { return $this->service->show($id); }
    public function getTotalMasuk() { return $this->service->getTotalMasuk(); }
    public function getTotalKeluar() { return $this->service->getTotalKeluar(); }
    public function getSaldo() { return $this->service->getSaldo(); }
}
