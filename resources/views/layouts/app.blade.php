<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.title') }}</title>

    <meta name="description" content="{{ config('app.description') }}">

    @if (Storage::disk('public')->exists('icon.png'))
      <link rel="icon" type="image/png" href="{{ asset('storage/icon.png') }}">
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- jQuery cropper --}}
    <script src="{{ asset('assets/cropper.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/cropper.css') }}">
    <script src="{{ asset('assets/jquery-cropper.js') }}"></script>

    {{-- Quill --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    {{-- Compressor --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.1.1/compressor.min.js" integrity="sha512-VaRptAfSxXFAv+vx33XixtIVT9A/9unb1Q8fp63y1ljF+Sbka+eMJWoDAArdm7jOYuLQHVx5v60TQ+t3EA8weA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
<body>
  <nav class="navbar navbar-dark navbar-expand-lg navtop">
    <div class="container navtop-container">
      <button class="navbar-toggler navtop-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navtop" aria-controls="navtop" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navtop">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <div class="navtop-social-links">
            @foreach (config('app.sociallinks') as $link)
              @if (config('app.'.$link) != null)
                <li class="nav-item">
                  <a class="nav-link" href="{{ config('app.'.$link) }}" target="_blank" rel="nofollow"><i class="bi bi-{{ $link }}"></i></a>
                </li>
              @endif
            @endforeach
          </div>
          @foreach (config('app.navbar.links.top') as $link)
            @if ($link->type == 'dropdown')
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ $link->title }}</a>
                <ul class="dropdown-menu">
                  @if ($link->links->isNotEmpty())
                    @foreach ($link->links as $link)
                      <li><a class="dropdown-item" @if($link->type == 'url') href="{{ $link->url }}" target="_blank" @elseif($link->type == 'category') href="{{ route('categories.show', $link->category_id) }}" @endif>{{ $link->title }}</a></li>
                    @endforeach
                  @else
                    <p class="px-3 m-0">Empty</p>
                  @endif
                </ul>
              </li>
            @elseif ($link->link_id == NULL)
              <li class="nav-item">
                <a class="nav-link" @if($link->type == 'url') href="{{ $link->url }}" target="_blank" @elseif($link->type == 'category') href="{{ route('categories.show', $link->category_id) }}" @endif>{{ $link->title }}</a>
              </li>
            @endif
          @endforeach
        </ul>
      </div>
      <ul class="navbar-nav static-navtop-list">
        <!-- Authentication Links -->
        @guest
          @if (Route::has('login'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
          @endif

          @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
          @endif
        @else
          <li class="nav-item dropdown">
            <a id="navtop-profile-dropdown" class="nav-link dropdown-toggle navtop-profile-dropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="{{ asset(Auth::user()->image->url_sm) }}" class="rounded-circle navtop-profile-image" alt="Profile image"> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu">
              @if (Auth::user()->is_admin)
                <li>
                  <a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
              @endif
              <li>
                <a href="{{ route('profile.index') }}" class="dropdown-item">Profile</a>
              </li>
              <hr class="dropdown-divider">
              <li>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </li>
            </ul>
          </li>
        @endguest
      </ul>
    </div>
  </nav>
  <section class="header">
    <div class="container">
      @if (Storage::disk('public')->exists('logo_light.png'))
        <link rel="icon" type="image/png" href="{{ asset('storage/logo_light.png') }}">
        <img class="header-logo" src="{{ asset('storage/logo_light.png') }}" alt="{{ config('app.title') }} logo">
        @else
        <img class="header-logo" src="{{ asset('images/header-logo.png') }}" alt="{{ config('app.title') }} logo">
      @endif
    </div>
  </section>
  <nav class="navbar navbar-dark navbar-expand-lg navbar-center {{ config('app.navbar.fixed') == TRUE ? 'sticky-top' : '' }}">
    <div class="container">
      <button class="navbar-toggler navbar-center-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-center" aria-controls="navbar-center" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{route('home')}}"><i class="bi bi-house-door-fill me-1"></i> Home</a>
        </li>
      </ul>
      <div class="collapse navbar-collapse" id="navbar-center">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          @foreach (config('app.navbar.links.center') as $link)
            @if ($link->type == 'dropdown')
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ $link->title }}</a>
                <ul class="dropdown-menu">
                  @if ($link->links->isNotEmpty())
                    @foreach ($link->links as $link)
                      <li><a class="dropdown-item" @if($link->type == 'url') href="{{ $link->url }}" target="_blank" @elseif($link->type == 'category') href="{{ route('categories.show', $link->category_id) }}" @endif>{{ $link->title }}</a></li>
                    @endforeach
                  @else
                    <p class="px-3 m-0">Empty</p>
                  @endif
                </ul>
              </li>
            @elseif ($link->link_id == NULL)
              <li class="nav-item">
                <a class="nav-link" @if($link->type == 'url') href="{{ $link->url }}" target="_blank" @elseif($link->type == 'category') href="{{ route('categories.show', $link->category_id) }}" @endif>{{ $link->title }}</a>
              </li>
            @endif
          @endforeach
        </ul>
        @if (config('app.search') == TRUE)
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <form action="{{ route('posts.search') }}" method="GET">
                <div class="nav-search">
                  <button class="search-btn" type="submit"><i class="bi bi-search"></i></button>
                  <input type="search" name="q" id="q" placeholder="Search" required>
                </div>
              </form>
            </li>
          </ul>
        @endif
      </div>
    </div>
  </nav>
  <main class="py-4">
    <div class="container">
      @yield('content')
    </div>
  </main>
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="footer-list">
            <h4 class="footer-list-title">World of technology</h4>
            <ul>
              <li><p>{{ config('app.description') }}</p></li>
              <li>
                @foreach (config('app.sociallinks') as $link)
                  @if (config('app.'.$link) != null)
                    <a class="pe-1" href="{{ config('app.'.$link) }}" target="_blank" rel="nofollow"><i class="bi bi-{{ $link }}"></i></a>
                  @endif
                @endforeach
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="footer-list">
            <h4 class="footer-list-title">{{ config('app.footer.title01') }}</h4>
            <ul>
              @foreach (config('app.footer.list01') as $link)
                <li><a href="{{ $link->type == 'url' ? $link->url : route('categories.show', $link->category) }}" {{ $link->type == 'url' ? 'target="_blank"' : '' }}>{{ $link->title }}</a></li>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="footer-list">
            <h4 class="footer-list-title">{{ config('app.footer.title02') }}</h4>
            <ul>
              @foreach (config('app.footer.list02') as $link)
                <li><a href="{{ $link->type == 'url' ? $link->url : route('categories.show', $link->category) }}" {{ $link->type == 'url' ? 'target="_blank"' : '' }}>{{ $link->title }}</a></li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-copyrights">
      <div class="container">
        <span>Copyright &copy; {{ date('Y') }}, {{ config('app.title') }}, All rights reserved.</span>
      </div>
    </div>
  </footer>
  @if (isset($scroll) || session('scroll'))
    <script>
      $([document.documentElement, document.body]).animate({
          scrollTop: $("{{ $scroll ?? session('scroll') }}").offset().top-100
      });
    </script>
  @endif
  @yield('scripts')
</body>
</html>
