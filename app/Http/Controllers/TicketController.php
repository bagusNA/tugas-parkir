<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()->with(['employee', 'rate'])->paginate();
        $activeTickets = 
            Ticket::where('status', 'Aktif')
                ->latest()
                ->with(['employee', 'rate'])
                ->paginate();

        return view('ticket.index', [
            'tickets' => [
                'all' => $tickets,
                'active' => $activeTickets,
            ],
            'rates' => Rate::all()
        ]);
    }

    public function create(Request $request)
    {
        $input = $request->validate([
            'plate_number' => [
                'required',
                'regex:/^[A-Z]{1,2}\s{1}\d{0,4}\s{0,1}[A-Z]{0,3}$/i'
            ],
            'rate_id' => 'required|integer'
        ]);

        $ticket = Ticket::create([
            'employee_id' => Auth::user()->id,
            'rate_id' => $input['rate_id'],
            'plate_number' => $input['plate_number'],
            'enter_at' => Carbon::now(),
        ]);

        return redirect()->route('ticket.index')->with('ticket', $ticket);
    }

    public function finish(Request $request, Ticket $ticket)
    {
        $ticket->status = 'Selesai';
        $ticket->save();

        return redirect()->route('ticket.index');
    }

    public function finishBySearch(Request $request)
    {
        $input = $request->validate([
            'ticket_id' => 'required|integer'
        ]);

        $ticket = Ticket::find($input['ticket_id'])->load('rate');

        if (!$ticket) {
            return back()->with('error', 'Kode karcis tidak ditemukan!');
        }
        
        $enterAt = new Carbon($ticket->enter_at);
        $exitAt = new Carbon($ticket->exitAt);

        $totalHour = $exitAt->diffInHours($enterAt);

        $totalPrice = ($totalHour + 1) * $ticket->rate->price_per_hour + $ticket->rate->base_price;
        
        $ticket->status = 'Selesai';
        $ticket->total_hour = $totalHour;
        $ticket->total_price = $totalPrice;
        $ticket->exit_at = $exitAt;
        $ticket->save();

        return redirect()->route('ticket.index');
    }
}
