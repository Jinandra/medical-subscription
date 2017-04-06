<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />

    <title>{{ isset($page_title) ? $page_title : '' }} | {{ Config::get('app.app_title') }}</title>

    <link href="{{URL::asset('resources/assets/gente-admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="{{URL::asset('resources/assets/gente-admin/css/animate.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('resources/assets/gente-admin/css/custom.css')}}" rel="stylesheet">
    <link href="{{URL::asset('resources/assets/gente-admin/css/maps/jquery-jvectormap-2.0.1.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('resources/assets/gente-admin/css/icheck/flat/green.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('resources/assets/gente-admin/css/floatexamples.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{URL::asset('resources/assets/gente-admin/js/jquery-ui/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('resources/assets/css/admin.css') }}" rel="stylesheet" />

    <!--[if lt IE 9]>
      <script src="../resources/assets/js/ie8-responsive-file-warning.js"></script>
    <![endif]-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
    <script src="{{URL::asset('resources/assets/gente-admin/js/jquery.min.js')}}"></script> 
    <script src="{{URL::asset('resources/assets/gente-admin/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('resources/assets/gente-admin/js/jquery-ui/jquery-ui.min.js')}}"></script>
    <script>
       // NProgress.start();
       var baseUrl = '<?=URL::to('/') ?>';
    </script>   
    <script src="{{URL::asset('resources/assets/gente-admin/js/nprogress.js')}}"></script> 
    <!--<script src="{{URL::asset('resources/assets/antarid/js/app.js')}}"></script>-->
    <!--<script src="{{URL::asset('resources/assets/holdonjs/HoldOn.min.js')}}"></script>-->
    <script src="{{ URL::asset('resources/assets/js/script.js') }}"></script>
</head>


<body class="nav-md">

    <div class="container body">
        <div class="main_container">       
        

        
