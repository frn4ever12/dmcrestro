<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }
    
    public function salesReports()
    {
        return view('reports.sales-reports');
    }
    
    public function dailySales()
    {
        return view('reports.daily-sales');
    }
    
    public function monthlySales()
    {
        return view('reports.monthly-sales');
    }
    
    public function yearlySales()
    {
        return view('reports.yearly-sales');
    }
    
    public function itemWiseSales()
    {
        return view('reports.item-wise-sales');
    }
    
    public function categoryWiseSales()
    {
        return view('reports.category-wise-sales');
    }
    
    public function purchaseReports()
    {
        return view('reports.purchase-reports');
    }
    
    public function purchaseSummary()
    {
        return view('reports.purchase-summary');
    }
    
    public function supplierWise()
    {
        return view('reports.supplier-wise');
    }
    
    public function purchaseReturn()
    {
        return view('reports.purchase-return');
    }
    
    public function inventoryReports()
    {
        return view('reports.inventory-reports');
    }
    
    public function stockReport()
    {
        return view('reports.stock-report');
    }
    
    public function stockLedger()
    {
        return view('reports.stock-ledger');
    }
    
    public function lowStock()
    {
        return view('reports.low-stock');
    }
    
    public function expiredItems()
    {
        return view('reports.expired-items');
    }
    
    public function wastageReport()
    {
        return view('reports.wastage-report');
    }
    
    public function financialReports()
    {
        return view('reports.financial-reports');
    }
    
    public function incomeReport()
    {
        return view('reports.income-report');
    }
    
    public function expenseReport()
    {
        return view('reports.expense-report');
    }
    
    public function cashFlow()
    {
        return view('reports.cash-flow');
    }
    
    public function profitReport()
    {
        return view('reports.profit-report');
    }
    
    public function vatReport()
    {
        return view('reports.vat-report');
    }
    
    public function otherReports()
    {
        return view('reports.other-reports');
    }
    
    public function customerReport()
    {
        return view('reports.customer-report');
    }
    
    public function employeeReport()
    {
        return view('reports.employee-report');
    }
    
    public function waiterReport()
    {
        return view('reports.waiter-report');
    }
    
    public function kitchenReport()
    {
        return view('reports.kitchen-report');
    }
    
    public function deliveryReport()
    {
        return view('reports.delivery-report');
    }
    
    public function reservationReport()
    {
        return view('reports.reservation-report');
    }
}
