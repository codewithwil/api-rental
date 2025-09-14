<?php

namespace App\Repositories\Transactions\ReturnRentCar;

use App\{
    Models\Transaction\RentCar\RentCar,
    Models\Transactions\ReturnRentCar\ReturnRentCar,
    Traits\DbTransaction
};

use Illuminate\{
    Http\Request
};

use Carbon\Carbon;

class ReturnRentCarRepository implements ReturnRentCarRepositoryInterface
{
    use DbTransaction;

    public function getAll()
    {
        return ReturnRentCar::with('rentCar')->get();
    }

    public function find($id)
    {
        return ReturnRentCar::with(['rentCar', 'paymentAmount', 'debt'])->findOrFail($id);
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
                $fine     = $lateDays * $rentCar->penalty;
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

            if ($fine > 0 && $req->filled('type')) {
                $this->handleRelation($return, $req->type, $req->due_date ?? null);
            }

            return $return->load(['payment', 'debt']);
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $return = ReturnRentCar::with(['payment', 'debt'])->findOrFail($id);

            $return->update($req->only([
                'return_date', 'return_name', 'return_address', 'return_phone', 'notes'
            ]));

            if ($return->fine <= 0 || !$req->has('type')) {
                return $return;
            }

            $oldType = $return->type;
            $newType = $req->type;

            if ($oldType !== $newType) {
                $return->payment()?->delete();
                $return->debt()?->delete();

                $this->handleRelation($return, $newType, $req->due_date ?? null);
                $return->update(['type' => $newType]);
            } 
            elseif ($newType == 2 && $req->filled('due_date')) {
                $return->debt?->update([
                    'due_date' => Carbon::parse($req->due_date),
                ]);
            }

            return $return->load(['payment', 'debt']);
        });
    }

    protected function handleRelation(ReturnRentCar $return, int $type, ?string $dueDate = null): void
    {
        if ($type === 1) {
            $return->payment()->create([
                'type'   => 1,
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
            $return = ReturnRentCar::findOrFail($id);
            $return->delete();
            return true;
        });
    }
}
