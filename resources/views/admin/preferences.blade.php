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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/da55352c9a.js" crossorigin="anonymous"></script>
    <title>Admin Preferences</title>
</head>
<body style="background-color: #F5F5F5">

    <nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark" style="margin-bottom: 10px">
        <a class="navbar-brand" href="#">ADMIN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
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
                <li class="nav-item ">
                    <a class="nav-link" href="reviews">
                        <i class="fa-solid fa-comment"></i>
                        Review
                    </a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" href="preferences">
                        <i class="fa-solid fa-heart"></i>
                        Preferences
                </li>

            </ul>


            <a href="/logout"><button class="btn btn-outline-danger my-2 my-sm-0" type="submit">Logout <span><i
                            class="fa-solid fa-right-from-bracket"></i></span></button></a>


        </div>
    </nav>

    <div class="container">
        <div class="card" style="box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);">
            <div class="card-header" style="background-color: #FFDAB9">
                <div class="row">
                    <div class="col-sm-2" style="text-align: center;">
                        <h5 class="text-danger"><i class="fa-solid fa-heart"></i> Preferences</h5>
                    </div>
                    <div class="col-sm-6" style="margin-left: -30px;">
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="col-sm-4">
                        <form action="{{ route('search.pre') }}" method="GET">
                            <div class="input-group" style="text-align: right">
                                <input type="text" placeholder="ค้นหาประเภทความชอบ" name="search" id="search" class="form-control" style="width: 220px;"  />
                                <button class="btn btn-danger" title="ค้นหา" type="submit"><span><i style="color: white;" class="fa-solid fa-magnifying-glass"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        @foreach ($preferences as $item)
                        <div class="col-sm-3" style="margin-top: 10px;">
                            <div class="card" style="box-shadow: 0 0 20px rgba(255, 0, 0 ,0.20);">
                                <div class="card-header" style="background-color: #FFDAB9; text-align: center;">
                                    <span class="text-secondary">ความชอบส่วนบุคคล</span>
                                </div>
                                <div class="card-body" style="text-align: center;">
                                    {{$item->preference_name}}
                                </div>
                                <div class="card-footer" style="text-align: right;">
                                    <a href='#edit{{ $item->preference_id }}' data-toggle='modal'><button style="margin-right: 5px;" class="btn btn-warning btn-sm"
                                        title="แก้ไขข้อมูลความชอบ" type="submit"><span><i style="color: white;"
                                                class="fa-solid fa-pen"></i></span>
                                    </button></a>
                                    <a href='#delete{{ $item->preference_id }}' data-toggle='modal'><button
                                        class="btn btn-danger btn-sm" title="ลบข้อมูล" type="submit"><span><i
                                                class="fa-solid fa-trash"></i></span>
                                    </button></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div style="margin: 10px;">
                        {{$preferences->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        เพิ่มข้อมูลประเภทความชอบ
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <form method="post" action="{{ route('insertpreference') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12" style="margin-top: 20px;">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">ความชอบส่วนบุคคล:</div>
                                        </div>
                                        <input type="text" class="form-control" name="preference_name" required>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="preference_id" value="">
                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-danger" value="Save Changes" name="save">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($preferences as $item)
    <div class="container">
        <div class="modal fade" id="delete{{ $item->preference_id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #d9534f; color: white;">
                        ลบข้อมูล
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <span style="color: #d9534f">คุณต้องการลบข้อมูลสถานที่: {{ $item->preference_name }}
                            ใช่หรือไม่ !!</span>
                    </div>

                    <div class="modal-footer">
                        <a href="{{ route('delpreferences', $item->preference_id) }}"> <button class="btn btn-danger" title="ลบข้อมูล" type="submit">Delete
                        </button></a>
                        <button type="button" class="btn btn-defalut" style="border-color: #d9534f; color: #d9534f;" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="edit{{ $item->preference_id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #E4A11B; color: white;">
                        แก้ไขข้อมูลความชอบ
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <form method="post" action="{{ route('update-preference', $item->preference_id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-12" style="margin-top: 20px;">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">ความชอบส่วนบุคคล:</div>
                                    </div>
                                    <input type="text" class="form-control" name="preference_name" value="{{$item->preference_name}}" required>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="preference_id" value="{{$item->preference_id}}">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-warning" title="แก้ไขข้อมูล" type="submit">Edit</button>
                        <button type="button" class="btn btn-defalut" style="border-color: #E4A11B; color: #E4A11B;" data-dismiss="modal">Close</button>
                    </div>
                 </form> <!-- ปิด Form Tag -->
                </div>
            </div>
        </div>


    </div> {{-- /container --}}
    


@endforeach



   


     <!-- jQuery library -->
     <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
     <!-- Popper JS -->
     <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
     <!-- Latest compiled JavaScript -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>