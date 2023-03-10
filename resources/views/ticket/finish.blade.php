<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Loket Keluar - Parkir Pintar</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}" />
    <link
      rel="shortcut icon"
      href="{{ asset('assets/images/logo/favicon.svg') }}"
      type="image/x-icon"
    />
    <link
      rel="shortcut icon"
      href="{{ asset('assets/images/logo/favicon.png') }}"
      type="image/png"
    />

    <style>
      .auth-grid {
        display: grid;
        place-items: center;
        height: 100%;
      }

      #ticket {
        padding: 3.5rem;
      }
    </style>
  </head>

  <body>
    <div id="auth">
      <div class="row h-100">
        <div class="col-lg-5 col-12">
          <div id="auth-left">
            <div class="auth-logo fs-1 fw-bold">
              <a href="index.html">Parkir Pintar</a>
            </div>
            @if ($ticket)
              <form action="{{ route('ticket.finish.post') }}" method="post">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                <h1 class="auth-title mb-5">Bayar</h1>
                <input type="text" name="plate_number" class="form-control fs-1 mb-2" id="plate-number" placeholder="Plat Nomor" required>
                <input type="text" class="form-control fs-1 mb-2" id="total_paid" name="total_paid" placeholder="Total Bayar">
                <input type="text" class="form-control fs-1 mb-2" id="total_bill" name="total_bill" placeholder="Total" value="{{ $total }}" hidden>
                <div class="d-grid">
                  <button id="pay_button" class="btn btn-primary fs-1">Bayar</button>
                </div>
              </form>

              <hr>

              <h2>Kembalian</h2>
              <h2>Rp. <span id="change">-</span></h2>
            @else
              @isset ($error)
                <div class="alert alert-warning">
                  {{ $error }}
                </div>
              @endisset

              <form action="{{ route('ticket.finish.form') }}">
                <h1 class="auth-title mb-5">Cari</h1>
                <input type="text" name="code" class="form-control fs-1 mb-2" id="kode-karcis" placeholder="Kode Karcis" required value="{{ request()->input('code') }}">
                <div class="d-grid">
                  <button class="btn btn-primary fs-1">Cari</button>
                </div>
              </form>
            @endif
          </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
          <div id="auth-right">
            <div class="auth-grid">
              @if ($ticket)
                <div id="ticket" class="card">
                  <h2>KARCIS - {{ $ticket->rate->type }}</h2>
                  <h1 class="fw-bold text-uppercase">{{ $ticket->scanCode->code }}</h1>
                  {{-- <p>Masuk: {{ (new Carbon($ticket->enter_at))->format("Y-m-d H:i:s") }}</p> --}}
                  <p>Masuk: {{ $ticket->enter_at }}</p>
                  <p>Total Jam: {{ $totalHour }} Jam</p>
                  {{-- <p>Keluar: {{ (new Carbon($ticket->enter_at))->format("Y-m-d H:i:s") }}</p> --}}
                  <h4>Rp. {{ $total }}</h4>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="{{ asset('assets/js/finish.js') }}" defer></script>
  </body>
</html>
