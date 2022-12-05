<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CrawlEscrutin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'escrutin:crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Transaction::whereStatus('complete')->whereSandbox(false)->whereNotNull('callback')->get()->each(function ($model) {
            $r = $this->visit($model);
            Page::create([
                'url' => $model->merchant_reference,
                'reference' => $model->reference,
                'status' => $r['status'],
                'isCrawled' => true,
                "payment_status" => $model->status,
                "desc" => $model->description
            ]);
        });
        return Command::SUCCESS;
    }

    public function visit($transaction)
    {

        try {
            $r = Http::withOptions([
                'verify' => false
            ])->get($transaction->callback . '?reference=' . $transaction->reference . '&status='.$transaction->status . '&notchpay_txnref=' . $transaction->merchant_reference);

            return [
                'status' => $r->status(),
                'callback' => $transaction->callback . '?reference=' . $transaction->reference . '&status='.$transaction->status . '&notchpay_txnref=' . $transaction->merchant_reference,
            ];
        } catch (\Throwable $th) {
            dd($th);
            return [
                'status' => $r->status(),
                'callback' => $transaction->callback . '?reference=' . $transaction->reference . '&status='.$transaction->status . '&notchpay_txnref=' . $transaction->merchant_reference,
            ];
        }

    }
}
