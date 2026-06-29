<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        return view('support.index');
    }
    
    public function helpCenter()
    {
        return view('support.help-center');
    }
    
    public function supportTickets()
    {
        return view('support.support-tickets');
    }
    
    public function documentation()
    {
        return view('support.documentation');
    }
    
    public function contactAdmin()
    {
        return view('support.contact-admin');
    }
}
