<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event; // Ensure you import the Event model if needed
use App\Models\Content;
use Illuminate\Support\Facades\Auth; // Import Auth if you need to check user authentication
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Carbon\Carbon; // Import Carbon for date handling if needed


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $events = Event::all(); // Fetch all events from the database
        return view('admin.event.index',compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'event_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
        ]);
        //event id will be auto generated 6 digit number and unique 
        do {
            $event_id = rand(100000, 999999); // Generate a random 6-digit number
        } while (Event::where('event_id', $event_id)->exists());

        
        $event = Event::create([
            'event_name' => $request->event_name,
            'event_id' => $event_id,
            'company_name' => $request->company_name,
        ]);
        ToastMagic::success('Event created successfully!');
        return redirect()->route('event.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $event = Event::findOrFail($id);
        $event->delete();
        ToastMagic::success('Event deleted successfully!');
        return redirect()->route('event.index');
    }

    /**
     * Display event statistics.
     */
    public function statistics()
    {
        // This method can be used to display event statistics
        // You can implement the logic to fetch and display statistics here
        return view('admin.event.statistics');
    }
    /**
     * Filter event statistics based on request parameters.
     */
    public function statisticsFilter(Request $request)
    {
        $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->startOfDay();
        $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->endOfDay();
        $event_id = $request->event_id;

        // Get event info
        $event = Event::where('event_id', $event_id)->first();

        // Group content by date and count
        $contents = Content::where('event_id', $event_id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total_participants')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format output
        $events = [];
        foreach ($contents as $content) {
            $events[] = [
                'date' => $content->date,
                'event_name' => $event ? $event->event_name : '',
                'event_id' => $event_id,
                'total_participants' => $content->total_participants,
            ];
        }

        return view('admin.event.statistics', compact('events', 'event'));
    }
}
