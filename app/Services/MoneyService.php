<?php

namespace App\Services;

use App\Database\Models\FundRecord;
use App\Database\Models\Item;
use App\Database\Models\User;
use App\Database\Models\WalletRecord;
use App\Settings\Defaults;
use LogicException;

readonly class MoneyService
{
    public function doShare(Item $item): void
    {
        info('doShare #'.$item->id, ['iid' => $item->id, 'total' => $item->total_amt, 'fund_rate' => $item->fund_rate]);
        $divideBy = $item->Users->count();

        // 公積金抽成無條件進位
        $fundShare = bcceil(bcmul($item->total_amt, $item->fund_rate, 2));
        info('doShare #'.$item->id, ['iid' => $item->id, 'fund' => $fundShare]);

        // total - 公積金抽成
        $totalShare = bcsub($item->total_amt, $fundShare, 0);
        info('doShare #'.$item->id, ['iid' => $item->id, 'totalShare' => $totalShare]);

        // 每人分成取整數
        $eachShare = bcfloor(bcdiv($totalShare, $divideBy, 2));
        info('doShare #'.$item->id, ['iid' => $item->id, 'each' => $eachShare, 'divideBy' => $divideBy]);

        // 分成餘數
        $modulus = bcsub($totalShare, bcmul($eachShare, $divideBy));
        info('doShare #'.$item->id, ['iid' => $item->id, 'mod' => $modulus]);

        // 餘數歸公
        $fundShare = bcadd($fundShare, $modulus, 0);
        info('doShare #'.$item->id, ['iid' => $item->id, 'fund+mod' => $fundShare]);

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

    public function getPostedAmt(?string $totalAmt = null, ?string $taxRate = null): ?string
    {
        if ($totalAmt === null) {
            return null;
        }

        $taxRate ??= app(Defaults::class)->tax_rate;

        return bcmul($totalAmt, bcsub('1.00', $taxRate, 4));
    }
}