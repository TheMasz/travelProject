<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/carousel.css') }}">

    <script src="
                                                                                                                        https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
                                                                                                                        ">
    </script>
    <link href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    " rel="stylesheet">


</head>

<body>
    <x-loading />
    <x-navbar />
    @if ($mypreferences)
        <x-firstPref :preferences="$preferences" />
    @endif
    <div class="container">
        <section class="splide">
            <h4>แนะนำสถานที่ท่องเที่ยวที่คุณอาจสนใจ</h4>
            <div class="splide__track">
                <ul class="splide__list">
                    @php
                        $chunkedLocations = array_chunk($locations, 3);
                    @endphp
                    @foreach ($chunkedLocations as $chunk)
                        <li class="splide__slide">
                            @foreach ($chunk as $location)
                                @php
                                    $images = explode(', ', $location->Images);
                                @endphp
                                <a href="/province/{{ $location->province_id }}/{{ $location->location_id }}">
                                    <div class="location"
                                        style="background: url('{{ asset('storage/images/locations/' . $images[0]) }}')">
                                        <div class="detail">
                                            <p>{{ $location->location_name }}</p>
                                            <p class="row align-center">
                                                <span class="material-icons">
                                                    place
                                                </span>
                                                จ.{{ $location->province_name }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        <div class="mt-2 wug-wrap">
            <div class="row flex-between py-02">
                <div class="row">
                    <span class="material-icons">
                        public
                    </span>
                    <h3>ไปไหนดี...</h3>
                </div>
                <div class="dropdown-toggle">
                    <span class="material-icons" id="dropdown-icon">
                        arrow_drop_down
                    </span>
                </div>
            </div>
            <div class="dropdown-menu">
                @foreach (app('getZoneswithProvince')() as $zone)
                    <div class="zone-group">
                        <div style="font-weight: bold;">{{ $zone->zone_name }}</div>
                        <div class="row flex-wrap">
                            @foreach ($zone->provinces as $province)
                                <a href='/province/{{ app('findProvinceId')($province) }}'>{{ $province }}</a>
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const wrap = document.querySelector('.wug-wrap');

            const toggle = wrap.querySelector('.dropdown-toggle');
            toggle.addEventListener('click', function(event) {
                event.preventDefault();
                const dropdownMenu = wrap.querySelector('.dropdown-menu');
                const dropdownIcon = document.getElementById('dropdown-icon');
                dropdownMenu.classList.toggle('open');
                if (dropdownMenu.classList.contains('open')) {
                    dropdownIcon.textContent = 'arrow_drop_up';
                } else {
                    dropdownIcon.textContent = 'arrow_drop_down';
                }
            });

            var splide = new Splide('.splide', {
                type: 'loop',
                drag: false,
                autoplay: true,
            });

            splide.mount();
        });
    </script>
</body>

</html>
