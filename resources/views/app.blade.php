<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>My Finance</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <link href='/assets/jquery-ui-1.11.4/jquery-ui.min.css' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="/assets/html5shiv.min.js"></script>
		<script src="/assets/respond.min.js"></script>
	<![endif]-->

    <!-- Scripts -->
    <script src="/assets/jquery.min.2.1.3.js"></script>

    <script src="/assets/jquery-ui-1.11.4/jquery-ui.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/assets/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="/assets/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="/assets/bootstrap.min.js"></script>

</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                @if (!Auth::guest())
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="{{ URL::route('home') }}">Home</a></li>
                    <li><a href="{{ URL::route('fileUpload') }}">New file</a></li>
                    <li><a href="{{ URL::route('files') }}">My Files</a></li>
                    <li><a href="{{ URL::route('categories') }}">Categories</a></li>
                    <li><a href="{{ URL::route('transactions') }}">Transactions</a></li>
                    <li><a href="{{ URL::route('transactions_from_storage') }}">Report</a></li>
                </ul>
                @endif


				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

</body>
</html>
