<!DOCTYPE html>
<html lang="zxx">
	<head>
		<title>Smart Score</title>
		<meta charset="UTF-8">
		<meta name="description" content="Smart Score">
		<meta name="keywords" content="smart, score, platform, skateboarding, competition">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Favicon -->   
		<link href="img/favicon.ico" rel="shortcut icon"/>

		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i" rel="stylesheet">

		<!-- Stylesheets -->
		<link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}?<?php echo time(); ?>"/>
		<link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}?<?php echo time(); ?>"/>
		<link rel="stylesheet" href="{{ URL::asset('css/owl.carousel.css') }}?<?php echo time(); ?>"/>
		<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}?<?php echo time(); ?>"/>
		<link rel="stylesheet" href="{{ URL::asset('css/animate.css') }}?<?php echo time(); ?>"/>


		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

	</head>
	<body>

        @include('layouts._site._menu')

		@yield('content')

		@include('layouts._site._footer')

		@include('sweetalert::alert')

		<!--====== Javascripts & Jquery ======-->
		<script src="{{ URL::asset('js/jquery-3.2.1.min.js') }}?<?php echo time(); ?>"></script>
		<script src="{{ URL::asset('js/jquery.mask.min.js') }}?<?php echo time(); ?>"></script>
		<script src="{{ URL::asset('js/bootstrap.min.js') }}?<?php echo time(); ?>"></script>
		<script src="{{ URL::asset('js/owl.carousel.min.js') }}?<?php echo time(); ?>"></script>
		<script src="{{ URL::asset('js/jquery.marquee.min.js') }}?<?php echo time(); ?>"></script>
		<script src="{{ URL::asset('js/main.js') }}?<?php echo time(); ?>"></script>
	</body>
</html>