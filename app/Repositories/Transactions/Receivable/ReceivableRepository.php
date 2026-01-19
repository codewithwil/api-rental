<?php

namespace App\Repositories\Transactions\Receivable;

use App\{
    Repositories\Transactions\Receivable\ReceivableRepositoryInterface,
    Traits\DbTransaction,
    Models\Transactions\Debt\Debt,
    Models\Transactions\Payment\PaymentAmount\PaymentAmount
};

use Illuminate\Http\Request;
use Exception;

class ReceivableRepository implements ReceivableRepositoryInterface
{
    use DbTransaction;

    public function getAll(Request $req)
    {
        $query = PaymentAmount::where('payable_type', Debt::class)
            ->where('type', PaymentAmount::TYPE_MASUK)
            ->orderBy('date', 'desc');

        if ($req->has('debt_id')) {
            $query->where('payable_id', $req->debt_id);
        }

        return $query->paginate(10);
    }

    public function find($id)
    {
        return PaymentAmount::where('payable_type', Debt::class)
            ->findOrFail($id);
    }

    public function payDebt(Request $req)
    {
        return $this->runInTransaction(function () use ($req) {
            $debtId     = $req->input('debt_id');
            $amountPaid = (float) $req->input('amount_paid');
            $debt       = Debt::findOrFail($debtId);

            if ($debt->status == Debt::STATUS_LUNAS) {
                throw new Exception("Hutang sudah lunas.");
            }

            $payment = PaymentAmount::create([
                'payable_id'   => $debt->debtId,
                'payable_type' => Debt::class,
                'date'         => now(),
                'type'         => PaymentAmount::TYPE_MASUK,
                'amount'       => $amountPaid,
                'status'       => PaymentAmount::STATUS_ACTIVE,
            ]);

            $debt->amount -= $amountPaid;
            if ($debt->amount <= 0) {
                $debt->amount = 0;
                $debt->status = Debt::STATUS_LUNAS;
            }

            $debt->save();

            return [
                'payment' => $payment,
                'debt'    => $debt,
            ];
        });
    }

    public function update(Request $req, $id)
    {
        return $this->runInTransaction(function () use ($req, $id) {
            $payment = PaymentAmount::findOrFail($id);

            if ($payment->payable_type !== Debt::class) {
                throw new Exception("Data bukan pembayaran hutang.");
            }

            $debt      = Debt::findOrFail($payment->payable_id);
            $oldAmount = $payment->amount;
            $newAmount = (float) $req->input('amount');

            if ($newAmount <= 0) {
                throw new Exception("Nominal pembayaran tidak valid.");
            }

            $difference    = $newAmount - $oldAmount;
            $newDebtAmount = $debt->amount - $difference;

            if ($newDebtAmount < 0) {
                throw new Exception("Nominal melebihi jumlah hutang yang tersisa.");
            }

            $payment->update(['amount' => $newAmount]);

            $debt->amount = $newDebtAmount;
            $debt->status = $newDebtAmount <= 0
                ? Debt::STATUS_LUNAS
                : Debt::STATUS_BELUMDIBAYAR;

            $debt->save();

            return [
                'payment' => $payment,
                'debt'    => $debt,
            ];
        });
    }

    public function delete($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $payment = PaymentAmount::findOrFail($id);

            if ($payment->payable_type !== Debt::class) {
                throw new Exception("Data bukan pembayaran hutang.");
            }

            $debt = Debt::findOrFail($payment->payable_id);

            $debt->amount += $payment->amount;
            $debt->status = Debt::STATUS_BELUMDIBAYAR;
            $debt->save();

            $payment->delete();

            return [
                'debt' => $debt,
            ];
        });
    }
}
