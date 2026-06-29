<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index()
    {
        return view('customers.index');
    }
    
    public function customerList()
    {
        return view('customers.customer-list');
    }
    
    public function membership()
    {
        return view('customers.membership');
    }
    
    public function loyaltyPoints()
    {
        return view('customers.loyalty-points');
    }
    
    public function wallet()
    {
        return view('customers.wallet');
    }
    
    public function feedback()
    {
        return view('customers.feedback');
    }
    
    public function reviews()
    {
        return view('customers.reviews');
    }
}
