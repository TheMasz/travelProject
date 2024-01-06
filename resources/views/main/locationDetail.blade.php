<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/locationDetail.css') }}">
    <script src="https://api.longdo.com/map/?key=7de1ec7f25b9980ac90ec2457c427a3e"></script>
    <title>{{ $location_detail->location_name }}</title>
    <script>
        var map, latitude, longitude;


        document.addEventListener('DOMContentLoaded', () => {
            latitude = document.getElementById('map').getAttribute('data-latitude');
            longitude = document.getElementById('map').getAttribute('data-longitude');
        });

        function init() {
            map = new longdo.Map({
                placeholder: document.getElementById('map')
            });
            var marker = new longdo.Marker({
                lon: parseFloat(longitude),
                lat: parseFloat(latitude)
            });
            map.Overlays.add(marker);
            map.location({
                lon: longitude,
                lat: latitude
            }, true);
            map.zoom(12, true);

        }
    </script>
</head>

<body onload="init();">
    <x-loading />
    <x-navbar />
    <div class="container">
        <div class="images-wrap">
            <div class="main-image">
                <div class="image"></div>
                <div class="credit">credit: {{ $location_detail->credit }}</div>
            </div>
            <div class="image-box">
                @php
                    $images = explode(', ', $location_detail->Images);
                @endphp
                @foreach ($images as $img)
                    <div class="image" style="background: url({{ asset('storage/images/locations/' . $img) }});"
                        data-img="{{ $img }}">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="details-wrap">
            <div class="title row">
                <div class="txt">
                    <div class="row align-center">
                        <h1>{{ $location_detail->location_name }}</h1>
                        <div class="opening-status mx-1" id="opening-status-{{ $location_detail->location_id }}"></div>
                        <script>
                            window.addEventListener('DOMContentLoaded', (e) => {
                                e.preventDefault();
                                checkOpeningStatus({{ $location_detail->location_id }});
                                setInterval(() => {
                                    checkOpeningStatus({{ $location_detail->location_id }});
                                }, 60000);
                            });
                        </script>
                    </div>

                    <div class="row">
                        <span class="material-icons">
                            place
                        </span>
                        <p class="address">{{ $location_detail->address }}</p>
                    </div>
                </div>
                <div class="button-wrap">
                    <button class="btn-secondary row align-center"
                        onclick="addPlan({{ $location_detail->location_id }},event)">
                        <span class="material-icons mr-05">
                            luggage
                        </span>
                        เพิ่มลงทริป
                    </button>
                </div>
            </div>
            <div class="row desc-wrap">
                <div class="detail">
                    <p style="text-align: justify;">{{ $location_detail->detail }}</p>
                    <div class="time">
                        <p>ช่วงเวลาเปิด-ปิด</p>
                        <p>{{ $location_detail->s_time }} - {{ $location_detail->e_time }} น.</p>
                    </div>
                    <div class="categories row py-1 flex-wrap">
                        @foreach (explode(',', $location_detail->Preferences) as $pref)
                            <div>{{ $pref }}</div>
                        @endforeach
                    </div>
                </div>
                <div class="map" id="map" data-latitude="{{ $location_detail->latitude }}"
                    data-longitude="{{ $location_detail->longitude }}">
                </div>
            </div>
        </div>
        <div class="reviews-wrap">
            <button class="btn-secondary row align-center btn_review">
                <span class="material-icons mr-05">
                    add
                </span>
                เขียนรีวิว
            </button>
            <div class="reviews">
                <input type="hidden" value="{{ session('member_id') }}" id="member_session">
                @if (count($reviews) > 0)
                    @foreach ($reviews as $review)
                        <div class="review">
                            <div class="user">
                                <div class="row align-center">
                                    <div class="avatar avatar-sm">
                                        @if ($review->member_img)
                                            <img src="{{ asset('storage/images/members/' . session('member_id') . '/' . $review->member_img) }}"
                                                alt="profile">
                                        @else
                                            <h4> {{ substr($review->username, 0, 1) }}</h4>
                                        @endif
                                    </div>
                                    <h4>{{ $review->username }}</h4>
                                </div>
                                <div class="star">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < $review->rating)
                                            <span class="material-icons">star</span>
                                        @else
                                            <span class="material-icons">star_border</span>
                                        @endif
                                    @endfor

                                </div>
                                <p class="date">{{ app('compareTime')($review->created_at) }}</p>
                                <p class="date"> {{ $review->created_at }}</p>
                            </div>
                            <div class="txt">
                                {{ $review->review }}
                            </div>
                            <div class="like_section like_section{{ $review->review_id }}">
                                @if ($review->liked_by_current_member > 0)
                                    <button type="button" class="btn_like"
                                        onclick="likeActions({{ $review->review_id }}, 'unlike')">
                                        <span class="material-icons" style="color: red;">favorite</span>
                                        <p></p>
                                    </button>
                                @else
                                    <button type="button" class="btn_like"
                                        onclick="likeActions({{ $review->review_id }}, 'like')">
                                        <span class="material-icons" style="color: red;">favorite_border</span>
                                        <p></p>
                                    </button>
                                @endif

                                <p>{{ $review->like_count }} ถูกใจ</p>
                            </div>
                            @if (session('member_id') == $review->member_id)
                                <div class="more">
                                    <button class="del-review" onclick="delAction({{ $review->review_id }})">
                                        <span class="material-icons">delete</span>
                                    </button>
                                </div>
                            @endif

                        </div>
                    @endforeach
                @else
                    <div class="no-reviews">
                        <span class="material-icons md-48">
                            forum
                        </span>
                        <p>+++ไม่มีการแสดงความคิด เริ่มที่คุณเลย+++</p>
                    </div>
                @endif

            </div>
            <button type="button" class="@if (count($reviews) == 0) disable @endif" id="loadMoreButton"
                data-locationId="{{ $location_detail->location_id }}"
                @if (count($reviews) == 0) disabled @endif>ดูเพิ่มเติม</button>
        </div>
    </div>
    <div class="modal modal-reviews">
        <div class="modal_content modal_content-reviews">
            <div class="content_wrap" style="overflow-y: hidden">
                <h3>แชร์ประสบการณ์ของคุณ</h3>
                <form class="form_reviews">
                    @csrf
                    <div class="rating">
                        <input type="number" name="rating" hidden>
                        <span class="material-icons star" data-value="1">star_border</span>
                        <span class="material-icons star" data-value="2">star_border</span>
                        <span class="material-icons star" data-value="3">star_border</span>
                        <span class="material-icons star" data-value="4">star_border</span>
                        <span class="material-icons star" data-value="5">star_border</span>
                    </div>
                    <input type="text" name="location_id" value="{{ $location_detail->location_id }}" hidden>
                    <textarea name="review_txt" cols="30" rows="7" placeholder="เล่าเรื่องราวของคุณ..."></textarea>
                    <div class="btn-group">
                        <button type="button" class="cancel_btn cancel_btn-reviews">ยกเลิก</button>
                        <button type="submit" class="submit_btn-reviews btn-secondary">ยืนยัน</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <x-confirm />
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/locationDetail.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>

</html>
