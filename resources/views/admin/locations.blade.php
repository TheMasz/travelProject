<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/da55352c9a.js" crossorigin="anonymous"></script>

    <title>Admin Locations</title>
</head>

<body style="background-color: #ddd">

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
                                title="เพิ่มข้อมูล" type="submit"><span><i style="color: white"
                                        class="fa-solid fa-plus"></i></span>
                            </button> </h5>
                    </div>
                    <div class="col-sm-4">
                        <form action="" name="from1" id="from1">
                            <div class="input-group" style="text-align: right">
                                <input type="search" placeholder="ค้นหาสถานที่ท่องเที่ยว" name="search"
                                    id="search" value="" class="form-control" style="width: 220px;" />
                                <button class="btn btn-warning" title="ค้นหา" type="submit"><span><i
                                            style="color: white;" class="fa-solid fa-magnifying-glass"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
            
            <div class="card-body">
                <div class="row">
                    @foreach ($locations as $item)
                    <div class="col-sm-4" style="margin-top: 5px;">
                        <div class="card">
                            
                            <img class="card-img-top rounded mx-auto d-block" style="height: 200px; padding: 10px;"
                                src="{{ url('storage/'.$item->img_names) }}" alt="Card image cap">
                            <div class="card-body">
                                    <h5 class="card-title">{{Str::limit($item->location_name,30)}}</h5>
                                    <p class="card-text">{{Str::limit($item->detail,110)}}</p>
                                    <p class="card-text"><small class="text-muted">Opening and Closing Times: {{$item->s_time}} - {{$item->e_time}}</small></p>
                                </div>
                                <div class="card-footer" style="text-align: right">
                                    <button class="btn btn-secondary" title="เพิ่มเติม" type="submit"><span><i
                                                class="fa-solid fa-bars"></i></span>
                                    </button>
                                    <button class="btn btn-warning" title="แก้ไขข้อมูลสถานที่ท่องเที่ยว" type="submit"><span><i
                                                style="color: white;" class="fa-solid fa-pen"></i></span>
                                    </button>
                                    <button class="btn btn-danger" title="ลบข้อมูล" type="submit"><span><i
                                                class="fa-solid fa-trash"></i></span>
                                    </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div> {{-- row --}}
                <br>
                {{$locations->links()}}
            </div> {{-- card body --}}
        </div>
    </div>

    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
