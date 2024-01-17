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
    <style>
        .sh-table {
            border-collap: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);
            border-bottom: 2px solid #009878;
        }
    </style>
    <title>Admin User</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="locations">
                        <i class="fa fa-home"></i>
                        Locations
                    </a>
                </li>
                <li class="nav-item active">
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

        <div class="card sh-table">
            <div class="card-header" style="background-color: #009870">
                <div class="row">
                    <div class="col-sm-8">
                        <h6 style="color: white; font-size: 20px;"><i class="fa-solid fa-users"></i> User</h6>
                    </div>
                    <div class="col-sm-4" style="text-align: right">

                        <form action="{{ route('search.users') }}" method="GET">
                            <div class="input-group" style="text-align: right">
                                <input type="text" placeholder="ค้นหาข้อมูลนักท่องเที่ยว" name="search"
                                    id="search" class="form-control" style="width: 220px;" />
                                <button class="btn btn-primary" title="ค้นหา" type="submit"><span><i
                                            style="color: white;" class="fa-solid fa-magnifying-glass"></i></span>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive-xl">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>รูปภาพ</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Status</th>
                                <th>จัดการข้อมูล</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr>
                                    <td><img style="width: 100px;" height="60px;"
                                            src="{{ asset('storage/images_users/' . $item->member_img) }}"></td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td>{{ Str::limit($item->password, 10) }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td><a href='#edit{{ $item->member_id }}' data-toggle='modal'><button style="margin-right: 5px;" class="btn btn-warning btn-sm"
                                        title="แก้ไขข้อมูลผู้ใช้งาน" type="submit"><span><i style="color: white;"
                                                class="fa-solid fa-pen"></i></span>
                                    </button></a>

                                            <a href='#delete{{ $item->member_id }}' data-toggle='modal'><button
                                                    class="btn btn-danger btn-sm" title="ลบข้อมูล"
                                                    type="submit"><span><i class="fa-solid fa-trash"></i></span>
                                                </button></a>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="container">
                    {{ $users->links() }}
                </div>
            </div>
        </div>{{-- card user --}}

    </div>


    @foreach ($users as $item)
        <div class="container">
            <div class="modal fade" id="delete{{ $item->member_id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #d9534f; color: white;">
                            ลบข้อมูล
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <span style="color: #d9534f">คุณต้องการลบผู้ใช้:{{ $item->username }} ใช่หรือไม่ ?</span>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('deleteusers', $item->member_id) }}"> <button class="btn btn-danger"
                                    title="ลบข้อมูล" type="submit">Delete
                                </button></a>
                            <button type="button" class="btn btn-defalut"
                                style="border-color: #d9534f; color: #d9534f;" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="edit{{ $item->member_id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #E4A11B; color: white;">
                            แก้ไขข้อมูลผู้ใช้งาน
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <form method="post" action="{{ route('update-users', $item->member_id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-sm-12" style="margin-top: 20px;">

                                        <div class="col-sm-6" style="margin-left: 110px; margin-bottom: 40px;">
                                                <img style="width: 200px;  box-shadow: 0 0 20px rgba(0, 0, 0, 0.50);" height="120px;"
                                                 src="{{ asset('storage/images_users/' . $item->member_img) }}"></>
                                        </div>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Email:</div>
                                            </div>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ $item->email }}" required>
                                        </div>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Username:</div>
                                            </div>
                                            <input type="text" class="form-control" name="username"
                                                value="{{ $item->username }}" required>
                                        </div>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Password:</div>
                                            </div>
                                            <input type="text" class="form-control" name="password"
                                                value="{{ $item->password }}" required>
                                        </div>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Status:</div>
                                            </div>
                                                
                                            <select name="status" class="form-control">
                                                <?php
                                                    $statuses = DB::table('members')->select('status')->distinct()->orderBy('status')->get();
                                                ?>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->status }}">{{ $status->status }}</option>
                                                @endforeach
                                            </select>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                                <input type="hidden" name="member_id" value="{{ $item->member_id }}">
                                <input type="hidden" name="member_img" value="{{ $item->member_img }}">
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-warning" title="แก้ไขข้อมูล" type="submit">Edit</button>
                            <button type="button" class="btn btn-defalut"
                                style="border-color: #E4A11B; color: #E4A11B;" data-dismiss="modal">Close</button>
                        </div>
                        </form> <!-- ปิด Form Tag -->
                    </div>
                </div>
            </div>


        </div>
    @endforeach


    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
