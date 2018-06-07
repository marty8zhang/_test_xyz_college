@php
/**
* The VIEW of the layout of the Dashboard.
* @author Marty Zhang
* @version 0.9.201806072029
*/
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    <link href="{{ (App::environment('demo') ? env('DEMO_ROOT_URI', '/xyz-college') : '') . mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ (App::environment('demo') ? env('DEMO_ROOT_URI', '/xyz-college') : '') . mix('/css/additions.css') }}" rel="stylesheet">
    @stack('header_stylesheets')
  </head>
  <body>
    <div id="app">
      <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
          <div class="navbar-header">
            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
              <span class="sr-only">Toggle Navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
              {{ config('app.name', 'Laravel') }}
            </a>
          </div>
          <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
              <li><a href="{{ route('dashboard.students.index') }}">Students</a></li>
              <li><a href="{{ route('dashboard.courses.index') }}">Courses</a></li>
              <li><a href="{{ route('dashboard.students-courses-enrollment.index') }}">Students & Courses Enrollment</a></li>
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
              <!-- Authentication Links -->
              @guest
              <li><a href="{{ route('login') }}">Login</a></li>
              <li><a href="{{ route('register') }}">Register</a></li>
              @else
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                  {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                      Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                    </form>
                  </li>
                </ul>
              </li>
              @endguest
            </ul>
          </div>
        </div>
      </nav>
      @yield('content')
    </div>
    <!-- Scripts -->
    <script src="{{ (App::environment('demo') ? env('DEMO_ROOT_URI', '/xyz-college') : '') . mix('/js/app.js') }}"></script>
    @stack('footer_scripts')
  </body>
</html>