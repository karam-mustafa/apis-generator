<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KM-LARAVEL API-GENERATOR</title>
    <!-- Fonts -->
    {{--    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">--}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.bootcdn.net/ajax/libs/hover.css/2.3.1/css/hover-min.css">
    <link href="https://fonts.googleapis.com/css2?family=Acme&display=swap" rel="stylesheet">
    <link href="{{asset('ApisGenerator/css/css.css')}}" rel="stylesheet">

</head>
<body id="body">
<div class="background" id="background">
    <div class="layer_1">
        <nav class="navbar">
            <div class="navbar_body">
                <ul class="nav h-100 center_elements_space_between w-100">
                    <li class="nav-item   hvr-bounce-to-top">
                        <a class="nav-link active" href="{{route("apisGenerator.index")}}">Home</a>
                    </li>
                    <li class="nav-item   hvr-bounce-to-top">
                        <a class="nav-link active" href="{{route("apisGenerator.create")}}">Create New</a>
                    </li>
                    <li class="nav-item   hvr-bounce-to-top">
                        <h4>KM-LARAVEL</h4>
                    </li>
                    <li class="nav-item   hvr-bounce-to-top">
                        <a class="nav-link active" href="{{route("apisGenerator.create")}}">Usage</a>
                    </li>
                    <li class="nav-item   hvr-bounce-to-top">
                        <a class="nav-link active" href="{{route("apisGenerator.create")}}">Docs</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('message'))
                <div class="alert alert-{{session('alert_type')}}">
                    {{session('message')}}
                </div>
            @endif
            @yield('content')
        </div>
        @yield('footer')
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<script src="{{asset('ApisGenerator/scripts/script.js')}}"></script>
</body>
</html>
