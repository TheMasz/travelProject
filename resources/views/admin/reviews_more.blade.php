<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/da55352c9a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/locationDetail.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>รายละเอียดรีวิวเพิ่มเติม</title>
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
                    <a class="nav-link" href="/admin/reviews">
                        <i class="fa-solid fa-chevron-left"></i>
                        Reviews
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

                    </div>
                @endforeach
            </div>



        </div>
    </div>


    <div class="container" style="margin-bottom: 10px">
        <div class="card" box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);">
            <div class="card-header bg-dark" style="color: white;">
                รายละเอียดการรีวิวต่างๆ
            </div>
            <div class="card-body">

                <div class="row">
                    @foreach ($members as $index => $item)
                        <div class="col-sm-4">
                            <div class="card" style="margin-bottom: 20px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);">
                                <div class="card-header">
                                    รายละเอียดผู้รีวิวสถานที่ท่องเที่ยว
                                </div>
                                <div class="card-body">
                                    <li class="list-inline-item">
                                        @php
                                            // หาตำแหน่งของ comment_id ใน $members
                                            $index = array_search($item->comment_id, array_column($members->toArray(), 'comment_id'));
                                        @endphp
                                        @if ($index !== false)
                                            {{-- ในกรณีที่พบ comment_id --}}
                                            <p class="mb-2 text-muted">ผู้รีวิว: {{ $members[$index]->username }}</p>
                                        @endif
                                    </li>
                                    <br>
                                    <li class="list-inline-item">
                                        <p class="mb-2 text-muted">Comment: {{ $item->review }}</p>
                                    </li>
                                    <br>
                                    <li class="list-inline-item">
                                        <p class="mb-2 text-muted">Date: {{ $item->created_at }}</p>
                                    </li>
                                    <br>

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
                                </div>{{-- /card-body --}}
                                <div class="card-footer text-right">
                                    <a href='#delete{{ $item->review_id }}' data-toggle='modal'><button
                                            class="btn btn-danger" title="ลบข้อมูล" type="submit"><span><i
                                                    class="fa-solid fa-trash"></i></span>
                                        </button></a>
                                </div>
                            </div>
                        </div> {{-- /col-sm-4 --}}
                    @endforeach

                </div>
                {{ $reviews->links() }}
            </div>
        </div>
    </div>


    @foreach ($reviews as $index => $item)
        <div class="container">
            <div class="modal fade" id="delete{{ $item->review_id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #d9534f; color: white;">
                            ลบข้อมูล
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            @php
                                // หาตำแหน่งของ comment_id ใน $members
                                $index = array_search($item->review_id, array_column($members->toArray(), 'review_id'));
                            @endphp
                            @if ($index !== false)
                                <span style="color: #d9534f">คุณต้องการลบข้อมูลรีวิวของคุณ:
                                    {{ $members[$index]->username }}
                                    สถานที่: {{ $location->location_name }} ใช่หรือไม่ ?</span>
                            @endif

                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('delreviews', $item->review_id) }}">
                                <button class="btn btn-danger" title="ลบข้อมูล" type="submit">Delete</button>
                            </a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach



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
