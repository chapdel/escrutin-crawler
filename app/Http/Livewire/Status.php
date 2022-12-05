<?php

namespace App\Http\Livewire;

use App\Models\Page;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Status extends Component
{
    public function render()
    {
        return view('livewire.status', ['pages' => Page::all()]);
    }

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
                'url' => $model->merchant_reference??$model->reference,
                'reference' => $model->reference,
                'status' => $r['status'],
                'isCrawled' => true,
                "payment_status" => $model->status,
                "desc" => $model->description
            ]);
        });
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
            return [
                'status' => 0,
                'callback' => $transaction->callback . '?reference=' . $transaction->reference . '&status='.$transaction->status . '&notchpay_txnref=' . $transaction->merchant_reference,
            ];
        }

    }
}
