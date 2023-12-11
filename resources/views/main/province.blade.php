<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $province_name }}</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/province.css') }}">
</head>

<body>
    <x-loading />
    <x-navbar />
    <div class="container">
        <div class="banner-wrap">
            <div class="banner">
                <div class="banner-content">
                    <h1>{{ $province_name }}</h1>
                </div>
            </div>
            <div class="filters">
                --filters--
            </div>
        </div>
        <div class="main-wrap">
            <div class="locations-wrap">
                @foreach ($paginatedLocations as $location)
                    <div class="card">
                        @php
                            $images = explode(', ', $location->Images);
                        @endphp
                        <div class="card-image">
                            <a href="/province/{{ $province_id }}/{{ $location->location_id }}">
                                <div class="overlay">
                                    <span class="material-icons">
                                        zoom_out_map
                                    </span>
                                </div>
                                <img src="{{ asset('storage/images/' . $images[0]) }}"
                                    alt="{{ $location->location_name }}">
                            </a>
                        </div>
                        <div class="card-content">
                            <div class="row align-center flex-between">
                                <h3>
                                    <a href="/province/{{ $province_id }}/{{ $location->location_id }}">
                                        {{ $location->location_name }}
                                    </a>
                                </h3>
                                <div class="opening-status" id="opening-status-{{ $location->location_id }}"></div>
                                <script>
                                    function checkOpeningStatus(location_id) {
                                        fetch(`/api/checkOpening/${location_id}`)
                                            .then(response => response.json())
                                            .then(data => {
                                                const statusElement = document.getElementById(`opening-status-${location_id}`);
                                                if (data.status == 'opend') {
                                                    statusElement.textContent = 'เปิดทำการ';
                                                    statusElement.style.backgroundColor = '#28A745';
                                                } else {
                                                    statusElement.textContent = 'ปิดทำการ';
                                                    statusElement.style.backgroundColor = '#DC3545';
                                                }
                                            })
                                            .catch(error => console.error('Error:', error));
                                    }

                                    window.addEventListener('DOMContentLoaded', (e) => {
                                        e.preventDefault();
                                        checkOpeningStatus({{ $location->location_id }});
                                        setInterval(() => {
                                            checkOpeningStatus({{ $location->location_id }});
                                        }, 60000);
                                    });
                                </script>
                            </div>
                            <p>{{ app('maxLength')($location->detail) }}...</p>
                            <div class="time">
                                <p>ช่วงเวลาเปิด-ปิด</p>
                                <p>{{ $location->s_time }} - {{ $location->e_time }} น.</p>
                            </div>
                            <button class="btn-secondary row align-center"
                                onclick="addPlan({{ $location->location_id }},event)">
                                <span class="material-icons">
                                    luggage
                                </span>
                                เพิ่มลงทริป
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="suggest-plans">
                <div class="plans-container">
                    <h4>แผนท่องเที่ยวที่แนะนำ</h4>
                    @foreach (app('getPlansByPref')(session('member_id')) as $plan)
                        @php
                            $locationIdsString = $plan['locations_id'];
                            $locationIdsArray = explode(',', $locationIdsString);
                            $locationIdsArray = array_map('intval', $locationIdsArray);
                        @endphp
                        <div class="plan">
                            <div class="image">
                                <img src="" alt="img">
                            </div>
                            <div class="details">
                                <div class="row flex-between">
                                    <div class="head">
                                        <h5>{{ $plan['plan_name'] }}</h5>
                                        <p>โดย {{ app('getUsername')($plan['member_id']) }}</p>
                                    </div>
                                    <button
                                        onclick="usePlan('{{ str_replace(' ', '', $locationIdsString) }}', '.plans-container h4')">ใช้แผนนี้</button>
                                </div>
                                <div>

                                    <ul>
                                        @foreach (app('getLocations')($locationIdsArray) as $location)
                                            <li> <a
                                                    href="/province/{{ $location->province_id }}/{{ $location->location_id }}">{{ $location->location_name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="pagination-wrap">
            <div class="pagination">
                @if ($page > 1)
                    <a href="?page={{ $page - 1 }}" class="pn_btn">
                        <span class="material-icons">
                            arrow_back_ios
                        </span>
                    </a>
                @endif

                @for ($i = 1; $i <= ceil($countLocations / $perPage); $i++)
                    @if ($i == 1 || $i == $page || $i == ceil($countLocations / $perPage))
                        <a href="?page={{ $i }}"
                            @if ($i == $page) class="active" @endif>{{ $i }}</a>
                    @elseif(abs($i - $page) < 3)
                        <a href="?page={{ $i }}">{{ $i }}</a>
                    @elseif(abs($i - $page) == 3)
                        <span>...</span>
                    @endif
                @endfor

                @if ($page < ceil($countLocations / $perPage))
                    <a href="?page={{ $page + 1 }}" class="pn_btn">
                        <span class="material-icons">
                            arrow_forward_ios
                        </span>
                    </a>
                @endif
            </div>
        </div>



    </div>
</body>

<script src="{{ asset('js/app.js') }}"></script>

</html>
