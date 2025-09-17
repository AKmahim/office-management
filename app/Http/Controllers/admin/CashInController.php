<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CashIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CashInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CashIn::with('addedBy');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('source', 'LIKE', "%{$search}%")
                  ->orWhere('note', 'LIKE', "%{$search}%")
                  ->orWhere('amount', 'LIKE', "%{$search}%")
                  ->orWhereHas('addedBy', function($userQuery) use ($search) {
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

        // Order by latest first
        $cashIns = $query->orderBy('created_at', 'desc')->paginate(10);

        // Calculate total amount for current filtered results
        $totalAmount = $query->sum('amount');

        return view('admin.cashin.index', compact('cashIns', 'totalAmount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cashin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            CashIn::create([
                'source' => $request->source,
                'amount' => $request->amount,
                'note' => $request->note,
                'added_by' => auth()->id(),
            ]);

            return redirect()->route('cashin.index')
                ->with('success', 'Cash In record created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create cash in record. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $cashIn = CashIn::with('addedBy')->findOrFail($id);
            return view('admin.cashin.show', compact('cashIn'));
        } catch (\Exception $e) {
            return redirect()->route('cashin.index')
                ->with('error', 'Cash In record not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $cashIn = CashIn::with('addedBy')->findOrFail($id);
            return view('admin.cashin.edit', compact('cashIn'));
        } catch (\Exception $e) {
            return redirect()->route('cashin.index')
                ->with('error', 'Cash In record not found.');
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
            'note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $cashIn = CashIn::findOrFail($id);
            
            $cashIn->update([
                'source' => $request->source,
                'amount' => $request->amount,
                'note' => $request->note,
            ]);

            return redirect()->route('cashin.index')
                ->with('success', 'Cash In record updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update cash in record. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $cashIn = CashIn::findOrFail($id);
            $cashIn->delete();

            return redirect()->route('cashin.index')
                ->with('success', 'Cash In record deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('cashin.index')
                ->with('error', 'Failed to delete cash in record.');
        }
    }

    /**
     * Get cash in statistics
     */
    public function statistics()
    {
        $totalCashIn = CashIn::sum('amount');
        $totalRecords = CashIn::count();
        $averageAmount = $totalRecords > 0 ? $totalCashIn / $totalRecords : 0;
        
        // Monthly statistics for current year
        $monthlyStats = CashIn::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('COUNT(*) as total_records')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy('month')
        ->get();

        // Recent cash ins (last 10)
        $recentCashIns = CashIn::with('addedBy')->latest()->take(10)->get();

        // Top sources
        $topSources = CashIn::select('source', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('source')
            ->orderBy('total_amount', 'desc')
            ->take(5)
            ->get();

        return view('admin.cashin.statistics', compact(
            'totalCashIn', 
            'totalRecords', 
            'averageAmount', 
            'monthlyStats',
            'recentCashIns',
            'topSources'
        ));
    }
}
