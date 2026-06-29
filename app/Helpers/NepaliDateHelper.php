<?php

namespace App\Helpers;

use App\Services\NepaliDateService;

class NepaliDateHelper
{
    protected static $nepaliDateService;

    public static function service(): NepaliDateService
    {
        if (!self::$nepaliDateService) {
            self::$nepaliDateService = app(NepaliDateService::class);
        }
        return self::$nepaliDateService;
    }

    public static function toBs(string $adDate): array
    {
        return self::service()->adToBs($adDate);
    }

    public static function toAd(int $bsYear, int $bsMonth, int $bsDay): string
    {
        return self::service()->bsToAd($bsYear, $bsMonth, $bsDay);
    }

    public static function fiscalYear(string $adDate = null): array
    {
        return self::service()->getFiscalYear($adDate);
    }

    public static function currentFiscalYear(): array
    {
        return self::service()->getCurrentFiscalYear();
    }

    public static function format(string $adDate, bool $inNepali = false): string
    {
        return self::service()->formatNepaliDate($adDate, $inNepali);
    }

    public static function age(int $bsYear, int $bsMonth, int $bsDay): array
    {
        return self::service()->calculateAge($bsYear, $bsMonth, $bsDay);
    }

    public static function toNepaliDigits(int $number): string
    {
        return self::service()->convertToNepaliDigits($number);
    }

    public static function toEnglishDigits(string $nepaliNumber): string
    {
        return self::service()->convertToEnglishDigits($nepaliNumber);
    }
}
