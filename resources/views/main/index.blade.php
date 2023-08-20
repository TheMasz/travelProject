<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Homepage</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/index.css') }}">
  <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
</head>

<body style="background-color: #F7F8FC;">
  <x-navbar />
  @if ($mypreferences)
  <div class="modal">
    <div class="modal_content">
      <div class="content_wrap">
        <form action="addPref" method="post">
          @csrf
          <div class="top">
            <h3>โปรดบอกเราว่าคุณชอบอะไร</h3>
          </div>
          <div class="center">
            <ul>
              @foreach($preferences as $preference)
              <li>
                <p>{{$preference->preference_name}}</p>
                <div class="row py-02">
                  <label class="preference-label">
                    <input type="radio" name="preference_{{$preference->preference_id}}" value="1" class="hidden-checkbox">
                    <span class="material-icons icon worst">
                      sentiment_very_dissatisfied
                    </span>
                  </label>
                  <label class="preference-label">
                    <input type="radio" name="preference_{{$preference->preference_id}}" value="2" class="hidden-checkbox">
                    <span class="material-icons icon bad">
                      sentiment_dissatisfied
                    </span>
                  </label>
                  <label class="preference-label">
                    <input type="radio" name="preference_{{$preference->preference_id}}" value="3" class="hidden-checkbox">
                    <span class="material-icons icon avg">
                      sentiment_neutral
                    </span>
                  </label>
                  <label class="preference-label">
                    <input type="radio" name="preference_{{$preference->preference_id}}" value="4" class="hidden-checkbox">
                    <span class="material-icons icon good">
                      sentiment_satisfied
                    </span>
                  </label>
                  <label class="preference-label">
                    <input type="radio" name="preference_{{$preference->preference_id}}" value="5" class="hidden-checkbox">
                    <span class="material-icons icon best">
                      sentiment_very_satisfied
                    </span>
                  </label>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
          <div class="bottom">
            <button class="btn-primary">เสร็จสิ้น</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif
  <div class="container">
    <p class='mb-4'>Member ID: {{session('member_id')}}</p>
    <a class='btn-primary' href="/logout">logout</a>
  </div>

</body>

</html>