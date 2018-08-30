<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/style.css') }}

</head>

<body class="gray-bg">


<div class="middle-box text-center animated fadeInDown">
    <h1>404</h1>
    <h3 class="font-bold">Page Not Found</h3>

    <div class="error-desc">
        Sorry, but the page you are looking for has not been found. Try checking the URL for errors, then hit the refresh button on your browser.
    </div>
</div>

<script src="js/jquery-3.1.1.min.js"></script>

</body>
</html>
