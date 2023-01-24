<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['code'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class);
    }

    public function scanCode(): HasOne
    {
        return $this->hasOne(ActiveCode::class);
    }

    protected function code(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $code = str_pad($attributes['id'], 4, '0', STR_PAD_LEFT);

                $date = new Carbon($attributes['enter_at']);
                $month = $this->toRoman($date->month);
                $year = $date->year;

                return "$year-$month-$code";
            }
        );
    }

    public function getTotal()
    {
        $enterAt = new Carbon($this->enter_at);
        $exitAt = new Carbon($this->exitAt);

        $totalHour = $exitAt->diffInHours($enterAt);

        $totalPrice = ($totalHour) * $this->rate->price_per_hour + $this->rate->base_price;

        return $totalPrice;
    }

    public function printTicket($code)
    {
        $connector = new CupsPrintConnector(env('CUPS_PRINTER', 'TM-T82-S-A'));
        $print = new Printer($connector);

        $print->selectPrintMode();
        $print->setTextSize(1,1);
        $print->text('SMKN 7 SAMARINDA');

        $print->setTextSize(1,1);
        $print->text($this->enter_at);
        $print->feed();
        
        $print->barcode(Str::upper($code), Printer::BARCODE_CODE39);
        $print->feed(2);

        $print->text($code);
        $print->feed();
        $print->text('Mohon karcis jangan sampai hilang\n');
        $print->text('Segala kehilangan merupakan tanggung jawab pribadi\n');
        $print->feed();
        $print->cut();
        $print->close();
    }

    public function printTicketOut()
    {
        $cashier = $this->employee;
        $code = $this->scanCode;
        $connector = new CupsPrintConnector(env('CUPS_PRINTER', 'TM-T82-S-A'));
        $print = new Printer($connector);

        $print->initialize();
        $print->selectPrintMode();
        $print->setTextSize(2,2);
        $print->text('SMKN 7 SAMARINDA');
        $print->feed();
        $print->text('-----------------');
        $print->feed();

        $print->setTextSize(1,1);
        $print->text("ENTRY: $this->enter_at");
        $print->feed();

        $print->text("EXIT: $this->exit_at");
        $print->feed(2);

        $print->setEmphasis(true);

        $print->text($this->textSpaceBetween(
            "PLATE",
            $this->plate_number
        ));
        $print->feed();

        $print->text($this->textSpaceBetween(
            "DURATION",
            "$this->total_hour JAM"
        ));
        $print->feed();

        $print->text($this->textSpaceBetween(
            "CASHIER",
            $cashier->name
        ));
        $print->feed();

        $print->text($this->textSpaceBetween(
            "TOTAL BILL",
            'Rp. ' . $this->total_price
        ));
        $print->feed();

        $print->text($this->textSpaceBetween(
            "TOTAL PAID",
            'Rp. ' . $this->total_paid
        ));
        $print->feed();

        $print->text($this->textSpaceBetween(
            "CHANGE",
            'Rp. ' . $this->total_paid - $this->total_price
        ));
        $print->feed();

        $print->text($this->textSpaceBetween(
            "TID",
            $code->code
        ));
        $print->feed();

        $print->cut();
        $print->close();
    }

    protected function textSpaceBetween($leftStr, $rightStr, $fontSize = 1) { 
        $maxChar = (int)(42 / $fontSize);
        $emptySpace = $maxChar - strlen($leftStr) - strlen($rightStr);
        return str_repeat(' ', $emptySpace); 
    }

    protected function toRoman(int $number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}
