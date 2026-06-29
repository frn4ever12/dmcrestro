<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TrialAccountController extends Controller
{
    public function index()
    {
        $trialTenants = Tenant::where('status', 'trial')
            ->with('subscriptionPlan')
            ->latest()
            ->paginate(15);
        return view('admin.trial-accounts.index', compact('trialTenants'));
    }
}
