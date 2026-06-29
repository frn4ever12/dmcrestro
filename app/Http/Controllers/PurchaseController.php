<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('purchase.index');
    }
    
    public function suppliers()
    {
        return view('purchase.suppliers');
    }
    
    public function purchaseOrders()
    {
        return view('purchase.purchase-orders');
    }
    
    public function purchaseReceive()
    {
        return view('purchase.purchase-receive');
    }
    
    public function purchaseInvoice()
    {
        return view('purchase.purchase-invoice');
    }
    
    public function purchaseReturn()
    {
        return view('purchase.purchase-return');
    }
    
    public function supplierPayments()
    {
        return view('purchase.supplier-payments');
    }
    
    public function duePayments()
    {
        return view('purchase.due-payments');
    }
}
