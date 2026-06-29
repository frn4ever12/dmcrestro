<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\FiscalYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $branchId = $user->branch_id;

        $journals = Journal::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->has('fiscal_year_id'), fn($q) => $q->where('fiscal_year_id', $request->fiscal_year_id))
            ->when($request->has('journal_type'), fn($q) => $q->where('journal_type', $request->journal_type))
            ->with(['entries', 'fiscalYear'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'journals' => $journals,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
            'journal_type' => 'required|in:sales,purchase,payment,receipt,adjustment,opening',
            'journal_number' => 'required|string|max:100',
            'journal_date' => 'required|date',
            'reference' => 'nullable|string',
            'description' => 'nullable|string',
            'entries' => 'required|array|min:2',
            'entries.*.account_id' => 'required|exists:chart_of_accounts,id',
            'entries.*.debit_amount' => 'nullable|numeric|min:0',
            'entries.*.credit_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate debit-credit balance
        $totalDebit = collect($request->entries)->sum('debit_amount');
        $totalCredit = collect($request->entries)->sum('credit_amount');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return response()->json([
                'message' => 'Debit and credit amounts must be equal',
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
            ], 422);
        }

        return DB::transaction(function () use ($request) {
            $user = auth()->user();
            $data = array_merge($request->all(), [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'tenant_id' => $user->tenant_id,
                'restaurant_id' => $user->restaurant_id,
                'branch_id' => $user->branch_id,
                'total_debit' => $totalDebit ?? 0,
                'total_credit' => $totalCredit ?? 0,
                'status' => 'posted',
            ]);

            $journal = Journal::create($data);

            // Create journal entries
            foreach ($request->entries as $entry) {
                JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $entry['account_id'],
                    'debit_amount' => $entry['debit_amount'] ?? 0,
                    'credit_amount' => $entry['credit_amount'] ?? 0,
                    'description' => $entry['description'] ?? null,
                ]);

                // Update ledger
                $this->updateLedger(
                    $entry['account_id'],
                    $entry['debit_amount'] ?? 0,
                    $entry['credit_amount'] ?? 0,
                    $journal->journal_date
                );
            }

            return response()->json([
                'message' => 'Journal created successfully',
                'journal' => $journal->load(['entries', 'fiscalYear']),
            ], 201);
        });
    }

    public function show($id)
    {
        $journal = Journal::with(['entries.account', 'fiscalYear'])->findOrFail($id);

        return response()->json([
            'journal' => $journal,
        ]);
    }

    protected function updateLedger($accountId, $debitAmount, $creditAmount, $date)
    {
        $ledger = \App\Models\Ledger::where('account_id', $accountId)
            ->orderBy('date', 'desc')
            ->first();

        $previousBalance = $ledger ? $ledger->balance : 0;
        $newBalance = $previousBalance + $debitAmount - $creditAmount;

        \App\Models\Ledger::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'account_id' => $accountId,
            'date' => $date,
            'debit_amount' => $debitAmount,
            'credit_amount' => $creditAmount,
            'balance' => $newBalance,
        ]);
    }

    public function trialBalance(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant_id;
        $fiscalYearId = $request->fiscal_year_id;

        $fiscalYear = FiscalYear::findOrFail($fiscalYearId);

        $accounts = \App\Models\ChartOfAccounts::where('restaurant_id', $restaurantId)
            ->with(['ledgers' => function($query) use ($fiscalYear) {
                $query->whereBetween('date', [$fiscalYear->start_date, $fiscalYear->end_date]);
            }])
            ->get();

        $trialBalance = $accounts->map(function ($account) {
            $totalDebit = $account->ledgers->sum('debit_amount');
            $totalCredit = $account->ledgers->sum('credit_amount');
            $balance = $totalDebit - $totalCredit;

            return [
                'account_id' => $account->id,
                'code' => $account->code,
                'name' => $account->name,
                'account_type' => $account->account_type,
                'debit' => $balance > 0 ? $balance : 0,
                'credit' => $balance < 0 ? abs($balance) : 0,
            ];
        });

        return response()->json([
            'fiscal_year' => $fiscalYear,
            'trial_balance' => $trialBalance,
        ]);
    }
}
