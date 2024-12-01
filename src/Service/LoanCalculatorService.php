<?php

namespace PragmaGoTech\Interview\Service;

use PragmaGoTech\Interview\Exception\InvalidLoanAmountException;
use PragmaGoTech\Interview\Exception\InvalidLoanPeriodException;
use PragmaGoTech\Interview\FeeCalculator;
use PragmaGoTech\Interview\Model\BreakPoints;
use PragmaGoTech\Interview\Model\LoanProposal;

class LoanCalculatorService implements FeeCalculator
{
    /**
     * @return BreakPoints[]
     */
    private array $breakPoints = [];

    public function setPeriodBreakPoints(int $period,BreakPoints $breakPoints): void
    {
        $this->breakPoints[$period] = $breakPoints;
    }

    function __construct()
    {
        $breakPoints = new BreakPoints([
            1000 => 50,
            2000 => 90,
            3000 => 90,
            4000 => 115,
            5000 => 100,
            6000 => 120,
            7000 => 140,
            8000 => 160,
            9000 => 180,
            10000 => 200,
            11000 => 220,
            12000 => 240,
            13000 => 260,
            14000 => 280,
            15000 => 300,
            16000 => 320,
            17000 => 340,
            18000 => 360,
            19000 => 380,
            20000 => 400
        ]);
        $this->setPeriodBreakPoints(12,$breakPoints);

        $breakPoints = new BreakPoints([
            1000 => 70,
            2000 => 100,
            3000 => 120,
            4000 => 160,
            5000 => 200,
            6000 => 240,
            7000 => 280,
            8000 => 320,
            9000 => 360,
            10000 => 400,
            11000 => 440,
            12000 => 480,
            13000 => 520,
            14000 => 560,
            15000 => 600,
            16000 => 640,
            17000 => 680,
            70 => 1000,
            18000 => 720,
            19000 => 760,
            20000 => 800
        ]);
        $this->setPeriodBreakPoints(24,$breakPoints);
    }

    private function getPeriodBreakPoints(int $period) : BreakPoints
    {
        if(!isset($this->breakPoints[$period])){
            throw new InvalidLoanPeriodException();
        }
        return $this->breakPoints[$period];
    }

    public function calculate(LoanProposal $application): float
    {
        if($application->amount()<=0){
            throw new InvalidLoanAmountException();
        }
        $breakPoints = $this->getPeriodBreakPoints($application->term());
        return $breakPoints->getFee($application->amount());
    }
}