<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$province_name}}</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/province.css') }}">
</head>

<body>
    <x-navbar />
    <div class="container">
        <div class="banner-wrap">
            <div class="banner">
                <div class="banner-content">
                    <h1>{{$province_name}}</h1>
                </div>
            </div>
            <div class="filters">
                --filters--
            </div>
        </div>
        <div class="main-wrap">
            <div class="locations-wrap">
                @foreach(app('getLocationsByPref')($province_id,$member_id) as $location)
                <div class="card">
                    @php
                    $images = explode(", ", $location->Images);
                    @endphp
                    <div class="card-image">
                        <a href="/province/{{$province_id}}/{{$location->location_id}}">
                            <div class="overlay">
                                <span class="material-icons">
                                    zoom_out_map
                                </span>
                            </div>
                            <img src="{{ asset('storage/images/' . $images[0]) }}" alt="{{ $location->location_name }}">
                        </a>
                    </div>
                    <div class="card-content">
                        <h3>
                            <a href="/province/{{$province_id}}/{{$location->location_id}}">
                                {{$location->location_name}}
                            </a>
                        </h3>
                        <p>{{ app('maxLength')($location->detail) }}...</p>
                        <button class="btn-primary" onclick="addPlan({{$location->location_id}})">add</button>
                    </div>
                </div>

                @endforeach
            </div>
            <div class="suggest-plans">
                <div class="plans-container">
                    <div>plan</div>
                    <div>plan</div>
                    <div>plan</div>
                    <div>plan</div>
                </div>
            </div>
        </div>

    </div>
</body>

<script src="{{ asset('js/app.js') }}"></script>

</html>