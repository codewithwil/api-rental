<?php

namespace App\Repositories\Transactions\Debts;

use App\{
    Repositories\Transactions\Debts\DebtsRepositoryInterface,
    Traits\DbTransaction,
    Models\Transaction\RentCar\RentCar
};
use Illuminate\Http\Request;

class DebtsRepository implements DebtsRepositoryInterface
{
    use DbTransaction;

    public function getAll(Request $req)
    {
        $rentCars = RentCar::with(['debts', 'vehicle'])
            ->whereHas('debts') 
            ->orderByDesc('created_at')
            ->paginate(10);

        $today = now();

        $rentCars = $rentCars->through(function ($rentCar) use ($today) {
            $totalDebt = $rentCar->debts->sum('amount');
            $totalPenalty = 0;

            $debts = $rentCar->debts->map(function ($debt) use ($rentCar, $today, &$totalPenalty) {
                $dueDate = \Carbon\Carbon::parse($debt->due_date);
                $daysLate = $today->gt($dueDate) ? $today->diffInDays($dueDate) : 0;

                $penaltyRate = ($rentCar->penalty ?? 0) / 100;
                $penaltyAmount = $debt->amount * $penaltyRate * $daysLate;

                $totalPenalty += $penaltyAmount;

                return [
                    'debtId'        => $debt->debtId,
                    'amount'        => (float) $debt->amount,
                    'due_date'      => $debt->due_date,
                    'days_late'     => $daysLate,
                    'penalty_rate'  => $rentCar->penalty . '%',
                    'penalty_total' => round($penaltyAmount, 2),
                    'status'        => $debt->status,
                ];
            });

            $totalToPay = $totalDebt + $totalPenalty;

            return [
                'rentCarId'     => $rentCar->rentCarId,
                'renter_name'   => $rentCar->renter_name,
                'vehicle_name'  => $rentCar->vehicle->name ?? null,
                'startDate'     => $rentCar->startDate,
                'endDate'       => $rentCar->endDate,
                'total_debt'    => round($totalDebt, 2),
                'total_penalty' => round($totalPenalty, 2),
                'total_to_pay'  => round($totalToPay, 2),
                'debts'         => $debts,
            ];
        });

        return $rentCars;
    }
}
