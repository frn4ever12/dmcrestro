<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HRMController extends Controller
{
    public function index()
    {
        return view('hrm.index');
    }
    
    public function employees()
    {
        return view('hrm.employees');
    }
    
    public function departments()
    {
        return view('hrm.departments');
    }
    
    public function designations()
    {
        return view('hrm.designations');
    }
    
    public function attendance()
    {
        return view('hrm.attendance');
    }
    
    public function leave()
    {
        return view('hrm.leave');
    }
    
    public function shifts()
    {
        return view('hrm.shifts');
    }
    
    public function payroll()
    {
        return view('hrm.payroll');
    }
    
    public function salary()
    {
        return view('hrm.salary');
    }
    
    public function advanceSalary()
    {
        return view('hrm.advance-salary');
    }
    
    public function incentives()
    {
        return view('hrm.incentives');
    }
}
