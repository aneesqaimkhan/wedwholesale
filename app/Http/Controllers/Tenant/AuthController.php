<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('tenant.auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended(url('/dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('tenant.auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'company' => $request->company,
            'address' => $request->address,
            'role' => 'user',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect(url('/dashboard'))->with('success', 'Registration successful!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(url('/login'));
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get statistics
        $stats = [
            'total_customers' => Customer::count(),
            'total_products' => Product::count(),
            'total_suppliers' => Supplier::count(),
            'total_sales_invoices' => SalesInvoice::count(),
            'total_purchases' => Purchase::count(),
            'total_expenses' => Expense::count(),
        ];
        
        // Calculate total sales amount
        $totalSales = SalesInvoiceItem::sum('net_amount');
        $stats['total_sales'] = $totalSales;
        
        // Calculate total purchases amount
        $totalPurchases = PurchaseItem::sum('net_amount');
        $stats['total_purchases'] = $totalPurchases;
        
        // Calculate total expenses amount
        $totalExpenses = Expense::sum('amount');
        $stats['total_expenses_amount'] = $totalExpenses;
        
        // Get monthly sales data (last 12 months)
        $monthlySales = SalesInvoice::select(
            DB::raw('YEAR(invoice_date) as year'),
            DB::raw('MONTH(invoice_date) as month'),
            DB::raw('SUM(si.net_amount) as total')
        )
        ->join('sales_invoice_items as si', 'sales_invoices.id', '=', 'si.invoice_id')
        ->where('invoice_date', '>=', Carbon::now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();
        
        $salesChartData = [
            'labels' => [],
            'data' => []
        ];
        
        foreach ($monthlySales as $sale) {
            $date = Carbon::create($sale->year, $sale->month, 1);
            $salesChartData['labels'][] = $date->format('M Y');
            $salesChartData['data'][] = (float) $sale->total;
        }
        
        // Get monthly purchases data (last 12 months)
        $monthlyPurchases = Purchase::select(
            DB::raw('YEAR(invoice_date) as year'),
            DB::raw('MONTH(invoice_date) as month'),
            DB::raw('SUM(pi.net_amount) as total')
        )
        ->join('purchase_items as pi', 'purchases.id', '=', 'pi.purchase_id')
        ->where('invoice_date', '>=', Carbon::now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();
        
        $purchasesChartData = [
            'labels' => [],
            'data' => []
        ];
        
        foreach ($monthlyPurchases as $purchase) {
            $date = Carbon::create($purchase->year, $purchase->month, 1);
            $purchasesChartData['labels'][] = $date->format('M Y');
            $purchasesChartData['data'][] = (float) $purchase->total;
        }
        
        // Get top customers by sales (last 30 days)
        $topCustomers = SalesInvoice::select(
            'customer_code',
            'customer_name',
            DB::raw('SUM(si.net_amount) as total_sales')
        )
        ->join('sales_invoice_items as si', 'sales_invoices.id', '=', 'si.invoice_id')
        ->where('invoice_date', '>=', Carbon::now()->subDays(30))
        ->groupBy('customer_code', 'customer_name')
        ->orderBy('total_sales', 'desc')
        ->limit(10)
        ->get();
        
        $topCustomersChartData = [
            'labels' => $topCustomers->pluck('customer_name')->toArray(),
            'data' => $topCustomers->pluck('total_sales')->map(function($val) {
                return (float) $val;
            })->toArray()
        ];
        
        // Get top products by sales (last 30 days)
        $topProducts = SalesInvoiceItem::select(
            'product_code',
            'product_name',
            DB::raw('SUM(net_amount) as total_sales'),
            DB::raw('SUM(box + pcs) as total_quantity')
        )
        ->join('sales_invoices', 'sales_invoice_items.invoice_id', '=', 'sales_invoices.id')
        ->where('sales_invoices.invoice_date', '>=', Carbon::now()->subDays(30))
        ->groupBy('product_code', 'product_name')
        ->orderBy('total_sales', 'desc')
        ->limit(10)
        ->get();
        
        $topProductsChartData = [
            'labels' => $topProducts->pluck('product_name')->toArray(),
            'data' => $topProducts->pluck('total_sales')->map(function($val) {
                return (float) $val;
            })->toArray()
        ];
        
        // Get expenses by type
        $expensesByType = Expense::select(
            'expense_types.name',
            DB::raw('SUM(expenses.amount) as total')
        )
        ->join('expense_types', 'expenses.expense_type_id', '=', 'expense_types.id')
        ->groupBy('expense_types.name')
        ->orderBy('total', 'desc')
        ->get();
        
        $expensesChartData = [
            'labels' => $expensesByType->pluck('name')->toArray(),
            'data' => $expensesByType->pluck('total')->map(function($val) {
                return (float) $val;
            })->toArray()
        ];
        
        // Sales vs Purchases comparison (last 6 months)
        $comparisonData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthSales = SalesInvoiceItem::join('sales_invoices', 'sales_invoice_items.invoice_id', '=', 'sales_invoices.id')
                ->whereBetween('sales_invoices.invoice_date', [$monthStart, $monthEnd])
                ->sum('sales_invoice_items.net_amount');
            
            $monthPurchases = PurchaseItem::join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
                ->whereBetween('purchases.invoice_date', [$monthStart, $monthEnd])
                ->sum('purchase_items.net_amount');
            
            $comparisonData['labels'][] = $month->format('M Y');
            $comparisonData['sales'][] = (float) $monthSales;
            $comparisonData['purchases'][] = (float) $monthPurchases;
        }
        
        return view('tenant.dashboard', compact(
            'user',
            'stats',
            'salesChartData',
            'purchasesChartData',
            'topCustomersChartData',
            'topProductsChartData',
            'expensesChartData',
            'comparisonData'
        ));
    }
}
