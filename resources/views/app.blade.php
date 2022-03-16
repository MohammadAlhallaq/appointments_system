<!DOCTYPE html>
<html>
<head>
    @include('partials.header')
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet"/>
    <script src="{{ mix('/js/app.js') }}" defer></script>
    @inertiaHead
</head>
<body class="g-sidenav-show bg-gray-200">
@inertia
@routes
</body>
</html>
