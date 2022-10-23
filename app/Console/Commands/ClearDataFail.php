<?php

namespace App\Console\Commands;

use App\Repositories\DomainRepository;
use App\Repositories\TransactionHistoryRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ClearDataFail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clearfail:data';

    /**
     * @var DomainRepository
     */
    protected $domainRepository;

    /**
     * @var TransactionHistoryRepository
     */
    protected $transactionHistoryRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear data fail';

    /**
     * Create a new command instance.
     * @param DomainRepository $domainRepository
     * @param TransactionHistoryRepository $transactionHistoryRepository
     * @return void
     */
    public function __construct(
        DomainRepository $domainRepository,
        TransactionHistoryRepository $transactionHistoryRepository
    ) {
        parent::__construct();
        $this->domainRepository = $domainRepository;
        $this->transactionHistoryRepository = $transactionHistoryRepository;
    }

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle()
    {
        $data = $this->domainRepository->getAll();
        foreach ($data as $key => $value) {

            if ($value->id == 1 || $value->id == 2) {
                $value->forceDelete();
            }

            if ($value->remote_domain_id == null) {
                $userWebsite = $value->userWebsite;
                if (count($userWebsite) > 0) {
                    $transaction = $userWebsite[0]->transaction;
                    if (count($transaction) > 0) {
                        $transaction[0]->forceDelete();
                    }
                    $userWebsite[0]->forceDelete();
                }
                $value->forceDelete();
            }
        }
    }
}
