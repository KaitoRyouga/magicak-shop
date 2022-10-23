<?php

namespace App\Repositories;

use App\Models\TransactionHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionHistoryRepository extends BaseRepository
{
    /**
     * TransactionHistoryRepository constructor.
     * @param TransactionHistory $transactionHistory
     */
    public function __construct(TransactionHistory $transactionHistory)
    {
        $this->model = $transactionHistory;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getInvoices(): LengthAwarePaginator
    {
        return $this->model->with([
            'transactionType',
            'paymentMethod'
        ])
            ->where('created_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * @param $id
     * @return TransactionHistory
     */
    public function getInvoiceById($id)
    {
        return $this->model->with([
            'transactionType',
            'paymentMethod',
            'createdUser',
            'userWebsite' => function ($query) {
                $query->with([
                    'hostingPlan' => function ($query) {
                        $query->with([
                            'prices',
                            'cluster',
                            'dcLocation',
                            'type'
                        ]);
                    },
                    'template' => function ($query) {
                        $query->with([
                            'category'
                        ]);
                    }
                ]);
            },
            'domain'
        ])->where('created_id', auth()->user()->id)
          ->findOrFail($id);
    }

    /**
     * @param $id
     * @return TransactionHistory
     */
    public function getInvoiceByRelationId($id)
    {
        return $this->model->where('relation_id', $id)->first();
    }
}
