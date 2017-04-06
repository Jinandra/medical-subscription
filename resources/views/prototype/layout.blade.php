<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script>
    $(function () {
      $('[data-toggle="popover"]').popover()
    })
  </script>
    <!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
		<!-- Modal -->
	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
		<div class="modal-dialog" role="document">
            <form class="form-horizontal" method="post" action="{{ url('user/login') }}">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    					<h4 class="modal-title" id="loginModalLabel">Login</h4>
    				</div>
    				<div class="modal-body">

                            @if (Input::get('modal')=='login')                    
                                @if (count($errors) > 0)                    
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if (Session::has('error_auth'))
                                    <div class="alert alert-danger">
                                        <ul>
                                            <li>{{ Session::get('error_auth') }}</li>                                        
                                        </ul>
                                    </div>
                                @endif
                            @endif
    					
    						<div class="form-group">
    							<label for="screenName" class="col-sm-3 control-label">Screen Name</label>
    							<div class="col-sm-9">
    								<input type="text" class="form-control" id="screenName" name="screen_name" placeholder="Screen Name">
    							</div>
    						</div>
    						<div class="form-group">
    							<label for="password" class="col-sm-3 control-label">Password</label>
    							<div class="col-sm-9">
    								<input type="password" class="form-control" id="password" name="password" placeholder="Password">
    							</div>
    						</div>
    					
    					<div class="row">
              	<div class="col-md-6 col-md-offset-3"><a href="#">Forgot Password?</a></div>
            	</div>
    				</div>
    				<div class="modal-footer">
    					<input value="Sign In" class="btn btn-primary text-center" type="submit" id="signin" />
    				</div>
    			</div>
                {{ csrf_field() }}
            </form>
		</div>
	</div>

    <!-- Modal -->
  <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerLabel">
    <div class="modal-dialog" role="document">
    <form class="form-horizontal" method="post" action="{{ url('user/register') }}">

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="registerLabel">Register</h4>
        </div>
        <div class="modal-body">
                
                @if (Input::get('modal')=='register')                    
                    @if (count($errors) > 0)                    
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
                
            
                <div class="form-group">              
					<label for="screenName" class="col-sm-4 control-label">Screen Name*</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="screenName" name="screen_name" placeholder="Screen Name" required>
                    </div>
                </div>

                <div class="form-group">              
					<label for="password" class="col-sm-4 control-label">Password*</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                </div>

				<div class="form-group">              
					<label for="confirmPassword" class="col-sm-4 control-label">Confirm Password*</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
                </div>

				<div class="form-group">              
                    <label for="email" class="col-sm-4 control-label">Email Address</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                </div>
            
        </div>
        <div class="modal-footer">          
          <input value="Register" class="btn btn-primary text-center" type="submit" id="submit" />
        </div>
            {{ csrf_field() }}
        </form>
      </div>
    </div>
  </div>

		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<a class="navbar-brand" href="{{ url('/') }}">
						<img alt="EnfoLink" src="http://s3.amazonaws.com/enfolink/img/enfoLinkLogo.png">
					</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<form class="navbar-form navbar-left" role="search" method="get" action="{{ url('/search') }}">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search" name="s">
						</div>
						<button type="button" class="btn btn-default">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</button>
					</form>
					<ul class="nav navbar-nav">
        		<li><a href="#">Filter</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><button type="button" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#loginModal">Login</a></li>
						<li><button type="button" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#registerModal">Register</a></li>
					</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
	@yield('content')

		
    <!-- Include all compiled plugins (below), or include individual files as needed -->
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script type="text/javascript">
        @if (Input::get('modal')=='register')                    
            $('#registerModal').modal('show');
        @endif

        @if (Input::get('modal')=='login')                    
            $('#loginModal').modal('show');
        @endif
    </script>
	</body>
</html>
