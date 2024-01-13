<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>เข้าสู่ระบบ</title>
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
                <h1 class="mb-2 b-800">เข้าสู่ระบบ</h1>
                <form action="login" method="post">
                    @csrf
                    <div class="input-wrap row mb-4">
                        <span class="material-icons"> person </span>
                        <input type="email" placeholder="อีเมลของคุณ" name="email" required />
                    </div>
                    <div class="input-wrap row mb-4">
                        <span class="material-icons"> password </span>
                        <div class="row password">
                            <input type="password" placeholder="รหัสผ่านของคุณ" name="password" id="password"
                                minlength="8" maxlength="16" required />
                            <button class="visibilityIcon" type="button" onclick="togglePassword('password')">
                                <span class="material-icons">
                                    visibility
                                </span>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="mb-4 btn-primary b-400">
                        เข้าสู่ระบบ
                    </button>
                </form>
                <p class="b-400">
                    หากคุณยังไม่มีบัญชี?
                    <span><a class="c-pri" href="/signup">คลิก</a></span>
                </p>
                <a class="font-sm" href="/resetPassword">ลืมรหัสผ่าน?</a>
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
