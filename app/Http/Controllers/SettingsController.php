<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }
    
    public function restaurantSettings()
    {
        return view('settings.restaurant-settings');
    }
    
    public function generalSettings()
    {
        return view('settings.general-settings');
    }
    
    public function branchSettings()
    {
        return view('settings.branch-settings');
    }
    
    public function taxVat()
    {
        return view('settings.tax-vat');
    }
    
    public function invoiceSettings()
    {
        return view('settings.invoice-settings');
    }
    
    public function printerSettings()
    {
        return view('settings.printer-settings');
    }
    
    public function paymentGateway()
    {
        return view('settings.payment-gateway');
    }
    
    public function nepaliDate()
    {
        return view('settings.nepali-date');
    }
    
    public function languageSettings()
    {
        return view('settings.language-settings');
    }
    
    public function userManagement()
    {
        return view('settings.user-management');
    }
    
    public function users()
    {
        return view('settings.users');
    }
    
    public function roles()
    {
        return view('settings.roles');
    }
    
    public function permissions()
    {
        return view('settings.permissions');
    }
    
    public function system()
    {
        return view('settings.system');
    }
    
    public function backup()
    {
        return view('settings.backup');
    }
    
    public function restore()
    {
        return view('settings.restore');
    }
    
    public function activityLogs()
    {
        return view('settings.activity-logs');
    }
    
    public function loginHistory()
    {
        return view('settings.login-history');
    }
    
    public function apiKeys()
    {
        return view('settings.api-keys');
    }
}
