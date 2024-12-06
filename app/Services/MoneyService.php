<?php

namespace App\Services;

use App\Database\Models\FundRecord;
use App\Database\Models\Item;
use App\Database\Models\User;
use App\Database\Models\WalletRecord;
use LogicException;

class MoneyService
{
    public function doShare(Item $item): void
    {
        $divideBy = $item->Users->count();
        $fundShare = bcmul($item->total_amt, $item->fund_rate, 0);

        // 公積金抽成後
        $totalShare = bcsub($item->total_amt, $fundShare, 0);
        $eachShare = bcdiv($totalShare, $divideBy, 2);

        // 每人分成取整數
        $eachShare = explode('.', $eachShare)[0];

        // 餘數歸公
        $fundShare = bcadd($fundShare, bcsub($totalShare, bcmul($eachShare, $divideBy)), 0);

        if (bccomp($item->total_amt, bcadd($fundShare, bcmul($eachShare, $divideBy))) !== 0) {
            throw new LogicException('Calculation error');
        }

        /** @var FundRecord $fund */
        $fund = FundRecord::latest()->first()->replicate();
        $fund->Fundable()->associate($item);
        $fund->amount = $fundShare;
        $fund->balance = bcadd($fund->balance, $fundShare);
        $fund->save();

        $item->Users->each(static function (User $user) use ($eachShare) {
            /** @var WalletRecord $money */
            $money = $user->Balance?->replicate() ?? new WalletRecord(['balance' => 0]);
            $money->amount = $eachShare;
            $money->balance = bcadd($money->balance, $eachShare);
            $user->Money()->save($money);
        });
    }
}
