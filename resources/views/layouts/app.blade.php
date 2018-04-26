<html>
<head>
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset("/css/app.css") }}">
</head>
<body>

@include('nav')

<div class="container">
    @yield('content')
</div>

    <script href="{{ asset("/css/app.js") }}"></script>
</body>
</html>