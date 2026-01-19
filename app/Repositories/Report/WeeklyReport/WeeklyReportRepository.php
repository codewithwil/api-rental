<?php

namespace App\Repositories\Report\WeeklyReport;

use App\Models\Report\WeeklyReport\{
    WeeklyReport,
    WeeklyReportDetail
};

use App\Traits\DbTransaction;
use Illuminate\{
    Http\Request,
    Support\Facades\Storage
};

class WeeklyReportRepository implements WeeklyReportRepositoryInterface
{
    use DbTransaction;

    public function getAll()
    {
        $weeklyReports = WeeklyReport::with([
            'user.employee', 
            'user.admin', 
            'vehicle', 
            'weeklyReportDetail.file'
        ])
        ->where('status', '!=', WeeklyReport::STATUS_DELETED)
        ->orderByDesc('report_date')
        ->get();

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Success',
            'data' => [
                'weeklyReports' => $weeklyReports->map(function($report){
                    return [
                        'weekReportId' => $report->weekReportId,
                        'user_id' => $report->user_id,
                        'user_name' => $report->userInfo(), 
                        'vehicle' => $report->vehicle,
                        'report_date' => $report->report_date,
                        'note' => $report->note,
                        'status' => $report->status,
                        'weekly_report_detail' => $report->weeklyReportDetail,
                    ];
                }),
            ]
        ]);
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

            $sentDetailIds = collect($req->input('details'))
                ->pluck('id') 
                ->filter()
                ->toArray();

            WeeklyReportDetail::where('weekReport_id', $weeklyReport->weekReportId)
                ->whereNotIn('weekReportDetId', $sentDetailIds)
                ->each(function($detail) {
                    if ($detail->file) {
                        Storage::disk('public')->delete($detail->file->path);
                        $detail->file->delete();
                    }
                    $detail->delete();
                });

            foreach ($req->input('details') as $index => $detailData) {
                if (!empty($detailData['delete']) && $detailData['delete'] == 1) {
                    if ($detail->file) {
                        Storage::disk('public')->delete($detail->file->path);
                        $detail->file->delete();
                    }
                    continue; 
                }

                $detail = WeeklyReportDetail::updateOrCreate(
                    [
                        'weekReportDetId' => $detailData['id'] ?? null, 
                    ],
                    [
                        'weekReport_id'   => $weeklyReport->weekReportId,
                        'component'       => $detailData['component'],
                        'position'        => $detailData['position'],
                    ]
                );

                if ($req->hasFile("details.$index.file")) {
                    if ($detail->file) {
                        Storage::disk('public')->delete($detail->file->path);
                        $detail->file->delete();
                    }

                    $file = $req->file("details.$index.file");
                    $path = $file->store('weeklyReport', 'public');

                    $detail->file()->create([
                        'path'          => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'size'          => $file->getSize(),
                        'mime_type'     => $file->getClientMimeType(),
                    ]);
                }
            }

            return $weeklyReport->load('weeklyReportDetail.file');
        });
    }

    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $weeklyReport = WeeklyReport::with('weeklyReportDetail.file')->findOrFail($id);
            foreach ($weeklyReport->weeklyReportDetail as $detail) {
                if ($detail->file) {
                    Storage::disk('public')->delete($detail->file->path);
                    $detail->file->delete();
                }
            }

            $weeklyReport->status = WeeklyReport::STATUS_DELETED;
            $weeklyReport->save();

            return $weeklyReport;
        });
    }

}

