<?php

namespace App\Http\Controllers;

use App\Models\ActiveCode;
use App\Models\Rate;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $relations = ['employee', 'rate'];

        $doneTickets = 
            Ticket::where('status', 'Selesai')
                ->latest()
                ->with($relations)
                ->paginate();
        $activeTickets = 
            Ticket::where('status', 'Aktif')
                ->latest()
                ->with($relations)
                ->paginate();

        return view('ticket.index', [
            'tickets' => [
                'done' => $doneTickets,
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
        $input = $request->validate([
            'image' => 'required|string'
        ]);

        // dd($request->all());

        $ticket = Ticket::create([
            'employee_id' => Auth::user()->id,
            'rate_id' => $rate->id,
            'enter_at' => Carbon::now(),
        ]);

        $scanCode = ActiveCode::create([
            'code' => Str::upper(Str::random(6)),
            'ticket_id' => $ticket->id
        ]);

        $ticket->printTicket($scanCode->code);

        $fileName = "ticket/$ticket->code.png";
        $ticket->image = $fileName;
        $ticket->save();
        Storage::put("public/$fileName", base64_decode($input['image']));

        return back()->with('ticket', $ticket->load('scanCode'));
    }

    public function finishForm(Request $request)
    {
        $inputCode = Str::upper($request->query('code'));
        $data = [
            'ticket' => null,
            'total' => 0,
        ];

        if ($inputCode) {
            $code = ActiveCode::firstWhere('code', $inputCode);

            if (!$code) {
                return view('ticket.finish', $data)->with('error', 'Kode tidak ditemukan!');
            }

            $data['ticket'] = $code->ticket->load(['rate', 'scanCode']);
            $data['total'] = $data['ticket']->getTotal();

            $enterAt = new Carbon($code->ticket->enter_at);
            $exitAt = new Carbon($code->ticket->exitAt);

            $totalHour = $exitAt->diffInHours($enterAt) + 1;
            $data['totalHour'] = $totalHour;
        }

        return view('ticket.finish', $data);
    }

    public function finish(Request $request)
    {
        $input = $request->validate([
            'ticket_id' => 'required',
            'total_paid' => 'required',
            'plate_number' => 'required',
        ]);

        $ticket = Ticket::find($input['ticket_id']);
        
        $enterAt = new Carbon($ticket->enter_at);
        $exitAt = new Carbon($ticket->exitAt);

        $totalHour = $exitAt->diffInHours($enterAt) + 1;

        $ticket->status = 'Selesai';
        $ticket->total_hour = $totalHour;
        $ticket->total_price = $ticket->getTotal();
        $ticket->total_paid = $input['total_paid'];
        $ticket->plate_number = $input['plate_number'];
        $ticket->exit_at = $exitAt;
        $ticket->save();

        $ticket->printTicketOut();

        $ticket->scanCode->delete();

        return redirect()->route('ticket.finish.form')->with('success', 'Berhasil bayar parkir!');
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

        $totalHour = $exitAt->diffInHours($enterAt) + 1;

        $totalPrice = $totalHour * $ticket->rate->price_per_hour + $ticket->rate->base_price;
        
        $ticket->status = 'Selesai';
        $ticket->total_hour = $totalHour;
        $ticket->total_price = $totalPrice;
        $ticket->exit_at = $exitAt;
        $ticket->save();

        $ticket->scanCode->delete();

        return redirect()->route('ticket.index');
    }
}
