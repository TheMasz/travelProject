<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Signup</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/index.css') }}">



</head>

<body>
  <div class="bg-gray min-h-100">
    <div class="container row align-center min-h-100">
      <div class="cont-lt w-50"></div>
      <div class="cont-rt w-50 px-5">
        <!-- message alert -->
        @if(session('error'))
        <div class="message message-error">
          {{session('error')}}
        </div>
        @endif
        @if(session('warning'))
        <div class="message message-warning">
          {{session('warning')}}
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
            <input type="password" placeholder="รหัสผ่านของคุณ" name="password" required minlength="8" maxlength="16" />
          </div>
          <div class="input-wrap row mb-4">
            <span class="material-icons">password</span>
            <input type="password" placeholder="ยืนยันรหัสผ่านของคุณ" name="cf_password" required minlength="8" maxlength="16" />
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
</body>

</html>