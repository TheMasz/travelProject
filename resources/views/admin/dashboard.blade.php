<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/da55352c9a.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>Dashboard</title>
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
        <h3>Dashboard</h3>
        <hr>
        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <div class="card shadow" style="border-color: #2E8B57;">
                    <div class="card-header bg-success text-white">
                        <span>จำนวนสมาชิกในระบบ</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <span class="text-success" style="font-size: 24px;">
                                    <i class="fas fa-users"></i>
                                </span>
                            </div>
                            <div class="col-sm-7 mt-2">

                                <?php
                                $numberOfUsers = DB::table('members')->where('status', 'user')->count();
                                ?>
                                <h4 class="text-success"><?php echo $numberOfUsers; ?> คน</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow" style="border-color: #d7b700;">
                    <div class="card-header bg-warning text-white">
                        <span>จำนวนสถานที่ท่องเที่ยวในระบบ</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <span class="text-warning" style="font-size: 24px;">
                                    <i class="fas fa-home"></i>
                                </span>
                            </div>
                            <div class="col-sm-7 mt-2">
                                <?php
                                $numberOfLocations = DB::table('locations')->count();
                                ?>
                                <h4 class="text-warning"><?php echo $numberOfLocations; ?> สถานที่</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow" style="border-color: #32CD32;">
                    <div class="card-header bg-success text-white">
                        <span>จำนวนรีวิวสถานที่ท่องเที่ยว</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <span class="text-success" style="font-size: 24px;">
                                    <i class="fas fa-comment"></i>
                                </span>
                            </div>
                            <div class="col-sm-6 mt-2">
                                <?php
                                $numberOfReviews = DB::table('reviews')->count();
                                ?>
                                <h4 class="text-success"><?php echo $numberOfReviews; ?> รีวิว</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow" style="border-color: #FFA07A;">
                    <div class="card-header bg-danger text-white">
                        <span>จำนวนประเภทความชอบ</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <span class="text-danger" style="font-size: 24px;">
                                    <i class="fas fa-heart"></i>
                                </span>
                            </div>
                            <div class="col-sm-7 mt-2">
                                <?php
                                $numberOfPrefer = DB::table('preferences')->count();
                                ?>
                                <h4 class="text-danger"><?php echo $numberOfPrefer; ?> ประเภท</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-body">
                        <canvas id="chartLine"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger" style="color: white;">
                        Top Reviews
                    </div>
                    <div class="card-body">


                        <?php
                        $topReviewedLocations = DB::table('locations')->select('locations.location_id', 'locations.location_name', DB::raw('COUNT(reviews.review_id) as total_reviews'), DB::raw('AVG(reviews.rating) as average_rating'))->join('reviews', 'locations.location_id', '=', 'reviews.location_id')->groupBy('locations.location_id', 'locations.location_name')->orderByDesc('total_reviews')->get();
                        
                        ?>
                        @foreach ($topReviewedLocations as $item)
                            <div class="row">
                                <div class="col-sm-9" style="font-size: 13px;">
                                    <b>{{ Str::limit($item->location_name, 20) }}</b>
                                </div>
                                <div class="col-sm-3" style="font-size: 12px;">
                                    <b>{{ $item->total_reviews }} รีวิว</b>
                                </div>
                                <div class="col-sm-7" style="font-size: 13px;">
                                    Rating
                                </div>
                                <div class="col-sm-5" style="font-size: 12px;">
                                    @if ($item->average_rating <= 1)
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

                                    @if ($item->average_rating >= 1.5 && $item->average_rating <= 1.9)
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

                                    @if ($item->average_rating >= 2 && $item->average_rating <= 2.4)
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

                                    @if ($item->average_rating >= 2.5 && $item->average_rating <= 2.9)
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

                                    @if ($item->average_rating >= 3 && $item->average_rating <= 3.4)
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

                                    @if ($item->average_rating >= 3.5 && $item->average_rating <= 3.9)
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

                                    @if ($item->average_rating >= 4 && $item->average_rating <= 4.4)
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

                                    @if ($item->average_rating >= 4.5 && $item->average_rating <= 4.9)
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

                                    @if ($item->average_rating >= 5)
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
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>


    <?php
    $membersData = DB::table('members')->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))->orderBy('year')->orderBy('month')->get();
    
    ?>

    <script>
        // Line Chart Data
        // Line Chart Data
        var chartLine_Data = {
            labels: <?php echo json_encode(
                array_map(function ($data) {
                    return date('M', mktime(0, 0, 0, $data->month, 1));
                }, $membersData->toArray()),
            ); ?>,
            datasets: [{
                label: '# New Members',
                fill: false,
                data: <?php echo json_encode($membersData->pluck('count')->toArray()); ?>,
                backgroundColor: ['red', 'blue', 'yellow', 'green', 'purple', 'orange'],
                borderColor: 'black',
                borderWidth: 1,
                pointRadius: 5,
            }]
        };

        // Find max and min values
        var maxCount = Math.max(...chartLine_Data.datasets[0].data);
        var minCount = Math.min(...chartLine_Data.datasets[0].data);

        // Find corresponding months
        var maxMonth = chartLine_Data.labels[chartLine_Data.datasets[0].data.indexOf(maxCount)];
        var minMonth = chartLine_Data.labels[chartLine_Data.datasets[0].data.indexOf(minCount)];

        // Line Chart
        var chartLine = document.getElementById('chartLine').getContext('2d');
        var chartLine_options = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Month with Most and Least Registrations: ' + maxMonth + ' (Most) / ' + minMonth + ' (Least)',
                    position: 'top',
                    font: {
                        size: 16
                    }
                }
            }
        };

        if (chartLine) {
            new Chart(chartLine, {
                type: 'line',
                data: chartLine_Data,
                options: chartLine_options,
            });
        }
    </script>



    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
