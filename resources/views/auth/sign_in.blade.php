<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Signin</title>
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
        <h1 class="mb-2 b-800">เข้าสู่ระบบ</h1>
        <form action="login" method="post">
          @csrf
          <div class="input-wrap row mb-4">
            <span class="material-icons"> person </span>
            <input type="email" placeholder="Your email" name="email" required />
          </div>
          <div class="input-wrap row mb-4">
            <span class="material-icons"> password </span>
            <input type="password" placeholder="Your password" name="password" minlength="8" maxlength="16" required />
          </div>
          <button type="submit" class="mb-4 btn-primary b-400">
            เข้าสู่ระบบ
          </button>
        </form>
        <p class="b-400">
          หากคุณยังไม่มีบัญชี?
          <span><a class="c-pri" href="/signup">คลิก</a></span>
        </p>
      </div>
    </div>
  </div>
</body>

</html>