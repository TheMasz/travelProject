<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <title>โปรไฟล์ของฉัน</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

</head>

<body>
    <x-loading />
    <x-navbar />
    <div class="container">
        <div class="myprofile">
            <div class="row">
                <div class="avatar avatar-lg">
                    @if ($member->member_img)
                        <img src="{{ asset('storage/images/members/' . session('member_id') . '/' . $member->member_img) }}"
                            alt="profile">
                    @else
                        <h4> {{ substr($member->username, 0, 1) }}</h4>
                    @endif
                </div>
                <div class="details">
                    <div class="row align-center mb-2">
                        <h4> {{ app('getUsername')(session('member_id')) }}</h4>
                        <button class="btn-primary btn_profile">แก้ไขโปรไฟล์</button>
                    </div>
                    <div class="row align-center">
                        <p>รีวิวทั้งหมด {{ $countReviews }}</p>
                        <p>แผนการท่องเที่ยว {{ $countPlans }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="myreviews">
            <h4>ประสบการณ์ท่องเที่ยวของคุณในอดีต</h4>
            <div class="filter">
                <label for="filterDropdown">เรียงจาก: </label>
                <select id="filterDropdown">
                    <option value="desc" selected>ใหม่-เก่า</option>
                    <option value="asc">เก่า-ใหม่</option>
                </select>
            </div>

            @if (count($reviews) > 0)
                <div class="reviews">
                    @foreach ($reviews as $review)
                        <div class="review_card">
                            <div class="row flex-between">
                                <div class="location">
                                    <div class="avatar avatar-md">
                                        @if ($member->member_img)
                                            <img src="{{ asset('storage/images/members/' . session('member_id') . '/' . $member->member_img) }}"
                                                alt="profile">
                                        @else
                                            <h4> {{ substr($member->username, 0, 1) }}</h4>
                                        @endif
                                    </div>
                                    <div class="desc">
                                        <a href="/province/{{ $review->province_id }}/{{ $review->location_id }}">
                                            <h4>{{ $review->location_name }}</h4>
                                        </a>
                                        <p>{{ app('compareTime')($review->created_at) }}</p>
                                        <p>{{ $review->created_at }}</p>
                                    </div>
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
                            </div>
                            <div class="review">
                                <p>{{ $review->review }}</p>
                            </div>
                            <button class="del-review" onclick="delAction({{ $review->review_id }})">
                                <span class="material-icons">delete</span>
                            </button>
                        </div>
                    @endforeach
                </div>
                <div class="row flex-center">
                    <button id="loadMoreButton" class="btn-secondary ">ดูเพิ่มเติม</button>
                </div>
            @else
                <div class="no-reviews">
                    <span class="material-icons md-48">
                        forum
                    </span>
                    <p>คุณยังไม่เคยแชร์ประสบการท่องเที่ยวเลย</p>
                    <p class="font-sm">--- ลองเริ่มต้นแชร์ประสบการณ์ท่องเที่ยวเพื่อประสบการณ์แนะนำสถานที่ที่ดียิ่งขึ้น
                        ---</p>
                </div>
            @endif


        </div>
    </div>

    <div class="modal modal-profile">
        <div class="modal_content modal_content-profile">
            <div class="content_wrap" style="overflow: hidden">
                <button class="cancel_btn">ปิด</button>
                <div class="menu">
                    <button id="editProfileBtn">แก้ไขโปรไฟล์</button>
                    <button id="editPreferencesBtn">แก้ไขความชอบ</button>
                </div>
                <div class="overlay" id="overlay"  >
                    {{-- change profile --}}
                    <div class="overlay-content" id="profileContent">
                        <form id="formMember" method="POST" action="/api/editProfile" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-1">
                                <div class="avatar avatar-md">
                                    @if ($member->member_img)
                                        <img src="{{ asset('storage/images/members/' . session('member_id') . '/' . $member->member_img) }}"
                                            alt="profile">
                                    @else
                                        <h4> {{ substr($member->username, 0, 1) }}</h4>
                                    @endif
                                </div>
                                <button class="ch_pic_pf" type="button">
                                    เปลี่ยนรูปโปรไฟล์
                                    <input type="file" name="profile_pic" accept="image/*">
                                </button>
                            </div>
                            <div class="input_wrap">
                                <p>ชื่อผู้ใช้</p>
                                <input type="text" name="username" value="{{ $member->username }}">
                            </div>

                            <div class="input_wrap">
                                <p for="">อีเมล</p>
                                <input type="email" disabled name="email" value="{{ $member->email }}">
                            </div>
                            <div class="input_wrap">
                                <p>รหัสผ่านเก่า</p>
                                <input type="password" name="old_password" placeholder="********">
                            </div>
                            <div class="input_wrap">
                                <p>รหัสผ่านใหม่ของคุณ</p>
                                <input type="password" name="new_password" placeholder="********">
                            </div>
                            <button type="submit" class="btn-primary" style="float: right">บันทึก</button>
                        </form>
                    </div>
                    {{-- change prefs --}}
                    <div class="overlay-content py-1" style="overflow: auto; height: 100%" 
                    id="preferencesContent">
                        <form id="formPref" method="POST" action="/api/editPref" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <h2>แก้ไขความชอบของคุณ</h2>
                            <p class="font-sm mb-1">การแก้ไชนี้อาจส่งผลให้การแนะนำสถานที่เปลี่ยน</p>
                            <ul>
                                @foreach ($preferences as $preference)
                                    <li>
                                        <p>{{ $preference->preference_name }}</p>
                                        <div class="row py-02">
                                            <label class="preference-label">
                                                <input type="radio"
                                                    name="preference_{{ $preference->preference_id }}" value="1"
                                                    class="hidden-checkbox"
                                                    @if ($preference->score == 1) checked @endif>
                                                <span class="material-icons icon worst">
                                                    sentiment_very_dissatisfied
                                                </span>
                                            </label>
                                            <label class="preference-label">
                                                <input type="radio"
                                                    name="preference_{{ $preference->preference_id }}" value="2"
                                                    class="hidden-checkbox"
                                                    @if ($preference->score == 2) checked @endif>
                                                <span class="material-icons icon bad">
                                                    sentiment_dissatisfied
                                                </span>
                                            </label>
                                            <label class="preference-label">
                                                <input type="radio"
                                                    name="preference_{{ $preference->preference_id }}" value="3"
                                                    class="hidden-checkbox"
                                                    @if ($preference->score == 3) checked @endif>
                                                <span class="material-icons icon avg">
                                                    sentiment_neutral
                                                </span>
                                            </label>
                                            <label class="preference-label">
                                                <input type="radio"
                                                    name="preference_{{ $preference->preference_id }}" value="4"
                                                    class="hidden-checkbox"
                                                    @if ($preference->score == 4) checked @endif>
                                                <span class="material-icons icon good">
                                                    sentiment_satisfied
                                                </span>
                                            </label>
                                            <label class="preference-label">
                                                <input type="radio"
                                                    name="preference_{{ $preference->preference_id }}" value="5"
                                                    class="hidden-checkbox"
                                                    @if ($preference->score == 5) checked @endif>
                                                <span class="material-icons icon best">
                                                    sentiment_very_satisfied
                                                </span>
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                                <button type="submit" class="btn-primary py-1" style="float: right">บันทึก</button>
                            </ul>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <x-confirm />

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" defer></script>
</body>

</html>
