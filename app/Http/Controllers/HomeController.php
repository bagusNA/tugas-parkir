<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $todayStart = Carbon::today();
        $todayEnd = (new Carbon($todayStart))->endOfDay();

        $todayTicket = Ticket::whereBetween('created_at', [$todayStart, $todayEnd]);
        $todayTicketCount = $todayTicket->count();

        $todayActiveTicketCount = $todayTicket->where('status', 'Aktif')->count();
        $todayDoneTicketCount = $todayTicket->where('status', 'Selesai')->count();
        
        return view('dashboard', [
            'title' => 'Dashboard',
            'todayTicketCount' => $todayTicketCount,
            'todayActiveTicketCount' => $todayActiveTicketCount,
            'todayDoneTicketCount' => $todayDoneTicketCount,
        ]);
    }
}
