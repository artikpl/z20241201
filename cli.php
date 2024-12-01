<?php

use PragmaGoTech\Interview\Model\BreakPoints;
use \PragmaGoTech\Interview\Service\LoanCalculatorService;
use \PragmaGoTech\Interview\Model\LoanProposal;
    require_once __DIR__ . '/vendor/autoload.php';
    $calculator = new LoanCalculatorService();
    $res = $calculator->calculate(new LoanProposal(24,2750));