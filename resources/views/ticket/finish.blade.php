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
        grid-template-rows: 1fr 1fr;
        padding: 0 2rem;
        gap: 1rem;
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
            @if (session('ticket'))
              <form action="{{ route('ticket.finish.post') }}" method="post">
                <h1 class="auth-title mb-5">Bayar</h1>
                <input type="text" class="form-control fs-1 mb-2" id="kode-karcis" placeholder="Total Bayar">
                <div class="d-grid">
                  <button class="btn btn-primary fs-1">Bayar</button>
                </div>
              </form>
            @else
              @if (session('error'))
                <div class="alert alert-warning">
                  {{ session('error') }}
                </div>
              @endif

              <form action="" method="post">
                <h1 class="auth-title mb-5">Cari</h1>
                <input type="text" class="form-control fs-1 mb-2" id="kode-karcis" placeholder="Kode Karcis">
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
              @if (session('ticket'))
                <div id="ticket" class="card text-center">
                  <h2>KARCIS</h2>
                  <h4>{{ $rate->type }}</h4>
                  
                  <h1 class="fw-bold text-uppercase">{{ session('ticket')['scanCode']['code'] }}</h1>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
