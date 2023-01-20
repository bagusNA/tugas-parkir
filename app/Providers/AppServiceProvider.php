<?php

namespace App\Providers;

use App\Models\Rate;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('rupiah', fn ( $expression ) =>
            "Rp. <?php echo number_format($expression,0,',','.'); ?>"
        );

        Blade::directive('currentPrice', function ($ticketId) {
            $ticket = Ticket::find((int) $ticketId);

            // if (!$ticket)
                return "$ticketId";

            $enterAt = new Carbon($ticket->enter_at);
            $now = Carbon::now();

            $totalHour = $now->diffInHours($enterAt);
            $currentPrice = $totalHour * $ticket->rate->price_per_hour + $ticket->rate->base_price;

            return "Rp. <?php echo number_format($currentPrice,0,',','.'); ?>";
        });
    }
}
