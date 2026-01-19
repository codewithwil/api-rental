<?php

namespace App\Repositories\Report\Kas;

use App\{
    Models\Transactions\Payment\PaymentAmount\PaymentAmount,
    Repositories\Report\Kas\KasRepositoryInterface,
    Traits\DbTransaction
};


class KasRepository implements KasRepositoryInterface
{
    use DbTransaction;

    public function getAll()
    {
        return PaymentAmount::with(['payable'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'        => $item->payAmountId,
                    'type'      => $item->type == 1 ? 'Masuk' : 'Keluar',
                    'amount'    => $item->amount,
                    'source'    => $item->source, 
                    'reference' => $item->payable, 
                    'date'      => $item->created_at->format('d-m-Y H:i')
                ];
            });
    }


    public function find($id)
    {
        return PaymentAmount::with(['payable'])
            ->findOrFail($id);
    }

    public function getTotalMasuk()
    {
        return PaymentAmount::where('type', 1)
            ->where('status', 1)
            ->sum('amount');
    }

    public function getTotalKeluar()
    {
        return PaymentAmount::where('type', 2)
            ->where('status', 1)
            ->sum('amount');
    }

    public function getSaldo()
    {
        return $this->getTotalMasuk() - $this->getTotalKeluar();
    }

}

