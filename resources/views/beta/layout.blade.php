<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'Enfolink')</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    @include('beta.style')
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!--<script src="https://code.jquery.com/jquery-3.1.1.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.3/js.cookie.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script src="{{ URL::asset('resources/assets/js/script.js') }}"></script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-83003613-1', 'auto');
      ga('send', 'pageview');
    </script>
  </head>

  <body>
    <header>
      <div class="clearfix">
        <div class="_head">
          <div class="col-xs-4 col-sm-2 ">
            <a href="/" class="logo-enfolink"><img src="{{ config('app.assets_path') }}/images/logo.png" alt="enfolink logo"/></a>
          </div>
          <div class="col-xs-4 col-sm-5 searchbar">
            <div class="search-section-input">
              <form method="get" action="{{ url('/search') }}">
                <input type="text" name="s" placeholder="Type UCode or Browse with Keywords"/>
                <input type="submit" class="search-btn" value="search"/>
              </form>
            </div>
          </div>
          <div class="col-sm-4 pull-right">
            <ul class="user-table">
              <li class="settings"><a href="/post/founder-s-message">About Us</a></li>
              <li>
                <a data-toggle="modal" data-remote="false" data-target="#modal-login" href="{{ url('user/login') }}">
                  Login
                </a>
              </li>
              <li class="dropdown dropdown-user">
                <a href="{{ url('user/registration') }}">Signup</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>

    @yield('content')


    <div class="modal fade" id="modal-login" tabindex="-1" role="dialog">
      <div class="modal-dialog form-modal" role="document">
        <div class="modal-content">
          <div class="modal-body form-popup">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
            <div class="row">
              <div class="col-xs-12">
                <h3>Login</h3>
                <form method="post" action="{{ url('user/login') }}">
                  <div>
                    @if (Input::get('modal')=='login')
                      @if (count($errors) > 0 || Session::has('error_auth'))
                        <div class="alert alert-danger">
                          <p>Invalid credentials !</p>
                        </div>
                      @endif
                    @endif
                    <div class="mb10">
                      <input type="text" id="screenName" name="screen_name" class="form-control mb0" value="{{ old('screen_name') }}" placeholder="Enter username or email address" required />
                      <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
                    </div>
                    <div class="mb10">
                      <input type="password" id="password" name="password" class="form-control mb0" placeholder="Enter password" required />
                      <!--<small class="db txt-red fz12 mt10 mb20">Password minimum lenght is 5 character</small>-->
                    </div>
                    <div class="mb10 mt10">
                      <label class="checkbox-default mr5">
                        <input id="remember" type="checkbox" name="remember" {{ is_null(old('remember')) ? '' : "checked" }}>
                        <span class="ico-checkbox"></span>
                      </label>
                      <label for="remember" class="mt5">Remember me</label>
                    </div>
                    <div class="text-center">
                      {{ csrf_field() }}
                      <input type="submit" class="el-btn el-btn-lg el-btn-green el-btn-padding-lg full" value="Login" />
                    </div>
                    <div class="mt20 text-center mb10">
                      <a id="btn-reset-pass" href="{{ url('password/reset') }}">
                        Forgot password?
                      </a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-reset-pass" tabindex="-1" role="dialog">
      <div class="modal-dialog form-modal" role="document">
        <div class="modal-content">
          <div class="modal-body form-popup">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
            <div class="row">
            <form role="form" method="POST" action="{{ url('/password/email') }}">
              {{ csrf_field() }}
              <div class="col-xs-12">
                <h3>Reset Password</h3>
                <div>
                  <div class="mb10">
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email Address" class="mb0" />
                    <!--<small class="db txt-red fz12 mt10 mb20">Email format is incorect</small>-->
                    @if ($errors->has('email'))
                      <small class="db txt-red fz12 mt10 mb20">{{ $errors->first('email') }}</small>
                    @endif
                  </div>
                  <div class="text-center mb10">
                    <input type="submit" class="el-btn el-btn-lg el-btn-green el-btn-padding-lg full" value="Send Password Reset Link" />
                  </div>
                </div>
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-reset-pass-success" tabindex="-1" role="dialog">
      <div class="modal-dialog form-modal" role="document">
        <div class="modal-content">
          <div class="modal-body form-popup">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
            <div class="row">
              <div class="col-xs-12">
                <h3>Reset Password Success</h3>
                <div>
                  {{ Session::get('status') }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if (Auth::check())
      <a href="https://goo.gl/forms/UhDlV80zMsOWVDVW2" target="_blank" class="btn-orange-floating">
        <i class="fa fa-pencil mr5"></i> Feedback
      </a>
    @endif
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ config('app.assets_path') }}/js/owl.carousel.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {
        var $menu = $('#menu'), $overlay = $('#overlay'), $menulink = $('#nav-control');
        $menulink.click(function (e) {
          $menulink.toggleClass('active');
          $menu.toggleClass('active');
          $overlay.toggleClass('active');
        });

        var $menu = $('#menu'), $overlay = $('#overlay'), $menulink = $('#nav-control');
        $overlay.click(function (e) {
          $menulink.toggleClass('active');
          $menu.toggleClass('active');
          $overlay.toggleClass('active');
        });

        $('#dropdown-auto').change(function () {
          $("#dropdown-auto-tmp-option").html($('#dropdown-auto option:selected').text());
          $(this).width($("#dropdown-auto-tmp").width());
        });

        $('#btn-reset-pass').on('click', function (e) {
          e.preventDefault();
          $('#modal-login').modal('hide');
          $('#modal-reset-pass').modal('show');
        });

        @if ( preg_match('/e-mailed.*password.*reset.*link/i', Session::get('status')) )
          if (location.pathname.match(/^\/password\/reset/) === null) {
            $('#modal-reset-pass-success').modal('show');
          }
        @elseif ( $errors->has('email') && is_null(old('agree')) )
          if (location.pathname.match(/^\/password\/reset/) === null) {
            $('#modal-reset-pass').modal('show');
          }
        @elseif( Session::get('status')=='fail' || (isset($_GET['modal']) && $_GET['modal'] == 'login' ))
          if (location.pathname === '/') {
            $('#modal-login').modal('show');
          }
        @endif

        $('a[data-target="#modal-login"]').on('click', function (e) {
          e.preventDefault();
        });
      });
    </script>

    @yield('additionalScript')

    <style type="text/css">
      .limit-text, .truncated-text, .limit-text-hidden {
        font-family: 'proxima_nova_rgregular';
      }
    </style>

    @yield('footer')
  </body>
</html>
