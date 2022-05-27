<?php

function interpret_currency($currency)
{
    switch ($currency) {
        case 'AUD/NZD':
        case 'USD':
        case 'CAD':
        {
            return '$';
        }
        case "JPY":
        case 'CNY':
        {
            return '¥';
        }
        case "GBP":
        {
            return '£';
        }
        case "CHF":
        {
            return 'Fr';
        }
        case "ZAR":
        {
            return 'R';
        }
        case "EUR":
        {
            return '€';
        }
        default:
            break;
    }
}

function sum_account($accounts) {
    $sum = 0;
    foreach ($accounts as $account) $sum += $account->balance;
    return $sum;
}