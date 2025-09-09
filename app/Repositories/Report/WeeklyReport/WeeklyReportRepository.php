<?php

namespace App\Repositories\Report\WeeklyReport;

use App\Models\Report\WeeklyReport\{
    WeeklyReport,
    WeeklyReportDetail
};
use App\Models\Files\Files;
use App\Traits\DbTransaction;
use Illuminate\Http\Request;

class WeeklyReportRepository implements WeeklyReportRepositoryInterface
{
    use DbTransaction;

    public function getAll()
    {
        return WeeklyReport::with(['user','vehicle','weeklyReportDetail.file'])
            ->where('status', '!=', WeeklyReport::STATUS_DELETED)
            ->get();
    }

    public function find($id)
    {
        return WeeklyReport::with(['user','vehicle','weeklyReportDetail.file'])
            ->findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $weeklyReport = WeeklyReport::create([
                'user_id'     => $req->input('user_id'),
                'vehicle_id'  => $req->input('vehicle_id'),
                'report_date' => $req->input('report_date'),
                'note'        => $req->input('note'),
                'status'      => WeeklyReport::STATUS_PENDING,
            ]);

            if ($req->has('details')) {
                foreach ($req->input('details') as $index => $detailData) {
                    $detail = WeeklyReportDetail::create([
                        'weekReport_id' => $weeklyReport->weekReportId,
                        'component'     => $detailData['component'],
                        'position'      => $detailData['position'],
                    ]);

                    if ($req->hasFile("details.$index.file")) {
                        $file     = $req->file("details.$index.file");
                        $path     = $file->store('weeklyReport', 'public');

                        $detail->file()->create([
                            'path'          => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'size'          => $file->getSize(),
                            'mime_type'     => $file->getClientMimeType(),
                        ]);
                    }
                }
            }

            return $weeklyReport->load('weeklyReportDetail.file');
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $weeklyReport = WeeklyReport::findOrFail($id);
            $weeklyReport->update($req->only([
                'user_id','vehicle_id','report_date','note','status'
            ]));

            if ($req->has('details')) {
                foreach ($req->input('details') as $index => $detailData) {
                    $detail = WeeklyReportDetail::updateOrCreate(
                        [
                            'weekReport_id' => $weeklyReport->weekReportId,
                            'component'     => $detailData['component'],
                            'position'      => $detailData['position'],
                        ],
                        []
                    );

                    if ($req->hasFile("details.$index.file")) {
                        if ($detail->file) {
                            $detail->file->delete();
                        }

                        $file     = $req->file("details.$index.file");
                        $path     = $file->store('weeklyReport', 'public');

                        $detail->file()->create([
                            'path'          => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'size'          => $file->getSize(),
                            'mime_type'     => $file->getClientMimeType(),
                        ]);
                    }
                }
            }

            return $weeklyReport->load('weeklyReportDetail.file');
        });
    }

    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $weeklyReport         = WeeklyReport::findOrFail($id);
            $weeklyReport->status = WeeklyReport::STATUS_DELETED;
            $weeklyReport->save();

            return $weeklyReport;
        });
    }
}

