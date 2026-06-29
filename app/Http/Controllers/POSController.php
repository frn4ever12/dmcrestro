<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }
    
    public function newBilling()
    {
        return view('pos.new-billing');
    }
    
    public function quickBilling()
    {
        return view('pos.quick-billing');
    }
    
    public function holdBills()
    {
        return view('pos.hold-bills');
    }
    
    public function resumeBills()
    {
        return view('pos.resume-bills');
    }
    
    public function splitBills()
    {
        return view('pos.split-bills');
    }
    
    public function mergeBills()
    {
        return view('pos.merge-bills');
    }
    
    public function reprintBill()
    {
        return view('pos.reprint-bill');
    }
    
    public function refundBills()
    {
        return view('pos.refund-bills');
    }
    
    public function voidBills()
    {
        return view('pos.void-bills');
    }
    
    public function dailyClosing()
    {
        return view('pos.daily-closing');
    }
    
    public function fullScreen()
    {
        $categories = \App\Models\MenuCategory::where('tenant_id', session('tenant_id'))
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        $menuItems = \App\Models\MenuItem::where('tenant_id', session('tenant_id'))
            ->where('is_available', true)
            ->where('is_active', true)
            ->with('category')
            ->orderBy('sort_order')
            ->get();
            
        $restaurant = \App\Models\Restaurant::where('tenant_id', session('tenant_id'))->first();
        
        return view('pos.full-screen', compact('categories', 'menuItems', 'restaurant'));
    }
}
