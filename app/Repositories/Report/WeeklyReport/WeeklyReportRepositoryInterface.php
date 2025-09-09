<?php

namespace App\Repositories\Report\WeeklyReport;

use Illuminate\Http\Request;

interface WeeklyReportRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function store(Request $req);
    public function update(Request $req, $id);
    public function delete($id);
}
