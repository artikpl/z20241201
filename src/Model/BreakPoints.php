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

    public function getFee(float $loanAmount) : float
    {
        foreach($this->levels as $limit => $fee){
            if($loanAmount<=$limit){
                return $fee;
            }
        }
        throw new LoanAmountLimitExceedException();
    }
}