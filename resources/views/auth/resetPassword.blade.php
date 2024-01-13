<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>รีเซ็ตรหัสผ่าน</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/resetPassword.css') }}">
</head>

<body>
    <div class="reset-container min-h-100">
        <div class="content-wrap">
            <form action="/checkEmail" method="post">
                @csrf
                <h1 class="mb-1 b-800">รีเซ็ตรหัสผ่าน</h1>
                <p class="font-sm"></p>
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

                <div class="input-wrap row mb-4">
                    <span class="material-icons"> email </span>
                    <input type="email" placeholder="อีเมลของคุณ" name="email" required />
                </div>

                <button type="submit" class="btn-primary">ยืนยัน</button>
            </form>
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
