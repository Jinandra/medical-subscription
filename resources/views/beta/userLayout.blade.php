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
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.3/js.cookie.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js"></script>
    <script src="{{ URL::asset('resources/assets/js/handlebars.fn.js') }}"></script>
    <script src="{{ URL::asset('resources/assets/js/script.js') }}"></script>
  </head>
  <body>
    <header>
      <div class="clearfix">
        <div class="_head">
          <a class="w-nav-control" id="nav-control"></a>
          <div class="header-container">
            <div class="col-xs-3 col-sm-2" >
              <a href="{{ url('/user') }}"><img src="{{ config('app.assets_path') }}/images/logo.png" alt=""/></a>
            </div>
            <div class="col-xs-3 col-sm-5 searchbar">
              <div class="search-section-input ml10 mr10">
                <form method="GET" action="{{ url('/search/filter') }}">
                  <input type="text" name="query" placeholder="Type UCode or Browse with Keywords" value="{{ Input::get('query') }}"/>
                  <input type="hidden" id="search-sort" name="sort" value="{{(Input::get('sort'))?Input::get('sort'):App\Models\Common::SEARCH_SORT_MOST_POPULAR}}"/>
                  <input type="hidden" id="search-date" name="date" value="{{(Input::get('date'))?Input::get('date'):App\Models\Common::SEARCH_UPLOAD_ALL_TIME}}"/>
                  <input type="hidden" id="search-types" name="types" value="{{(Input::get('types'))?Input::get('types'):App\Models\Common::SEARCH_TYPE_ALL}}"/>
                  <input type="submit" class="search-btn" value="search"/>
                </form>
              </div>
            </div>
            <div class="col-xs-4 col-sm-4 pull-right">
              <ul class="user-table">
                <li class="contribute">
                  <a href="{{ url('/contribute/addForm') }}" title="Add Media">
                    <i class="fa fa-plus"></i>
                  </a>
                </li>
                <?php /*?><li class="notification">
                    <a href="{{ url('/post/message') }}">
                        <i class="fa fa-bell"></i>
                    </a>
                </li><?php */?>
                @if (Auth::check())
                  <li class="settings dropdown-hover dropdown-account">
                    <div class="dropdown">
                      @if (trim(Auth::user()->initialName()) === '')
                        <div class="more-action more-action-padding" data-trigger="hover" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-user fa-ico-circle"></i>
                        </div>
                      @else
                        <div class="more-action" data-trigger="hover" data-toggle="dropdown"
                            role="button" aria-haspopup="true" aria-expanded="false">
                          <div class="img-initial">{{ Auth::user()->initialName() }}</div>
                        </div>
                      @endif
                      <ul class="dropdown-menu pull-right" aria-labelledby="Menu Profile">
                        @if ( Auth::user()->hasRole('administrator') )
                          <li><a href="{{ url('admin') }}">Admin Dashboard</a></li>
                        @endif
  <?php /*
                        <li>
                          <a href="{{ url('/account') }}">Profile</a>
                        </li>
  */ ?>
                        <li>
                          <a href="{{ url('user/logout') }}">Logout</a>
                        </li>
                      </ul>
                    </div>
                  </li>
                @endif
                @if (!Auth::check())
                  <li class="dropdown dropdown-user" id="fat-menu12">
                    <a id="drop3" href="#" data-target="#myModal" data-toggle="modal">
                      Login | Sign Up
                    </a>
                  </li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div>
    </header>

    @if (Session::has('message'))
      <?php /* FIXME: add notification bar */ ?>
      <!--
      <div class="header-alert active">
        <div class="header-alert-panel">
          <div class="header-alert-padding">
            <i class="fa fa-check mr10"></i> <span  id="alertHeading">{{ session('message')}}</span>
          </div>
          <div class="header-alert-close header-alert-padding">
            Close
          </div>
        </div>
      </div>
      -->
    @endif


    <div class="container pr {{ Request::segment(1) == 'user' && Request::segment(2) == '' ? 'siteMenu' : '' }}">
      @if ( !isset($hideBundleCart) || $hideBundleCart )
        <div class="add-bundle-wrap">
          <a href="{{ url('/bundle/view') }}" class="btn btn-green">
            <span id="add-bundle-number"><?php if (isset($countBundleCart[0])) echo $countBundleCart[0]; ?></span>&nbsp;&nbsp;Bundle Cart
          </a>
        </div>
      @endif
      <div class="row">
        <div class="col-sm-2 col-md-2 col-xs-2 _left-column" id="menu">
          <ul class="_nav">
            <li>
                <a href="{{ url('/user') }}" class='<?php echo (Request::segment(1) == 'user' && Request::segment(2) == '') ? 'active' : ''; ?>'><i class="fa fa-home"></i><span class="title">Home</span> </a>
            </li>
            <li>
              <a href="{{  url('/collection') }}" class='<?php echo (Request::segment(1) == 'collection') ? 'active' : ''; ?>'><span class="dib vam lh60"><i class="el-ico ico-20 ico-collection"></i></span><span class="title">My Collection</span></a>
            </li>
            <li>
              <a href="{{ url('/bundle') }}" class='<?php echo (Request::segment(1) == 'bundle') ? 'active' : ''; ?>'><span class="dib vam lh60"><i class="el-ico ico-20 ico-ucode"></i></span><span class="title">My UCodes</span></a>
            </li>
            <li>
              <a href="{{ url('/contribute') }}" class='<?php echo (Request::segment(1) == 'contribute') ? 'active' : ''; ?>'><i class="fa fa-arrow-circle-up"></i><span class="title">My Contributions</span></a>
            </li>
            <li>
              <a href="{{ url('/account') }}" class='<?php echo (Request::segment(1) == 'account') ? 'active' : ''; ?>'><i class="fa fa-gear"></i><span class="title">Account</span></a>
            </li>
            <li>
              <a href="{{ url('/post') }}" class='<?php echo (Request::segment(1) == 'post') ? 'active' : ''; ?>'><i class="fa fa-life-bouy"></i><span class="title">About Us</span></a>
            </li>
            <li>
              <a href="{{ url('learns') }}" class='<?php echo (Request::segment(1) == 'learns') ? 'active' : ''; ?>'><i class="fa fa-book"></i><span class="title">Learn</span></a>
            </li>
          </ul>
        </div>

        @yield('content')

      </div>
    </div>

    @if (Auth::check())
      <a href="https://goo.gl/forms/UhDlV80zMsOWVDVW2" target="_blank" class="btn-orange-floating">
        <i class="fa fa-pencil mr5"></i> Feedback
      </a>
    @endif

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-83003613-1', 'auto');
      ga('send', 'pageview');
    </script>
    <script type="text/javascript">
      $(document).ready(function () {

        // Left menu expand / collapse handler
        (function () {
          var $menu = $('#menu'),
              $menulink = $('#nav-control'),
              $navsection = $('._nav');

          /* if (Cookies.get('nav') === 'active') { */
          /*   $navsection.addClass('click-active'); */
          /* } else { */
          /*   $navsection.removeClass('click-active'); */
          /* } */

          function menuSidebarDo(action) {
            if(action){
              $menulink.addClass('active');
              $menu.addClass('active');
            } else {
              $menulink.removeClass('active');
              $menu.removeClass('active');
            }
          };
          function collapseMenuSidebar () {
            if (!$navsection.hasClass('click-active')) {
              menuSidebarDo(false);
            }
          }

          $menulink.click(function (e) {
            if (!$navsection.hasClass('click-active')) {
              $navsection.addClass('click-active')
              /* Cookies.set('nav', 'active'); */
              menuSidebarDo(true);
            } else {
              $navsection.removeClass('click-active')
              /* Cookies.set('nav', 'inactive'); */
              menuSidebarDo(false);
            }
          });

          $menulink.mouseenter(function () {
            menuSidebarDo(true);
          });
          $navsection.mouseleave(collapseMenuSidebar);
          $('.header-container').mouseenter(collapseMenuSidebar);
        })();


        $('#dropdown-auto').change(function () {
          $("#dropdown-auto-tmp-option").html($('#dropdown-auto option:selected').text());
          $(this).width($("#dropdown-auto-tmp").width());
        });


        /* $('.img-svg').each(function() { */
        /*   var $img = jQuery(this); */
        /*   var imgURL = $img.attr('src'); */
        /*   var attributes = $img.prop("attributes"); */

        /*   $.get(imgURL, function(data) { */
        /*     var $svg = jQuery(data).find('svg'); */
        /*     $svg = $svg.removeAttr('xmlns:a'); */
        /*     $.each(attributes, function() { */
        /*       $svg.attr(this.name, this.value); */
        /*     }); */
        /*     $img.replaceWith($svg); */
        /*   },'xml'); */
        /* }); */

        // TODO: notification bar - later
        /* var timeOut; */
        /* $('.header-alert-close').on('click', function () { */
        /*     var b = $('.header-alert'); */
        /*     b.removeClass('active'); */
        /*     clearTimeout(timeOut); */
        /* }); */

        /* setTimeout(function () { */
        /*   var b = $('.header-alert'); */
        /*   b.removeClass('active'); */
        /*   clearTimeout(timeOut); */
        /* }, 4000); */
      });
    </script>
    <style type="text/css">
        .limit-text, .truncated-text, .limit-text-hidden {
            font-family: 'proxima_nova_rgregular';
        }
      span.tooltiptext.tooltip-floating { background: #fff; }
      .siteMenu { z-index: 0; }
    </style>

    @yield('additionalScript')

    </body>
</html>
