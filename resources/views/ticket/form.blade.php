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

      .webcam-wrapper {
        display: grid;
        place-items: center;
      }

      #webcam {
        background: #fff;
        padding: .5rem;
        border-radius: 10px;
        height: 35vh;
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
            <h1 class="auth-title mb-5">Welcome</h1>
            <h2 class="auth-title fs-1">{{ $rate->type }}</h2>

            <form action="{{ route('ticket.create', $rate->id) }}" method="POST" class="d-grid pt-5" id="form">
              @csrf
              <button class="btn btn-primary py-5 fs-1">Ambil Tiket</button>
            </form>
          </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
          <div id="auth-right">
            <div class="auth-grid">
              <div class="webcam-wrapper">
                <video src="" id="webcam" class=""></video>
                {{-- <canvas id="canvas"></canvas>
                <img src="" alt="" id="picture"> --}}
              </div>
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

    <script src="{{ asset('assets/js/ticket.js') }}"></script>
  </body>
</html>
