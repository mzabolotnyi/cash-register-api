<?php

namespace App\Service\Receipt;

class VatCalculator
{
    const VAT_6 = 'VAT_6';
    const VAT_21 = 'VAT_21';
    const VAT_CLASSES = [self::VAT_6, self::VAT_21];

    const VAT_PERCENT = [
        self::VAT_6 => 6,
        self::VAT_21 => 21,
    ];

    public function calculate($amount, string $vatClass, bool $vatIncluded = true): float
    {
        if (!isset(self::VAT_PERCENT[$vatClass])) {
            throw new \InvalidArgumentException('Invalid VAT class');
        }

        $percent = self::VAT_PERCENT[$vatClass];

        if ($vatIncluded) {
            $amountVat = round($amount / (100 + $percent) * $percent, 2);
        } else {
            $amountVat = round($amount * $percent / 100, 2);
        }

        return $amountVat;
    }
}