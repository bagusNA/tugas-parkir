<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Parkir Pintar</title>
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
            <div class="auth-logo">
              <a href="index.html"
                ><img src="{{ asset('assets/images/logo/logo.svg') }}" alt="Logo"
              /></a>
            </div>
            @if ($ticket)
              <form action="{{ route('ticket.finish.post') }}" method="post">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                <h1 class="auth-title mb-5">Bayar</h1>
                <input type="text" name="plate_number" class="form-control fs-1 mb-2" id="plate-number" placeholder="Plat Nomor" required>
                <input type="text" class="form-control fs-1 mb-2" id="total_paid" name="total_paid" placeholder="Total Bayar">
                <div class="d-grid">
                  <button class="btn btn-primary fs-1">Bayar</button>
                </div>
              </form>
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
                  <h2>KARCIS</h2>
                  <div class="form-group">
                    <label for="disabledInput">Readonly Input</label>
                    <input type="text" class="form-control form-control-lg" id="readonlyInput" readonly="readonly" value="You can't update me :P">
                  </div>
                  <h4>{{ $ticket->rate->type }}</h4>
                  <h4>Rp. {{ $total }}</h4>
                  
                  <h1 class="fw-bold text-uppercase">{{ $ticket->scanCode->code }}</h1>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
