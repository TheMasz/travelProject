@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->has('img_path'))
    <div class="alert alert-danger" id="error-message">
        {{ $errors->first('img_path') }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('error-message').style.display = 'none';
        }, 7000); // 7000 มิลลิวินาที = 7 วินาที
    </script>
@endif

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/da55352c9a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/locationDetail.css') }}">

    <title>รายละเอียดสถานที่ท่องเที่ยวเพิ่มเติม</title>
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
                <li class="nav-item active">
                    <a class="nav-link" href="/admin/locations">
                        <i class="fa-solid fa-chevron-left"></i>
                        Locations
                    </a>
                </li>

            </ul>


            <a href="/logout"><button class="btn btn-outline-danger my-2 my-sm-0" type="submit">Logout <span><i
                            class="fa-solid fa-right-from-bracket"></i></span></button></a>


        </div>
    </nav>

    <div class="container">
        <h3 style="text-align: center; margin-top: 20px;">{{ $location->location_name }}</h3>
        <div class="images-wrap">
            <div class="main-image">
                <div class="image"></div>
                <div class="credit">credit: {{ $images[0]->credit }}</div>
            </div>
            <div class="image-box">
                @foreach ($images as $img)
                    <div class="image"
                        style="background: url({{ asset('storage/images/locations/' . $img->img_path) }});"
                        data-img="{{ $img->img_path }}">
                        <div class="card-footer text-right"
                            style="backdrop-filter: blur(10px); height: 45px; margin-top: 90%; margin-bottom: 5px;">
                            <a href='#editphoto{{ $img->img_id }}' data-toggle='modal'>
                                <button class="btn btn-warning btn-sm"><i style="color: white;"
                                        class="fa-solid fa-pen"></i></button>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <div class="container" style="margin-bottom: 10px">
        <div class="card" style="box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);">
            <div class="card-header bg-dark" style="color: white;">
                รายละเอียดสถานที่ท่องเที่ยว
            </div>
            <div class="card-body">
                <p class="card-text">ที่อยู่: {{ $location->address }}</p>
                <p class="card-text">รายละเอียด: {{ $location->detail }}</p>
                <p class="card-text">เวลาเปิด-ปิด: {{ $location->s_time }} - {{ $location->e_time }} น.</p>
                <p class="card-text">ละติจูด: {{ $location->latitude }}</p>
                <p class="card-text">ลองจิจูด: {{ $location->longitude }}</p>
                <div class="row">
                    @php
                        $prefs = explode(', ', $location->Preferences);
                    @endphp

                    @foreach ($prefs as $pref)
                        <div class="category">
                            {{ $pref }}
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-right">
                <a href='#editdata{{ $location->location_id }}' data-toggle='modal'><button class="btn btn-warning"
                        title="แก้ไขข้อมูลสถานที่ท่องเที่ยว" type="submit"><span><i style="color: white;"
                                class="fa-solid fa-pen"></i></span>
                    </button>

                    <a href='#delete{{ $location->location_id }}' data-toggle='modal'><button class="btn btn-danger"
                            title="ลบข้อมูล" type="submit"><span><i class="fa-solid fa-trash"></i></span>
                        </button></a>
            </div>
        </div>
    </div>

    {{-- Modal edit --}}
    <div class="container">
        <div class="modal fade" id="editdata{{ $location->location_id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        แก้ไขข้อมูลสถานที่ท่องเที่ยว
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <form method="post" action="{{ route('updateLocation', $location->location_id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="location_name"
                                        placeholder="ชื่อสถานที่ท่องเที่ยว" value="{{ $location->location_name }}"
                                        required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="address"
                                        placeholder="ที่อยู่สถานที่ท่องเที่ยว" value="{{ $location->address }}"
                                        required>
                                </div>
                                <div class="col-sm-6" style="margin-top: 10px;">
                                    <textarea name="detail" class="form-control"placeholder="รายละเอียดสถานที่ท่องเที่ยว" required>{{ $location->detail }}</textarea>
                                </div>
                                <div class="col-sm-6" style="margin-top: 20px;">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">เวลาเปิด</div>
                                        </div>
                                        <input value="{{ $location->s_time }}" type="time" class="form-control"
                                            name="s_time" required>
                                    </div>
                                </div>
                                <div class="col-sm-6" style="margin-top: 10px;">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">เวลาปิด</div>
                                        </div>
                                        <input type="time" value="{{ $location->e_time }}" class="form-control"
                                            name="e_time" required>
                                    </div>
                                </div>
                                <div class="col-sm-6" style="margin-top: 10px;">
                                    <select class="custom-select mr-sm-2" name="province_id">
                                        <option selected>จังหวัด...</option>
                                        <?php
                                        $provinces = DB::table('provinces')->get();
                                        ?>
                                        @foreach ($provinces as $item)
                                            <option value="{{ $item->province_id }}"
                                                {{ $item->province_id == $location->province_id ? 'selected' : '' }}>
                                                {{ $item->province_name }} <!-- แก้ไขตรงนี้ -->
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-6" style="margin-top: 10px;">
                                    <input value="{{ $location->latitude }}" type="number" step="0.0000000001"
                                        class="form-control" name="latitude" placeholder="Latitude" required>
                                </div>
                                <div class="col-sm-6" style="margin-top: 10px;">
                                    <input value="{{ $location->longitude }}" type="number" step="0.0000000001"
                                        class="form-control" name="longitude" placeholder="Longitude" required>
                                </div>

                                <div class="col-sm-6" style="margin-top: 10px;">
                                    @foreach ($images->unique('credit') as $img1)
                                        <input type="text" class="form-control" name="credit"
                                            placeholder="Credit: Photo" value="{{ $img1->credit }}" required>
                                    @endforeach
                                </div>
                                <?php
                                $preference = DB::table('preferences')->get();
                                ?>
                                <div class="col-sm-12" style="margin-top: 10px;">
                                    <span style="font-size: 16px;">ประเภทสถานที่ท่องเที่ยว</span><br>
                                    @foreach ($preference as $item)
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label
                                                class="btn btn-outline-primary {{ in_array($item->preference_name, $prefs) ? 'active' : '' }}"
                                                style="margin: 10px;">
                                                <input type="checkbox" name="preferences_id[]"
                                                    value="{{ $item->preference_id }}"
                                                    {{ in_array($item->preference_name, $prefs) ? 'checked' : '' }}>
                                                {{ $item->preference_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>


                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-warning" value="Save Changes" name="save">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div> {{-- Close Modal edit --}}


    <div class="container">
        <div class="modal fade" id="delete{{ $location->location_id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #d9534f; color: white;">
                        ลบข้อมูล
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <span style="color: #d9534f">คุณต้องการลบข้อมูลสถานที่: {{ $location->location_name }}
                            ใช่หรือไม่ !!</span>
                    </div>

                    <div class="modal-footer">
                        <a href="{{ route('deletelocation', $location->location_id) }}"> <button
                                class="btn btn-danger" title="ลบข้อมูล" type="submit">Delete
                            </button></a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>


        @foreach ($images as $img)
            <form method="post" enctype="multipart/form-data"
                action="{{ route('updatephoto', ['img_id' => $img->img_id]) }}">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal fade" id="editphoto{{ $img->img_id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #E4A11B; color: white;">
                                แก้ไขรูปภาพสถานที่ท่องเที่ยว
                                <button style="color: white;" type="button" class="close"
                                    data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">

                                <div class="col-sm-6" style="margin-left: 110px; margin-bottom: 40px;">
                                    <img style="width: 200px;  box-shadow: 0 0 20px rgba(0, 0, 0, 0.50);"
                                        height="120px;"
                                        src="{{ asset('storage/images/locations/' . $img->img_path) }}"></>
                                </div>

                                <div class="col-sm-12" style="margin-top: 10px;">
                                    <input type="file" name="img_path" class="form-control"
                                        accept="image/jpeg, image/png" required>
                                </div>

                            </div>

                            <input type="hidden" name="location_id" value="{{ $img->location_id }}">
                            <input type="hidden" name="credit" value="{{ $img->credit }}">

                            <div class="modal-footer">
                                <button class="btn btn-warning" style="color: white;" title="แก้ไข"
                                    type="submit">Edit
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        @endforeach

    </div>


    <script>
        // หลังจากที่คุณทำงานสำเร็จ เช่น การคลิกปุ่ม "Edit" หรือส่ง request
        document.addEventListener('DOMContentLoaded', function() {
            // แสดง Alert
            var alertElement = document.querySelector('.alert-success');
            alertElement.style.display = 'block';

            // ซ่อน Alert หลังจาก 5 วินาที
            setTimeout(function() {
                alertElement.style.display = 'none';
            }, 5000);
        });
    </script>



    <script src="{{ asset('js/showImages.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
