<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
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

                return "$code/$month/$year";
            }
        );
    }

    public function printTicket($code)
    {
        $connector = new FilePrintConnector('/dev/usb/lp0');
        $print = new Printer($connector);

        $print->setTextSize(1,1);
        $print->text('SMKN 7 SAMARINDA');

        $print->setTextSize(1,1);
        $print->text($this->enter_at);
        
        $print->barcode(Str::upper($code), Printer::BARCODE_CODE39);
        $print->feed(2);

        $print->text($code);
        $print->text('Mohon karcis jangan sampai hilang');
        $print->text('Segala kehilangan merupakan tanggung jawab pribadi');
        $print->cut();
        $print->close();
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
