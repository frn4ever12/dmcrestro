<?php

namespace App\Services;

class NepaliDateService
{
    private array $nepaliMonths = [
        1 => 'Baishakh',
        2 => 'Jestha',
        3 => 'Ashadh',
        4 => 'Shrawan',
        5 => 'Bhadra',
        6 => 'Ashwin',
        7 => 'Kartik',
        8 => 'Mangsir',
        9 => 'Poush',
        10 => 'Magh',
        11 => 'Falgun',
        12 => 'Chaitra',
    ];

    private array $nepaliMonthsNp = [
        1 => 'बैशाख',
        2 => 'जेष्ठ',
        3 => 'आषाढ',
        4 => 'श्रावण',
        5 => 'भाद्र',
        6 => 'असोज',
        7 => 'कार्तिक',
        8 => 'मंसिर',
        9 => 'पौष',
        10 => 'माघ',
        11 => 'फाल्गुन',
        12 => 'चैत्र',
    ];

    private array $nepaliDays = [
        0 => 'Aaitabar',
        1 => 'Sombar',
        2 => 'Manglbar',
        3 => 'Budhabar',
        4 => 'Bihibar',
        5 => 'Shukrbar',
        6 => 'Sanibar',
    ];

    private array $nepaliDaysNp = [
        0 => 'आइतबार',
        1 => 'सोमबार',
        2 => 'मंगलबार',
        3 => 'बुधबार',
        4 => 'बिहिबार',
        5 => 'शुक्रबार',
        6 => 'शनिबार',
    ];

    // BS to AD conversion reference data (simplified)
    // In production, use a proper library like nepali-date
    private array $bsToAdReference = [
        // Reference points for conversion
        // This is a simplified version - use proper library for production
    ];

    /**
     * Convert AD (Gregorian) to BS (Bikram Sambat)
     */
    public function adToBs(string $adDate): array
    {
        // Simplified conversion - in production use nepali-date package
        $date = \Carbon\Carbon::parse($adDate);
        
        // Approximate conversion (BS is approximately 56 years 8 months ahead)
        $bsYear = $date->year + 56;
        $bsMonth = $date->month + 8;
        $bsDay = $date->day + 15;

        if ($bsMonth > 12) {
            $bsYear += 1;
            $bsMonth -= 12;
        }

        // Adjust for month days (simplified)
        $monthDays = $this->getBsMonthDays($bsYear, $bsMonth);
        if ($bsDay > $monthDays) {
            $bsDay -= $monthDays;
            $bsMonth += 1;
            if ($bsMonth > 12) {
                $bsYear += 1;
                $bsMonth = 1;
            }
        }

        return [
            'year' => $bsYear,
            'month' => $bsMonth,
            'day' => $bsDay,
            'month_name' => $this->nepaliMonths[$bsMonth],
            'month_name_np' => $this->nepaliMonthsNp[$bsMonth],
            'day_name' => $this->nepaliDays[$date->dayOfWeek],
            'day_name_np' => $this->nepaliDaysNp[$date->dayOfWeek],
            'formatted' => sprintf('%d %s %d', $bsDay, $this->nepaliMonths[$bsMonth], $bsYear),
            'formatted_np' => sprintf('%d %s %d', $this->convertToNepaliDigits($bsDay), $this->nepaliMonthsNp[$bsMonth], $this->convertToNepaliDigits($bsYear)),
        ];
    }

    /**
     * Convert BS (Bikram Sambat) to AD (Gregorian)
     */
    public function bsToAd(int $bsYear, int $bsMonth, int $bsDay): string
    {
        // Simplified conversion - in production use nepali-date package
        $adYear = $bsYear - 56;
        $adMonth = $bsMonth - 8;
        $adDay = $bsDay - 15;

        if ($adMonth <= 0) {
            $adYear -= 1;
            $adMonth += 12;
        }

        if ($adDay <= 0) {
            $adMonth -= 1;
            if ($adMonth <= 0) {
                $adYear -= 1;
                $adMonth += 12;
            }
            $adDay += 30; // Approximate
        }

        return sprintf('%04d-%02d-%02d', $adYear, $adMonth, $adDay);
    }

    /**
     * Get Nepali fiscal year
     * Nepali fiscal year starts from mid-July (Shrawan)
     */
    public function getFiscalYear(string $adDate = null): array
    {
        $date = $adDate ? \Carbon\Carbon::parse($adDate) : now();
        
        // Fiscal year starts from mid-July (around July 16)
        $fiscalYearStart = \Carbon\Carbon::create($date->year, 7, 16);
        
        if ($date < $fiscalYearStart) {
            $startYear = $date->year - 1;
            $endYear = $date->year;
        } else {
            $startYear = $date->year;
            $endYear = $date->year + 1;
        }

        // Convert to BS
        $bsStart = $this->adToBs($startYear . '-07-16');
        $bsEnd = $this->adToBs($endYear . '-07-15');

        return [
            'ad_start' => sprintf('%04d-07-16', $startYear),
            'ad_end' => sprintf('%04d-07-15', $endYear),
            'bs_start_year' => $bsStart['year'],
            'bs_start_month' => $bsStart['month'],
            'bs_end_year' => $bsEnd['year'],
            'bs_end_month' => $bsEnd['month'],
            'name' => sprintf('%d/%d', $bsStart['year'], $bsEnd['year']),
            'name_np' => sprintf('%s/%s', $this->convertToNepaliDigits($bsStart['year']), $this->convertToNepaliDigits($bsEnd['year'])),
        ];
    }

    /**
     * Get current fiscal year
     */
    public function getCurrentFiscalYear(): array
    {
        return $this->getFiscalYear();
    }

    /**
     * Calculate age from BS birth date
     */
    public function calculateAge(int $bsYear, int $bsMonth, int $bsDay): array
    {
        $birthDate = $this->bsToAd($bsYear, $bsMonth, $bsDay);
        $birth = \Carbon\Carbon::parse($birthDate);
        $now = now();

        $age = $birth->diff($now);

        return [
            'years' => $age->y,
            'months' => $age->m,
            'days' => $age->d,
            'total_days' => $birth->diffInDays($now),
        ];
    }

    /**
     * Format date in Nepali format
     */
    public function formatNepaliDate(string $adDate, bool $inNepali = false): string
    {
        $bsDate = $this->adToBs($adDate);
        
        if ($inNepali) {
            return $bsDate['formatted_np'];
        }
        
        return $bsDate['formatted'];
    }

    /**
     * Get BS month days (simplified)
     */
    private function getBsMonthDays(int $year, int $month): int
    {
        // Simplified month days - actual BS calendar varies
        $days = [
            1 => 31, 2 => 31, 3 => 32, 4 => 32, 5 => 31, 6 => 31,
            7 => 30, 8 => 30, 9 => 29, 10 => 30, 11 => 30, 12 => 31
        ];

        // Adjust for leap year in BS
        if ($month === 2 && $this->isBsLeapYear($year)) {
            return 32;
        }

        return $days[$month] ?? 30;
    }

    /**
     * Check if BS year is leap year (simplified)
     */
    private function isBsLeapYear(int $year): bool
    {
        // Simplified logic - actual BS leap year calculation is complex
        return $year % 4 === 0;
    }

    /**
     * Convert English digits to Nepali digits
     */
    private function convertToNepaliDigits(int $number): string
    {
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $nepali = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
        
        return str_replace($english, $nepali, (string) $number);
    }

    /**
     * Convert Nepali digits to English digits
     */
    public function convertToEnglishDigits(string $nepaliNumber): string
    {
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $nepali = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
        
        return str_replace($nepali, $english, $nepaliNumber);
    }

    /**
     * Get Nepali date in array format
     */
    public function getNepaliDateArray(string $adDate = null): array
    {
        $date = $adDate ? \Carbon\Carbon::parse($adDate) : now();
        return $this->adToBs($date->format('Y-m-d'));
    }
}
