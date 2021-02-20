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

    public function calculate($amount, $vatClass): float
    {
        if (!isset(self::VAT_PERCENT[$vatClass])) {
            throw new \InvalidArgumentException('Invalid VAT class');
        }

        $percent = self::VAT_PERCENT[$vatClass];

        return round($amount * $percent / 100, 2);
    }
}