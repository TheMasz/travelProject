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
<body>

    <nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark" style="margin-bottom: 10px">
        <a class="navbar-brand" href="#">ADMIN</a>
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
                    <div class="col-sm-10">
                        <h6 style="color: white; font-size: 20px;"><i class="fa-solid fa-users"></i> User</h6>
                    </div>
                    <div class="col-sm-2" style="text-align: right">
                        <button class="btn btn-warning btn-sm" style="color: white" data-toggle="collapse"
                            data-target="#demo"><i class="fa-solid fa-caret-down"></i></button>
                    </div>
                </div>

            </div>
            <div class="card-body-collapse" id="demo">
                <div class="table-responsive-xl">
                    <div class="row">
                        <div class="col-xl-4" style="margin: 5px">
                            <form action="" method="post" name="from1" id="from1">
                                <div class="input-group" style="text-align: right">
                                    <input type="text" placeholder="ค้นหาข้อมูลนักท่องเที่ยว" name="txtsearch" id="txtsearch" class="form-control" style="width: 220px;"  />
                                    <button class="btn btn-primary" title="ค้นหา" type="submit"><span><i style="color: white;" class="fa-solid fa-magnifying-glass"></i></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                                <td><img src="{{$item->member_img}}" alt=""></td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->username}}</td>
                                <td>{{Str::limit($item->password,10)}}</td>
                                <td>{{$item->status}}</td>
                                <td><button style="margin-right: 5px;" class="btn btn-warning btn-sm"
                                        title="แก้ไขข้อมูลผู้ใช้งาน" type="submit"><span><i style="color: white;"
                                                class="fa-solid fa-pen"></i></span>
                                    </button><button class="btn btn-danger btn-sm" title="ลบข้อมูล"
                                        type="submit"><span><i class="fa-solid fa-trash"></i></span>
                                    </button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
               <div class="container">
                {{$users->links()}}
               </div>
            </div>
        </div>{{-- card user --}}

    </div>


     <!-- jQuery library -->
     <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
     <!-- Popper JS -->
     <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
     <!-- Latest compiled JavaScript -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>