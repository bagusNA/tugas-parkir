<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Parkir Pintar')</title>
  
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}" />
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
  
    <link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}" />
  </head>

  <body>
    <script src="assets/js/initTheme.js"></script>
    <div id="app">
      @include('layouts.sidebar')

      <div id="main">
        <header class="mb-3">
          <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
          </a>
        </header>
        
        <div class="page-heading flex-grow-0">
          <h3>@yield('title', 'Parkir Pintar')</h3>
        </div>

        @yield('content')

        @include('layouts.footer')
      </div>
    </div>
    
    @include('layouts.scripts')
  </body>
</html>
