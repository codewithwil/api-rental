<?php

namespace App\Services\Transactions\Receivable;

use App\{
    Repositories\Transactions\Receivable\ReceivableRepositoryInterface,
    Traits\ApiResponse
};
use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class ReceivableService
{
    use ApiResponse;

    protected ReceivableRepositoryInterface $receivableRepo;

    public function __construct(ReceivableRepositoryInterface $receivableRepo)
    {
        $this->receivableRepo = $receivableRepo;
    }

    public function index(Request $req)
    {
        try {
            $receivables = $this->receivableRepo->getAll($req);

            return $this->successResponse([
                'receivables' => $receivables
            ]);
        } catch (Throwable $e) {
            return $this->errorMessage('Gagal mengambil data pembayaran: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $receivable = $this->receivableRepo->find($id);

            return $this->successResponse([
                'receivable' => $receivable
            ]);
        } catch (Throwable $e) {
            return $this->errorMessage('Gagal mengambil detail pembayaran: ' . $e->getMessage(), 404);
        }
    }

    public function payDebt(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'debt_id'     => 'required|exists:debts,debtId',
            'amount_paid' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $result = $this->receivableRepo->payDebt($req);
            return $this->successResponse($result, 'Pembayaran hutang berhasil disimpan');
        } catch (Throwable $e) {
            return $this->errorMessage('Gagal menyimpan pembayaran hutang: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'amount' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $result = $this->receivableRepo->update($req, $id);
            return $this->successResponse($result, 'Pembayaran hutang berhasil diperbarui');
        } catch (Throwable $e) {
            return $this->errorMessage('Gagal memperbarui pembayaran hutang: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->receivableRepo->delete($id);
            return $this->successResponse($result, 'Pembayaran hutang berhasil dihapus');
        } catch (Throwable $e) {
            return $this->errorMessage('Gagal menghapus pembayaran hutang: ' . $e->getMessage(), 500);
        }
    }
}
