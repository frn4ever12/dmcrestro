<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index()
    {
        return view('accounting.index');
    }
    
    public function accounts()
    {
        return view('accounting.accounts');
    }
    
    public function chartOfAccounts()
    {
        return view('accounting.chart-of-accounts');
    }
    
    public function ledger()
    {
        return view('accounting.ledger');
    }
    
    public function cashBook()
    {
        return view('accounting.cash-book');
    }
    
    public function bankBook()
    {
        return view('accounting.bank-book');
    }
    
    public function pettyCash()
    {
        return view('accounting.petty-cash');
    }
    
    public function transactions()
    {
        return view('accounting.transactions');
    }
    
    public function income()
    {
        return view('accounting.income');
    }
    
    public function expenses()
    {
        return view('accounting.expenses');
    }
    
    public function bankTransactions()
    {
        return view('accounting.bank-transactions');
    }
    
    public function bankReconciliation()
    {
        return view('accounting.bank-reconciliation');
    }
    
    public function financialReports()
    {
        return view('accounting.financial-reports');
    }
    
    public function trialBalance()
    {
        return view('accounting.trial-balance');
    }
    
    public function profitLoss()
    {
        return view('accounting.profit-loss');
    }
    
    public function balanceSheet()
    {
        return view('accounting.balance-sheet');
    }
    
    public function vatReport()
    {
        return view('accounting.vat-report');
    }
    
    public function panReport()
    {
        return view('accounting.pan-report');
    }
    
    public function taxReport()
    {
        return view('accounting.tax-report');
    }
}
