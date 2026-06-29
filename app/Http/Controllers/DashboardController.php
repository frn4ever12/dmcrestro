<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect super admin to admin dashboard
        if (auth()->user()->user_type === 'super_admin') {
            return redirect('/admin/dashboard');
        }
        
        return view('dashboard.index');
    }
    
    public function billing()
    {
        $tenant = auth()->user()->tenant;
        
        if (!$tenant) {
            return redirect()->route('dashboard')->with('error', 'No tenant found.');
        }
        
        return view('settings.billing', compact('tenant'));
    }
    
    public function renew()
    {
        $tenant = auth()->user()->tenant;
        
        if (!$tenant) {
            return redirect()->route('dashboard')->with('error', 'No tenant found.');
        }
        
        $plans = \App\Models\SubscriptionPlan::active()->ordered()->get();
        
        return view('subscription.renew', compact('tenant', 'plans'));
    }
}
