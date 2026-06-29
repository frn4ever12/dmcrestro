<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\NepaliDateHelper;
use Illuminate\Http\Request;

class NepaliDateController extends Controller
{
    public function convertToBs(Request $request)
    {
        $request->validate([
            'ad_date' => 'required|date',
        ]);

        $bsDate = NepaliDateHelper::toBs($request->ad_date);

        return response()->json([
            'ad_date' => $request->ad_date,
            'bs_date' => $bsDate,
        ]);
    }

    public function convertToAd(Request $request)
    {
        $request->validate([
            'bs_year' => 'required|integer|min:2000|max:2100',
            'bs_month' => 'required|integer|min:1|max:12',
            'bs_day' => 'required|integer|min:1|max:32',
        ]);

        $adDate = NepaliDateHelper::toAd(
            $request->bs_year,
            $request->bs_month,
            $request->bs_day
        );

        return response()->json([
            'bs_date' => sprintf('%d-%d-%d', $request->bs_year, $request->bs_month, $request->bs_day),
            'ad_date' => $adDate,
        ]);
    }

    public function getFiscalYear(Request $request)
    {
        $fiscalYear = NepaliDateHelper::fiscalYear($request->date ?? null);

        return response()->json([
            'fiscal_year' => $fiscalYear,
        ]);
    }

    public function getCurrentFiscalYear()
    {
        $fiscalYear = NepaliDateHelper::currentFiscalYear();

        return response()->json([
            'fiscal_year' => $fiscalYear,
        ]);
    }

    public function calculateAge(Request $request)
    {
        $request->validate([
            'bs_year' => 'required|integer',
            'bs_month' => 'required|integer|min:1|max:12',
            'bs_day' => 'required|integer|min:1|max:32',
        ]);

        $age = NepaliDateHelper::age(
            $request->bs_year,
            $request->bs_month,
            $request->bs_day
        );

        return response()->json([
            'birth_date' => sprintf('%d-%d-%d', $request->bs_year, $request->bs_month, $request->bs_day),
            'age' => $age,
        ]);
    }

    public function formatDate(Request $request)
    {
        $request->validate([
            'ad_date' => 'required|date',
            'in_nepali' => 'sometimes|boolean',
        ]);

        $formatted = NepaliDateHelper::format(
            $request->ad_date,
            $request->in_nepali ?? false
        );

        return response()->json([
            'ad_date' => $request->ad_date,
            'formatted' => $formatted,
        ]);
    }

    public function convertDigits(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'to' => 'required|in:nepali,english',
        ]);

        if ($request->to === 'nepali') {
            $result = NepaliDateHelper::toNepaliDigits($request->number);
        } else {
            $result = NepaliDateHelper::toEnglishDigits($request->number);
        }

        return response()->json([
            'input' => $request->number,
            'result' => $result,
        ]);
    }
}
