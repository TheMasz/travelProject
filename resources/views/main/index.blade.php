<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>หน้าหลัก</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/index.css') }}">
  <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
</head>

<body>
  <x-navbar />
  @if ($mypreferences)
  <x-firstPref :preferences="$preferences" />
  @endif
  <div class="container">
    <div class="mt-2 wug-wrap">
      <div class="row flex-between py-02">
        <div class="row">
          <span class="material-icons">
            public
          </span>
          <h3>ไปไหนดี...</h3>
        </div>
        <div class="dropdown-toggle">
          <span class="material-icons" id="dropdown-icon">
            arrow_drop_down
          </span>
        </div>
      </div>
      <div class="dropdown-menu">
        @foreach(app('getZoneswithProvince')() as $zone)
        <div class="zone-group">
          <div style="font-weight: bold;">{{$zone->zone_name}}</div>
          <div class="row flex-wrap">
            @foreach($zone->provinces as $province)
            <a href='/province/{{ app('findProvinceId')($province) }}'>{{ $province }}</a>
            @endforeach
          </div>

        </div>
        @endforeach
      </div>

    </div>
  </div>
  <script src="{{ asset('js/app.js') }}"></script>
  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
      const wrap = document.querySelector('.wug-wrap');

      const toggle = wrap.querySelector('.dropdown-toggle');
      toggle.addEventListener('click', function(event) {
        event.preventDefault();
        const dropdownMenu = wrap.querySelector('.dropdown-menu');
        const dropdownIcon = document.getElementById('dropdown-icon');
        dropdownMenu.classList.toggle('open');
        if (dropdownMenu.classList.contains('open')) {
          dropdownIcon.textContent = 'arrow_drop_up'; 
        } else {
          dropdownIcon.textContent = 'arrow_drop_down';
        }
      });
    });
  </script>
</body>

</html>