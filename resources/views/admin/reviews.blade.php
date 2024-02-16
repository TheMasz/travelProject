<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/da55352c9a.js" crossorigin="anonymous"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    
    <title>Admin Reviews</title>
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
        <div class="card" style="box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);">
            <div class="card-header" style="background-color: #B0C4DE">
                <div class="row">
                    <div class="col-sm-8">
                        <h5 class="text-primary"><i class="fa-solid fa-comment"></i> Review</h5>
                    </div>
                    <div class="col-sm-4">
                        <form action="{{ route('search.reviews') }}" method="GET">
                            <div class="input-group" style="text-align: right">
                                <input type="text" placeholder="ค้นหาสถานที่ท่องเที่ยว" name="search" id="search"
                                    class="form-control" style="width: 220px;" />
                                <button class="btn btn-primary" title="ค้นหา" type="submit"><span><i
                                            style="color: white;" class="fa-solid fa-magnifying-glass"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="row" style="margin-top: -20px">
                    @foreach ($data as $item)
                        <div class="col-md-4">
                            <section class="mx-auto my-5" style="max-width: 25rem;">
                                <div class="card">
                                    <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                                        <img class="card-img-top rounded mx-auto d-block"
                                            style="height: 200px; padding: 10px;"
                                            src="{{ asset('storage/images/locations/' . $item->img_names) }}">
                                        <a href="#!">
                                            <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title font-weight-bold"><a>{{ $item->location_name }}</a></h5>
                                        <ul class="list-unstyled list-inline mb-0">
                                            @if ($item->rating <= 1)
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

                                            @if ($item->rating >= 1.5 && $item->rating <= 1.9)
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

                                            @if ($item->rating >= 2 && $item->rating <= 2.4)
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

                                            @if ($item->rating >= 2.5 && $item->rating <= 2.9)
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

                                            @if ($item->rating >= 3 && $item->rating <= 3.4)
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

                                            @if ($item->rating >= 3.5 && $item->rating <= 3.9)
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

                                            @if ($item->rating >= 4 && $item->rating <= 4.4)
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

                                            @if ($item->rating >= 4.5 && $item->rating <= 4.9)
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

                                            @if ($item->rating >= 5)
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
                                                <p class="text-muted">{{ $item->rating }} ({{ $item->countrat }})</p>
                                            </li>
                                        </ul>

                                        <p class="mb-2 text-muted">Comment By: {{ $item->username }}</p>
                                        <p class="card-text">
                                        <p class="card-text">รีวิว: {{ Str::limit($item->comment_txt, 34) }}</p>
                                        </p>
                                        <hr class="my-4" />

                                        <a href="{{ route('reviews', ['location_id' => $item->location_id]) }}">
                                            <button style="margin-right: 5px;" class="btn btn-info btn-sm"
                                                title="ดูรีวิวเพิ่มเติม" type="submit"><span><i style="color: white"
                                                        class="fa-solid fa-magnifying-glass"></i></span>
                                            </button>
                                        </a>

                                        {{-- <button style="margin-right: 5px;" class="btn btn-warning btn-sm"
                                        title="แก้ไขข้อมูลรีวิว" type="submit"><span><i style="color: white;"
                                                class="fa-solid fa-pen"></i></span>
                                    </button> --}}
                                        {{-- <a href="{{ route('delreviews', $item->comment_id) }}"
                                        onclick="return confirm('คุณต้องการลบข้อมูลรีวิวของคุณ:{{ $item->username }} สถานที่:{{ $item->location_name }} ใช่หรือไม่ ?')"><button
                                            class="btn btn-danger btn-sm" title="ลบข้อมูล"
                                            type="submit"><span><i class="fa-solid fa-trash"></i></span>
                                        </button></a> --}}

                                        {{-- <a href='#delete{{ $item->comment_id }}' data-toggle='modal'><button
                                                class="btn btn-danger btn-sm" title="ลบข้อมูล"
                                                type="submit"><span><i class="fa-solid fa-trash"></i></span>
                                            </button></a> --}}
                                    </div>
                                </div>

                            </section>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- @foreach ($data as $item)
        <div class="container">
            <div class="modal fade" id="delete{{ $item->comment_id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #d9534f; color: white;">
                            ลบข้อมูล
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <span style="color: #d9534f">คุณต้องการลบข้อมูลรีวิวของคุณ:{{ $item->username }}
                                สถานที่:{{ $item->location_name }} ใช่หรือไม่ ?</span>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('delreviews', $item->comment_id) }}"> <button class="btn btn-danger"
                                    title="ลบข้อมูล" type="submit">Delete
                                </button></a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}


    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
