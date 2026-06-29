<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        return view('sales.index');
    }
    
    public function dailySales()
    {
        return view('sales.daily-sales');
    }
    
    public function monthlySales()
    {
        return view('sales.monthly-sales');
    }
    
    public function voucher()
    {
        return view('sales.voucher');
    }
    
    public function journalVoucher()
    {
        return view('sales.journal-voucher');
    }
    
    public function paymentVoucher()
    {
        return view('sales.payment-voucher');
    }
    
    public function receiptVoucher()
    {
        return view('sales.receipt-voucher');
    }
    
    public function contraVoucher()
    {
        return view('sales.contra-voucher');
    }
}
