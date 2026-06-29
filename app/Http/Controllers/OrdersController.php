<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }
    
    public function newOrders()
    {
        return view('orders.new');
    }
    
    public function dineIn()
    {
        return view('orders.dine-in');
    }
    
    public function takeaway()
    {
        return view('orders.takeaway');
    }
    
    public function delivery()
    {
        return view('orders.delivery');
    }
    
    public function online()
    {
        return view('orders.online');
    }
    
    public function scheduled()
    {
        return view('orders.scheduled');
    }
    
    public function history()
    {
        return view('orders.history');
    }
    
    public function cancelled()
    {
        return view('orders.cancelled');
    }
    
    public function returned()
    {
        return view('orders.returned');
    }
}
