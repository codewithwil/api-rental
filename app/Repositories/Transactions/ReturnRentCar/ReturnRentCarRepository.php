<?php

namespace App\Repositories\Transactions\ReturnRentCar;

use App\{
    Models\Transaction\RentCar\RentCar,
    Models\Transactions\ReturnRentCar\ReturnRentCar,
    Traits\DbTransaction,
    Models\Resources\Vehicle\Vehicle
};

use Illuminate\{
    Http\Request,
};

use Carbon\Carbon;

class ReturnRentCarRepository implements ReturnRentCarRepositoryInterface
{
    use DbTransaction;

    public function getAll()
    {
        return ReturnRentCar::with('rentCar.vehicle')->get();
    }

    public function find($id)
    {
        return ReturnRentCar::with(['rentCar.vehicle', 'paymentAmount', 'debt'])->findOrFail($id);
    }

    public function store(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $rentCar    = RentCar::findOrFail($req->rentCar_id);
            $returnDate = Carbon::parse($req->return_date);
            $endDate    = Carbon::parse($rentCar->endDate);
            $lateDays   = 0;
            $fine       = 0;

            if ($returnDate->greaterThan($endDate)) {
                $lateDays = $endDate->diffInDays($returnDate);

                $finePerDay = $rentCar->pricePerDay * ($rentCar->penalty / 100);
                $fine       = $lateDays * $finePerDay;
            }

            $return = ReturnRentCar::create([
                'rentCar_id'     => $req->rentCar_id,
                'return_date'    => $returnDate,
                'return_name'    => $req->return_name,
                'return_address' => $req->return_address,
                'return_phone'   => $req->return_phone,
                'notes'          => $req->notes,
                'late_days'      => $lateDays,
                'fine'           => $fine,
                'type'           => $req->type, 
            ]);

            if ($rentCar->vehicle) {
                $rentCar->vehicle->update(['status' => Vehicle::STATUS_ACTIVE]);
            }

            if ($fine > 0 && $req->filled('type')) {
                $this->handleRelation($return, $req->type, $req->due_date ?? null);
            }

            return $return->load(['paymentAmount', 'debt']);
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $return     = ReturnRentCar::with(['rentCar', 'paymentAmount', 'debt'])->findOrFail($id);
            $rentCar    = $return->rentCar;
            $returnDate = $req->filled('return_date') 
                ? Carbon::parse($req->return_date) 
                : Carbon::parse($return->return_date);

            $endDate  = Carbon::parse($rentCar->endDate);
            $lateDays = 0;
            $fine     = 0;

            if ($returnDate->greaterThan($endDate)) {
                $lateDays = $endDate->diffInDays($returnDate);

                $finePerDay = $rentCar->pricePerDay * ($rentCar->penalty / 100);
                $fine       = $lateDays * $finePerDay;
            }

            $oldType = $return->type;

            $return->update([
                'return_date'    => $returnDate,
                'return_name'    => $req->return_name ?? $return->return_name,
                'return_address' => $req->return_address ?? $return->return_address,
                'return_phone'   => $req->return_phone ?? $return->return_phone,
                'notes'          => $req->notes ?? $return->notes,
                'type'           => $req->type ?? $return->type,
                'late_days'      => $lateDays,
                'fine'           => $fine,
            ]);

            if ($fine <= 0 || !$req->has('type')) {
                return $return->load(['paymentAmount', 'debt']);
            }

            $newType = (int) $req->type;

            if ($oldType !== $newType) {
                $return->paymentAmount()?->delete();
                $return->debt()?->delete();
                $this->handleRelation($return, $newType, $req->due_date ?? null);
            } elseif ($newType == 2 && $req->filled('due_date')) {
                $return->debt?->update([
                    'due_date' => Carbon::parse($req->due_date),
                ]);
            } elseif ($newType == 1 && $return->paymentAmount) {
                $return->paymentAmount->update([
                    'amount' => $fine,
                    'date'   => $returnDate, 
                ]);
            }

            return $return->load(['paymentAmount', 'debt']);
        });
    }

    protected function handleRelation(ReturnRentCar $return, int $type, ?string $dueDate = null): void
    {
        if ($type === 1) {
            $return->paymentAmount()->create([
                'type'   => 1,
                'date'   => $return->return_date, 
                'amount' => $return->fine,
                'status' => 1,
            ]);
        } elseif ($type === 2) {
            $return->debt()->create([
                'amount'   => $return->fine,
                'due_date' => $dueDate 
                    ? Carbon::parse($dueDate) 
                    : Carbon::now()->addDays(7),
                'status'   => 0,
            ]);
        }
    }

    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $return = ReturnRentCar::with(['paymentAmount', 'debt'])->findOrFail($id);

            if ($return->paymentAmount) {
                $return->paymentAmount()->delete();
            }

            if ($return->debt) {
                $return->debt()->delete();
            }

            $return->delete();

            return true;
        });
    }

}
