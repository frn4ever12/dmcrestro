<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuManagementController extends Controller
{
    public function index()
    {
        return view('menu-management.index');
    }
    
    public function categories()
    {
        return view('menu-management.categories');
    }
    
    public function items()
    {
        return view('menu-management.items');
    }
    
    public function modifiers()
    {
        return view('menu-management.modifiers');
    }
    
    public function combos()
    {
        return view('menu-management.combos');
    }
    
    public function pricing()
    {
        return view('menu-management.pricing');
    }
    
    public function availability()
    {
        return view('menu-management.availability');
    }
    
    public function qrMenu()
    {
        return view('menu-management.qr-menu');
    }
}
