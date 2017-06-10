
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../favicon.ico">

    <title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<link rel="stylesheet" type="text/css" href={{ asset('/css/autocomplete.css') }}>
	{{--<script type="text/javascript" src={{ asset('/js/autocomplete.js') }}></script>--}}

	<!-- Custom styles for this template -->
	<link href="/css/app.css" rel="stylesheet">
</head>

<body>

	@include('layouts.navvv')

	{{-- Replaced with laracasts/flash package
	https://github.com/laracasts/flash
	@if( $flash = session('message'))
		<div id="flash-message" class="alert alert-success" role="alert">
			{{ $flash }}
		</div>
	@endif--}}
	<div class="container">
		@include('flash::message')
		{{--<p>This is my horse!</p>--}}
	</div>

	{{--<script>
        $('#flash-overlay-modal').modal();
	</script>--}}


	<div class="blog-header">
		<div class="container">
			<h1 class="blog-title">Yes Vote! (too big here)</h1>
			<p class="lead blog-description">Start your vote, among your friends, your team, your community, your city.</p>
		</div>
	</div>

	<div class="container">

		<div class="row">

			@yield('content')

			@include('layouts.sidebar')

		</div><!-- /.row -->

	</div><!-- /.container -->

	@include('layouts.footer')

</body>
</html>
