<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CashOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CashOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CashOut::with('createdBy');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('source', 'LIKE', "%{$search}%")
                  ->orWhere('reciever', 'LIKE', "%{$search}%")
                  ->orWhere('given_by', 'LIKE', "%{$search}%")
                  ->orWhere('payout_method', 'LIKE', "%{$search}%")
                  ->orWhere('note', 'LIKE', "%{$search}%")
                  ->orWhere('amount', 'LIKE', "%{$search}%")
                  ->orWhereHas('createdBy', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Payout method filter
        if ($request->has('payout_method') && !empty($request->payout_method)) {
            $query->where('payout_method', $request->payout_method);
        }

        // Order by latest first
        $cashOuts = $query->orderBy('created_at', 'desc')->paginate(10);

        // Calculate total amount for current filtered results
        $totalAmount = $query->sum('amount');

        // Get unique payout methods for filter dropdown
        $payoutMethods = CashOut::distinct()->pluck('payout_method')->filter()->sort();

        return view('admin.cashout.index', compact('cashOuts', 'totalAmount', 'payoutMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get unique payout methods for dropdown
        $payoutMethods = CashOut::distinct()->pluck('payout_method')->filter()->sort();
        
        return view('admin.cashout.create', compact('payoutMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'reciever' => 'required|string|max:255',
            'given_by' => 'required|string|max:255',
            'payout_method' => 'required|string|max:100',
            'note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            CashOut::create([
                'source' => $request->source,
                'amount' => $request->amount,
                'reciever' => $request->reciever,
                'given_by' => $request->given_by,
                'payout_method' => $request->payout_method,
                'note' => $request->note,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('cashout.index')
                ->with('success', 'Cash Out record created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create cash out record. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $cashOut = CashOut::with('createdBy')->findOrFail($id);
            return view('admin.cashout.show', compact('cashOut'));
        } catch (\Exception $e) {
            return redirect()->route('cashout.index')
                ->with('error', 'Cash Out record not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $cashOut = CashOut::with('createdBy')->findOrFail($id);
            
            // Get unique payout methods for dropdown
            $payoutMethods = CashOut::distinct()->pluck('payout_method')->filter()->sort();
            
            return view('admin.cashout.edit', compact('cashOut', 'payoutMethods'));
        } catch (\Exception $e) {
            return redirect()->route('cashout.index')
                ->with('error', 'Cash Out record not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'reciever' => 'required|string|max:255',
            'given_by' => 'required|string|max:255',
            'payout_method' => 'required|string|max:100',
            'note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $cashOut = CashOut::findOrFail($id);
            
            $cashOut->update([
                'source' => $request->source,
                'amount' => $request->amount,
                'reciever' => $request->reciever,
                'given_by' => $request->given_by,
                'payout_method' => $request->payout_method,
                'note' => $request->note,
            ]);

            return redirect()->route('cashout.index')
                ->with('success', 'Cash Out record updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update cash out record. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $cashOut = CashOut::findOrFail($id);
            $cashOut->delete();

            return redirect()->route('cashout.index')
                ->with('success', 'Cash Out record deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('cashout.index')
                ->with('error', 'Failed to delete cash out record.');
        }
    }

    /**
     * Get cash out statistics
     */
    public function statistics()
    {
        $totalCashOut = CashOut::sum('amount');
        $totalRecords = CashOut::count();
        $averageAmount = $totalRecords > 0 ? $totalCashOut / $totalRecords : 0;
        
        // Monthly statistics for current year
        $monthlyStats = CashOut::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('COUNT(*) as total_records')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy('month')
        ->get();

        // Recent cash outs (last 10)
        $recentCashOuts = CashOut::with('createdBy')->latest()->take(10)->get();

        // Top receivers
        $topReceivers = CashOut::select('reciever', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('reciever')
            ->orderBy('total_amount', 'desc')
            ->take(5)
            ->get();

        // Payout method statistics
        $payoutMethodStats = CashOut::select('payout_method', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('payout_method')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Top sources (purposes)
        $topSources = CashOut::select('source', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('source')
            ->orderBy('total_amount', 'desc')
            ->take(5)
            ->get();

        return view('admin.cashout.statistics', compact(
            'totalCashOut', 
            'totalRecords', 
            'averageAmount', 
            'monthlyStats',
            'recentCashOuts',
            'topReceivers',
            'payoutMethodStats',
            'topSources'
        ));
    }
}
