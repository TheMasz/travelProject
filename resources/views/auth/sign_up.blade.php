<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>สมัครสมาชิก</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>
    <div class="bg-gray min-h-100">
        <div class="container row align-center min-h-100">
            <div class="cont-lt w-50">
                <img src="/images/image1.png" alt="Image by pikisuperstar on Freepik">
            </div>
            <div class="cont-rt w-50 px-5">
                <!-- message alert -->
                @if (session('error'))
                    <div class="message message-error">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('warning'))
                    <div class="message message-warning">
                        {{ session('warning') }}
                    </div>
                @endif
                <h1 class="mb-2 b-800">สมัครสมาชิก</h1>
                <form action="register" method="post">
                    @csrf
                    <div class="input-wrap row mb-4">
                        <span class="material-icons">person</span>
                        <input type="email" placeholder="อีเมลของคุณ" name="email" required />
                    </div>
                    <div class="input-wrap row mb-4">
                        <span class="material-icons">password</span>
                        <div class="row password">
                            <input type="password" placeholder="รหัสผ่านของคุณ" id="password" name="password" required
                                minlength="8" maxlength="16" />
                            <button class="visibilityIcon" type="button" onclick="togglePassword('password')">
                                <span class="material-icons">
                                    visibility
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="input-wrap row mb-4">
                        <span class="material-icons">password</span>
                        <div class="row cf_password">
                            <input type="password" placeholder="ยืนยันรหัสผ่านของคุณ" id="cf_password"
                                name="cf_password" required minlength="8" maxlength="16" />
                            <button class="visibilityIcon" type="button" onclick="togglePassword('cf_password')">
                                <span class="material-icons">
                                    visibility
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="input-wrap row mb-4">
                        <span class="material-icons">quiz</span>
                        <select name="question" id="question">
                            @foreach ($questions as $question)
                                <option value="{{ $question->question_id }}">{{ $question->question_text }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-wrap row mb-4">
                        <span class="material-icons">question_answer</span>
                        <input type="text" placeholder="คำคอบของคุณ" name="answer" required />
                    </div>
                    <button type="submit" class="mb-4 btn-primary">สร้างบัญชี</button>
                </form>
                <p>
                    หากคุณมีบัญชีแล้ว?
                    <span><a class="c-pri" href="/signin">คลิก</a></span>
                </p>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const currentType = passwordInput.type;
            const icon = document.querySelector(`.${inputId} .visibilityIcon`);

            passwordInput.type = (currentType === 'password') ? 'text' : 'password';


            if (currentType === 'password') {
                icon.innerHTML = `<span class="material-icons">visibility_off</span>`

            } else {
                icon.innerHTML = `<span class="material-icons">visibility</span>`
            }
        }
    </script>
</body>

</html>
