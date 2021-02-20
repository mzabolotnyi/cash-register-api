<?php


namespace App\Tests\Service\Receipt;

use App\Service\Receipt\VatCalculator;
use PHPUnit\Framework\TestCase;

class VatCalculatorTest extends TestCase
{
    public function testCalculateVat6()
    {
        $amount = 100;
        $vatClass = VatCalculator::VAT_6;
        $amountVat = $this->getVatCalculator()->calculate($amount, $vatClass);

        $this->assertEquals(6, $amountVat);
    }

    public function testCalculateVat21()
    {
        $amount = 100;
        $vatClass = VatCalculator::VAT_21;
        $amountVat = $this->getVatCalculator()->calculate($amount, $vatClass);

        $this->assertEquals(21, $amountVat);
    }

    public function testCalculateIncorrectVatClass()
    {
        $this->expectException(\InvalidArgumentException::class);

        $amount = 100;
        $vatClass = 'Incorrect';
        $this->getVatCalculator()->calculate($amount, $vatClass);
    }

    private function getVatCalculator(): VatCalculator
    {
        return new VatCalculator();
    }
}