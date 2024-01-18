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
            @php
                if (count($paginatedLocations) > 0) {
                    $maxIndex = count($paginatedLocations) - 1;
                    $random = rand(0, $maxIndex);
                    $imgs = $paginatedLocations[$random]->Images;
                    $banner = explode(', ', $imgs);
                } else {
                    $banner = [];
                }
            @endphp
            <div class="banner"
                style="
            @if (!empty($banner)) background: url({{ asset('storage/images/locations/' . $banner[0]) }});
            @else
                background-color: #555; @endif
        ">
                <div class="banner-content">
                    <h1>{{ $province_name }}</h1>
                </div>
            </div>
            <div class="filters">
                <div class="btns-pref">
                    @foreach ($allPrefs as $pref)
                        <button data-prefId="{{ $pref->preference_id }}"
                            @if (isset($selectedPreferences) && in_array($pref->preference_id, $selectedPreferences)) class="active" @endif>
                            {{ $pref->preference_name }}
                        </button>
                    @endforeach
                </div>
                <div class="btns-confirm">
                    <button class="clear-btn">ล้างทั้งหมด</button>
                    <button class="confirm-btn">ยืนยัน</button>
                </div>

            </div>
        </div>
        <div class="main-wrap">
            <div class="locations-wrap">
                @if (count($paginatedLocations) > 0)
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
                                    <img src="{{ asset('storage/images/locations/' . $images[0]) }}"
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

                                <div class="row align-center flex-between">
                                    <div class="categories row">
                                        @php
                                            $prefs = explode(', ', $location->Preferences);
                                        @endphp

                                        @foreach ($prefs as $pref)
                                            @php
                                                $isActive = false;
                                                if (isset($selectedPreferences)) {
                                                    $findPrefId = App::make('findPrefId');
                                                    $id = $findPrefId($pref);
                                                    $isActive = in_array($id, $selectedPreferences);
                                                }
                                            @endphp

                                            <div
                                                class="category @if ($isActive) category-active @endif">
                                                {{ $pref }}
                                            </div>
                                        @endforeach

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
                        </div>
                    @endforeach
                @else
                    <div class="no-locations">
                        <span class="material-icons md-36" style="color: gray; margin-bottom: 10px;">
                            do_not_disturb_alt
                        </span>
                        <p class="font-sm">ยังไม่พร้อมใช้งานในจังหวัดนี้</p>
                    </div>
                @endif

            </div>
            <div class="suggest-plans">
                <div class="plans-container">
                    <h4>แผนท่องเที่ยวที่แนะนำ</h4>
                    @php
                        $plans = app('getPlansByPref')(session('member_id'));
                    @endphp
                    @if (count($plans) > 0)
                        @foreach ($plans as $plan)
                            @php
                                $locationIdsString = $plan['locations_id'];
                                $locationIdsArray = explode(',', $locationIdsString);
                                $locationIdsArray = array_map('intval', $locationIdsArray);
                            @endphp
                            <div class="plan">
                                <div class="image">
                                    <div class="avatar avatar-sm">
                                        @if (!empty(app('getMemberImg')($plan['member_id'])))
                                            <img src="{{ asset('storage/images/members/' . $plan['member_id'] . '/' . app('getMemberImg')($plan['member_id'])) }}"
                                                alt="{{ app('getUsername')($plan['member_id']) }}">
                                        @else
                                            <h4> {{ substr(app('getUsername')($plan['member_id']), 0, 1) }}</h4>
                                        @endif
                                    </div>
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
                    @else
                        <div class="no-plans">
                            <span class="material-icons" style="color: #ccc">
                                luggage
                            </span>
                            <p class="font-sm">ไม่มีแผนการท่องเที่ยวที่แนะนำ</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if ($countLocations !== 0)
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
        @endif

    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" defer></script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/province.js') }}"></script>
</body>

</html>
