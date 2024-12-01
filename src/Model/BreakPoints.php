<?php

namespace PragmaGoTech\Interview\Model;

use PragmaGoTech\Interview\Exception\InvalidBreakPointKeyException;
use PragmaGoTech\Interview\Exception\InvalidBreakPointValueException;
use PragmaGoTech\Interview\Exception\LoanAmountLimitExceedException;

class BreakPoints
{
    private array $levels;
    public function __construct(array $data)
    {
        foreach($data as $key => $value){
            if($key <= 0 || (!is_int($key) && !is_double($key))){
                throw new InvalidBreakPointKeyException();
            }
            if($value < 0 || (!is_int($value) && !is_double($value))){
                throw new InvalidBreakPointValueException();
            }
        }
        ksort($data);
        $this->levels = $data;
    }

    private function interpolate(float $loanAmount,float $lowerLimit,float $lowerFee,float $upperLimit,float $upperFee){
        $x = $loanAmount;
        $y1 = $upperFee;
        $y0 = $lowerFee;
        $x1 = $upperLimit;
        $x0 = $lowerLimit;
        $fee = $y0 + (($y1-$y0)/($x1-$x0))*($x-$x0);
        return ceil($fee/5)*5;
    }

    public function getFee(float $loanAmount) : float
    {
        $lowerLimit = null;
        $lowerFee = null;
        foreach($this->levels as $upperLimit => $upperFee){
            if($upperLimit<$loanAmount) {
                $lowerLimit = $upperLimit;
                $lowerFee = $upperFee;
            }else if(!isset($lowerFee)) {
                return $upperFee;
            }else{
                return $this->interpolate($loanAmount,$lowerLimit,$lowerFee,$upperLimit,$upperFee);
            }
        }
        throw new LoanAmountLimitExceedException();
    }
}