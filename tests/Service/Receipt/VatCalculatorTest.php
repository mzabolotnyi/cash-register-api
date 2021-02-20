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

        $amountVat = $this->getVatCalculator()->calculate($amount, $vatClass, false);
        $this->assertEquals(6, $amountVat);

        $amountVat = $this->getVatCalculator()->calculate($amount, $vatClass, true);
        $this->assertEquals(5.66, $amountVat);
    }

    public function testCalculateVat21()
    {
        $amount = 100;
        $vatClass = VatCalculator::VAT_21;

        $amountVat = $this->getVatCalculator()->calculate($amount, $vatClass, false);
        $this->assertEquals(21, $amountVat);

        $amountVat = $this->getVatCalculator()->calculate($amount, $vatClass, true);
        $this->assertEquals(17.36, $amountVat);
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