@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alert('{{ session('success') }}');
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alert('{{ session('error') }}');
        });
    </script>
@endif

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/da55352c9a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>Admin Locations</title>
</head>

<body style="background-color: #F5F5F5">

    <nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark" style="margin-bottom: 10px">
        <a class="navbar-brand" href="">ADMIN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item ">
                    <a class="nav-link" href="dashboard">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="locations">
                        <i class="fa fa-home"></i>
                        Locations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users">
                        <i class="fa-solid fa-users"></i>
                        User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reviews">
                        <i class="fa-solid fa-comment"></i>
                        Review
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="preferences">
                        <i class="fa-solid fa-heart"></i>
                        Preferences
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="questions">
                        <i class="fa-solid fa-circle-question"></i>
                        Questions
                    </a>
                </li>

            </ul>


            <a href="/logout"><button class="btn btn-outline-danger my-2 my-sm-0" type="submit">Logout <span><i
                            class="fa-solid fa-right-from-bracket"></i></span></button></a>


        </div>
    </nav>

    <div class="container">
        <div class="card border-info">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-8">
                        <h5><span><i class="fa-solid fa-map-pin"></i></span> Locations <button class="btn btn-success"
                                title="เพิ่มข้อมูล" type="button" data-toggle="modal" data-target="#myModal"><span><i
                                        style="color: white" class="fa-solid fa-plus"></i></a></span>
                            </button> </h5>
                    </div>
                    <div class="col-sm-4">
                        <form action="{{ route('search.location') }}" method="GET">
                            <div class="input-group" style="text-align: right">
                                <input type="search" placeholder="ค้นหาสถานที่ท่องเที่ยว" name="search" id="search"
                                    value="" class="form-control" style="width: 220px;" />
                                <button class="btn btn-warning" title="ค้นหา" type="submit"><span><i
                                            style="color: white;" class="fa-solid fa-magnifying-glass"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>


            {{-- Modal Insert --}}
            <div class="container">
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                เพิ่มข้อมูล
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                                <form method="post" action="{{ route('insertlocations') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="location_name"
                                                placeholder="ชื่อสถานที่ท่องเที่ยว"
                                                onKeyPress="return KeyCode(location_name)" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="address"
                                                placeholder="ที่อยู่สถานที่ท่องเที่ยว" required>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 10px;">
                                            <textarea name="detail" class="form-control" placeholder="รายละเอียดสถานที่ท่องเที่ยว" required></textarea>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 10px;">
                                            <select class="custom-select mr-sm-2" name="province_id">
                                                <option selected>จังหวัด...</option>
                                                <?php
                                                $provinces = DB::table('provinces')->get();
                                                ?>
                                                @foreach ($provinces as $item)
                                                    <option value="{{ $item->province_id }}">{{ $item->province_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 10px;">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">เวลาเปิด</div>
                                                </div>
                                                <input type="time" class="form-control" name="s_time" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 10px;">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">เวลาปิด</div>
                                                </div>
                                                <input type="time" class="form-control" name="e_time" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 10px;">
                                            <input type="number" step="0.0000000001" class="form-control"
                                                name="latitude" placeholder="Latitude" required>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 10px;">
                                            <input type="number" step="0.0000000001" class="form-control"
                                                name="longitude" placeholder="Longitude" required>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 10px;">

                                            <input type="file" name="img_path[]" class="form-control"
                                                multiple="multiple" accept="image/jpeg, image/png" required>
                                        </div>

                                        <div class="col-sm-6" style="margin-top: 10px;">
                                            <input type="text" class="form-control" name="credit"
                                                placeholder="Credit:Photo" required>
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px;">
                                            <span style="font-size: 16px;">วันที่เปิดทำการ</span><br>
                                            @php
                                                $days = DB::table('days')->get();
                                            @endphp
                                            @foreach ($days as $day)
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-outline-primary " style="margin: 10px;">
                                                        <input type="checkbox" name="days_id[]"
                                                            value="{{ $day->day_id }}">
                                                        {{ $day->day_name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <?php
                                        $preference = DB::table('preferences')->get();
                                        ?>
                                        <div class="col-sm-12" style="margin-top: 10px;">
                                            <span style="font-size: 16px;">ประเภทสถานที่ท่องเที่ยว</span><br>
                                            @foreach ($preference as $item)
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-outline-primary" style="margin: 10px;">
                                                        <input type="checkbox" name="preferences_id[]"
                                                            value="{{ $item->preference_id }}">{{ $item->preference_name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>

                                    <input type="hidden" name="location_id" value="">
                                    <input type="hidden" name="img_id" value="">

                            </div>

                            <div class="modal-footer">
                                <input type="submit" class="btn btn-success" value="Save Changes" name="save">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> {{-- Close Modal Insert --}}


            <div class="card-body">

                <form action="{{ route('admin.locations') }}" method="GET">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">จังหวัด:</div>
                                </div>
                                <select name="searchProvince" class="form-control">
                                    <option selected>ทั้งหมด...</option>
                                    <?php
                                    $provinces = DB::table('provinces')->get();
                                    ?>
                                    @foreach ($provinces as $item)
                                        <option value="{{ $item->province_id }}">{{ $item->province_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button style="height: 100%; margin-top: 5px;" class="btn btn-primary btn-sm">ค้นหา</button>
                    </div>
                </form>

                <hr>
                <div class="row">
                    @foreach ($locations as $item)
                        <div class="col-md-4" style="margin-top: 5px;">
                            <div class="card">

                                <img class="card-img-top rounded mx-auto d-block"
                                    style="height: 200px; padding: 10px;"
                                    src="{{ asset('storage/images/locations/' . $item->img_names) }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ Str::limit($item->location_name, 30) }}</h5>
                                    <p class="card-text">{{ Str::limit($item->detail, 110) }}</p>
                                    <p class="card-text"><small class="text-muted">Opening and Closing Times:
                                            {{ $item->s_time }} - {{ $item->e_time }}</small></p>
                                </div>
                                <div class="card-footer" style="text-align: right">

                                    <a href="{{ route('locations', ['location_id' => $item->location_id]) }}">
                                        <button type='button' class='btn btn-secondary' data-toggle="tooltip"
                                            title="เพิ่มเติม">
                                            <i class="fa-solid fa-bars"></i>
                                        </button>
                                    </a>

                                    <a href='#delete{{ $item->location_id }}' data-toggle='modal'><button
                                            class="btn btn-danger" title="ลบข้อมูล" type="submit"><span><i
                                                    class="fa-solid fa-trash"></i></span>
                                        </button></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div> {{-- row --}}
                <br>
                {{ $locations->links() }}
            </div> {{-- card body --}}
        </div>
    </div>

    @foreach ($locations as $item)
        <div class="container">
            <div class="modal fade" id="delete{{ $item->location_id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #d9534f; color: white;">
                            ลบข้อมูล
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <span style="color: #000; font-size: 16px;">คุณต้องการลบข้อมูลสถานที่: <span
                                    style="color: #d9534f; font-size: 18px;">{{ $item->location_name }}</span>
                                ใช่หรือไม่ !!</span>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('deletelocation', $item->location_id) }}"> <button
                                    class="btn btn-danger" title="ลบข้อมูล" type="submit">Delete
                                </button></a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endforeach


    <script type="text/javascript">
        function KeyCode(objId) {
            if (
                (event.keyCode >= 48 && event.keyCode <= 57) || // 48-57(ตัวเลข)
                (event.keyCode >= 97 && event.keyCode <= 122) || // 97-122(Eng ตัวพิมพ์เล็ก)
                (event.keyCode >= 65 && event.keyCode <= 90) || // 65-90(Eng ตัวพิมพ์ใหญ่)
                (event.keyCode >= 3586 && event.keyCode <= 3675) ||
                (event.keyCode === 32) // space bar
            ) {
                return true;
            } else {
                alert("กรอกได้เฉพาะ a-z หรือ A-Z และ 0-9");
                return false;
            }
        }


        function KeyCode1(objId) {
            if (event.keyCode >= 48 && event.keyCode <= 57 || event.keyCode >= 161 && event.keyCode <= 255 || event
                .keyCode >= 3585 && event.keyCode <= 3675 || event
                .keyCode >= 97 && event.keyCode <= 122 || event.keyCode >= 65 && event.keyCode <= 90
            ) //48-57(ตัวเลข) ,65-90(Eng ตัวพิมพ์ใหญ่ ) ,97-122(Eng ตัวพิมพ์เล็ก)
            {
                return true;
            } else {
                alert("กรอกได้เฉพาะ ก-ฮ และ a-z และ A-Z หรือ 0-9");
                return false;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
