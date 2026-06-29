<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        return view('delivery.index');
    }
    
    public function dashboard()
    {
        return view('delivery.dashboard');
    }
    
    public function deliveryOrders()
    {
        return view('delivery.delivery-orders');
    }
    
    public function deliveryPartners()
    {
        return view('delivery.delivery-partners');
    }
    
    public function deliveryCharges()
    {
        return view('delivery.delivery-charges');
    }
    
    public function riderManagement()
    {
        return view('delivery.rider-management');
    }
    
    public function deliveryReports()
    {
        return view('delivery.delivery-reports');
    }
}
