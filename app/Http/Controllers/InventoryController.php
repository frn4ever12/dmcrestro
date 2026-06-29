<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return view('inventory.index');
    }
    
    public function rawMaterials()
    {
        return view('inventory.raw-materials');
    }
    
    public function products()
    {
        return view('inventory.products');
    }
    
    public function warehouses()
    {
        return view('inventory.warehouses');
    }
    
    public function stockList()
    {
        return view('inventory.stock-list');
    }
    
    public function stockAdjustment()
    {
        return view('inventory.stock-adjustment');
    }
    
    public function stockTransfer()
    {
        return view('inventory.stock-transfer');
    }
    
    public function wastage()
    {
        return view('inventory.wastage');
    }
    
    public function expiredItems()
    {
        return view('inventory.expired-items');
    }
    
    public function stockLedger()
    {
        return view('inventory.stock-ledger');
    }
}
