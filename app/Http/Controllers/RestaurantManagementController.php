<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantManagementController extends Controller
{
    public function index()
    {
        return view('restaurant-management.index');
    }
    
    public function profile()
    {
        return view('restaurant-management.profile');
    }
    
    public function branches()
    {
        return view('restaurant-management.branches');
    }
    
    public function tables()
    {
        return view('restaurant-management.tables');
    }
    
    public function waiters()
    {
        return view('restaurant-management.waiters');
    }
    
    public function kitchen()
    {
        return view('restaurant-management.kitchen');
    }
    
    public function printers()
    {
        return view('restaurant-management.printers');
    }
    
    public function integrations()
    {
        return view('restaurant-management.integrations');
    }
}
