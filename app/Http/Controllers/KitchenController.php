<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        return view('kitchen.index');
    }
    
    public function dashboard()
    {
        return view('kitchen.dashboard');
    }
    
    public function pending()
    {
        return view('kitchen.pending');
    }
    
    public function preparing()
    {
        return view('kitchen.preparing');
    }
    
    public function ready()
    {
        return view('kitchen.ready');
    }
    
    public function served()
    {
        return view('kitchen.served');
    }
    
    public function display()
    {
        return view('kitchen.display');
    }
    
    public function tickets()
    {
        return view('kitchen.tickets');
    }
    
    public function kot()
    {
        return view('kitchen.kot');
    }
    
    public function kds()
    {
        return view('kitchen.kds');
    }
    
    public function updateOrderStatus(Request $request, $orderId)
    {
        $status = $request->input('status');
        
        // Update order status in database
        // This would typically update the Order model
        
        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully'
        ]);
    }
}
