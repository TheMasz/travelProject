<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/da55352c9a.js" crossorigin="anonymous"></script>
    <title>Admin Reviews</title>
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
                <li class="nav-item active">
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
        <div class="card" style="box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);">
           <div class="card-header" style="background-color: #B0C4DE">
                <div class="row">
                    <div class="col-sm-8">
                        <h5 class="text-primary"><i class="fa-solid fa-comment"></i> Review</h5>
                    </div>
                    <div class="col-sm-4">
                        <form action="" method="post" name="from1" id="from1">
                            <div class="input-group" style="text-align: right">
                                <input type="text" placeholder="ค้นหาสถานที่ท่องเที่ยว" name="txtsearch" id="txtsearch" class="form-control" style="width: 220px;"  />
                                <button class="btn btn-primary" title="ค้นหา" type="submit"><span><i style="color: white;" class="fa-solid fa-magnifying-glass"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
           </div>
           <div class="card-body">
                <div class="row" style="margin-top: -20px">
                    @foreach ($data as $item)
                    <div class="col-sm-4">
                        <section class="mx-auto my-5" style="max-width: 25rem;">
                            <div class="card">
                                <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                                    <img src="{{url('storage/'.$item->img_names)}}" class="img-fluid" />
                                    <a href="#!">
                                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold"><a>{{$item->location_name}}</a></h5>
                                    <ul class="list-unstyled list-inline mb-0">

                                        @if ($item->rating == 1)
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                        
                                         @endif

                                        @if ($item->rating == 1.5)
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item">
                                            <i class="fas fa-star-half-alt text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                         @endif

                                         @if ($item->rating == 2)
                                         <li class="list-inline-item me-0">
                                             <i class="fas fa-star text-warning fa-xs"></i>
                                         </li>
                                         <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                          @endif

                                          @if ($item->rating == 2.5)
                                          <li class="list-inline-item me-0">
                                              <i class="fas fa-star text-warning fa-xs"></i>
                                          </li>
                                          <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item">
                                            <i class="fas fa-star-half-alt text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>

                                           @endif

                                          @if ($item->rating == 3)
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                       
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                         @endif

                                          @if ($item->rating == 3.5)
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item">
                                            <i class="fas fa-star-half-alt text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                         @endif

                                         @if ($item->rating == 4)
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fa-regular text-warning fa-star fa-xs"></i>
                                        </li>
                                         @endif

                                         @if ($item->rating == 4.5)
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item">
                                            <i class="fas fa-star-half-alt text-warning fa-xs"></i>
                                        </li>
                                         @endif

                                         @if ($item->rating == 5)
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                        <li class="list-inline-item me-0">
                                            <i class="fas fa-star text-warning fa-xs"></i>
                                        </li>
                                         @endif
                                    

                                        <li class="list-inline-item">
                                            <p class="text-muted">{{$item->rating}} ({{$item->countrat}})</p>
                                        </li>
                                    </ul>
                                    
                                    <p class="mb-2 text-muted">Comment By: {{$item->username}}</p>
                                    <p class="card-text">
                                        <p class="card-text">รีวิว: {{Str::limit($item->comment_txt,34)}}</p>
                                    </p>
                                    <hr class="my-4" />
                                    
                                    <button style="margin-right: 5px;" class="btn btn-info btn-sm"
                                        title="ดูรีวิวเพิ่มเติม" type="submit"><span><i style="color: white" class="fa-solid fa-magnifying-glass"></i></span>
                                    </button>
                                    <button style="margin-right: 5px;" class="btn btn-warning btn-sm"
                                        title="แก้ไขข้อมูลรีวิว" type="submit"><span><i style="color: white;"
                                                class="fa-solid fa-pen"></i></span>
                                    </button>
                                    <button class="btn btn-danger btn-sm" title="ลบข้อมูล"
                                        type="submit"><span><i class="fa-solid fa-trash"></i></span>
                                    </button>
                                </div>
                            </div>
                
                        </section>
                    </div>
                    @endforeach
                </div>
           </div>
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