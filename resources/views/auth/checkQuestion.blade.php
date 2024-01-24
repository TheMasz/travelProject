<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>โปรดตรวจสอบคำถาม</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/resetPassword.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <div class="reset-container min-h-100">
        <div class="content-wrap">
            <form action="/checkQuiz" method="post">
                @csrf
                <h1 class="mb-1 b-800">รีเซ็ตรหัสผ่าน</h1>
                <p class="font-sm"></p>
                @if (session('warning'))
                    <div class="message message-warning">
                        {{ session('warning') }}
                    </div>
                @endif
                <div class="input-wrap row mb-4">
                    <span class="material-icons"> quiz </span>
                    <input type="text" placeholder="{{ $questionTexts[0] }}" name="question" disabled />
                </div>
                <div class="input-wrap row mb-4">
                    <span class="material-icons"> question_answer </span>
                    <input type="text" value="{{ $member_id }}" name="member_id" hidden />
                    <input type="text" value="{{ $email }}" name="email" hidden />
                    <input type="text" placeholder="คำตอบ" name="answer" />
                </div>

                <button type="submit" class="btn-primary">ยืนยัน</button>
            </form>
        </div>
    </div>
</body>

</html>
