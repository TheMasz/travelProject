<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>เกี่ยวกับเรา</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
</head>

<body>
    <x-loading />
    <x-navbar />
    <div class="container">
        <div class="content-wrap">
            <div class="aboutour-section">
                <div class="details">
                    <h3>เกี่ยวกับเรา</h3>
                    <p>เที่ยวปะ เป็นแพลตฟอร์มการท่องเที่ยวที่เชื่อมโยงคนและประสบการณ์
                        ท่องเที่ยวที่น่าสนใจทั่วโลก ด้วยการใช้เทคโนโลยีและข้อมูลที่ประณีต
                        เรามุ่งมั่นที่จะให้ประสบการณ์การเดินทางที่ไม่ซ้ำซาก
                        และตอบสนองความต้องการของผู้ใช้ทุกคน
                    </p>
                </div>
                <div class="image">
                    <img src="/images/about1.png" alt="Image by Freepik">
                </div>
            </div>
            <div class="features-section">
                <div class="image">
                    <img src="/images/about2.png" alt="Image by storyset on Freepik">
                </div>
                <div class="details">
                    <h3>เทคโนโลยีและคุณสมบัติเด่น</h3>
                    <ul>
                        <li>Personalization: การปรับแต่งแบบที่แตกต่างกันตามความต้องการและสิ่งที่คุณชื่นชอบ</li>
                        <li>Real-time Updates: ข้อมูลแนะนำที่เป็นปัจจุบัน</li>
                    </ul>
                </div>
            </div>
            <div class="ourteam-section">
                <h3>ทีมของเรา</h3>
                <div class="card-wrap">
                    <div class="card">
                        <div class="detail">
                            <p>ธนวันต์ วงศาวดี</p>
                            <p class="font-sm">t.tanawan.w@gmail.com</p>
                            <div class="row flex-wrap mt-1">
                                <div class="tag green">
                                    FullStack
                                </div>
                                <div class="tag purple">
                                    Design
                                </div>
                                <div class="tag red">
                                    Database
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="detail">
                            <p>อ.สุภัทรา เกิดเมฆ</p>
                            <p class="font-sm">xxx@gmail.com</p>
                            <div class="row flex-wrap mt-1">
                                <div class="tag red">
                                    Advisor
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="detail">
                            <p>เอกรัตน์ เจียมตุรัส</p>
                            <p class="font-sm">xxx@gmail.com</p>
                            <div class="row flex-wrap mt-1">
                                <div class="tag green">
                                    FullStack
                                </div>
                                <div class="tag purple">
                                    Design
                                </div>
                                <div class="tag red">
                                    Database
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
