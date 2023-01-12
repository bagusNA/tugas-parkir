<?php

namespace App\Http\Controllers;

use App\Models\ActiveCode;
use App\Models\Rate;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Str;
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

    public function createForm(Rate $rate)
    {
        return view('ticket.form', [
            'rate' => $rate
        ]);
    }

    public function create(Rate $rate, Request $request)
    {
        $ticket = Ticket::create([
            'employee_id' => Auth::user()->id,
            'rate_id' => $rate->id,
            'enter_at' => Carbon::now('Asia/Singapore'),
        ]);

        $scanCode = ActiveCode::create([
            'code' => Str::random(6),
            'ticket_id' => $ticket->id
        ]);

        // $ticket->printTicket($scanCode->code);

        return back()->with('ticket', $ticket->load('scanCode'));
    }

    public function finishForm(Request $request)
    {
        return view('ticket.finish');
    }

    public function finish(Request $request)
    {
        $input = $request->validate([
            'code' => 'required'
        ]);

        $code = ActiveCode::firstWhere('code', $input['code']);
        if (!$code) {
            return back()->with('error', 'Kode karcis tidak ditemukan!');
        }

        $ticket = $code->ticket;
        
        $enterAt = new Carbon($ticket->enter_at);
        $exitAt = new Carbon($ticket->exitAt);

        $totalHour = $exitAt->diffInHours($enterAt);

        $totalPrice = ($totalHour + 1) * $ticket->rate->price_per_hour + $ticket->rate->base_price;
        
        $ticket->status = 'Selesai';
        $ticket->total_hour = $totalHour;
        $ticket->total_price = $totalPrice;
        $ticket->exit_at = $exitAt;
        $ticket->save();

        $ticket->scanCode->delete();
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

        $ticket->scanCode->delete();

        return redirect()->route('ticket.index');
    }
}
