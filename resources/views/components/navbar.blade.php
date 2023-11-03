<header class="navbar">
    <div class="container">
        <nav class="nav row">
            <div class="lt">
                <ul class="nav-list">
                    <li>
                        <a href="/">
                            <h1>Logo</h1>
                        </a>
                    </li>
                    <li><a href="/">หน้าหลัก</a></li>
                    <li><a href="/about">เกี่ยวกับเรา</a></li>
                </ul>
            </div>
            <div class="rt">
                <ul class="nav-list">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">
                            {{ app('getUsername')(session('member_id')) }}
                        </a>
                        <div class="dropdown-menu">
                            <!-- Add dropdown items here -->
                            <a class="dropdown-item" href="/profile">โปรไฟล์</a>
                            <a class="dropdown-item" href="/settings">การตั้งค่า</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/logout">ออกจากระบบ</a>
                        </div>
                    </li>
                    <li>
                        <a href="/basket">
                            <span class="material-icons">
                                luggage
                            </span>
                            <span id="cartLength">
                                0
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            const dropdown = navbar.querySelector('.dropdown-toggle');
            dropdown.addEventListener('click', function(event) {
                event.preventDefault();
                const dropdownMenu = navbar.querySelector('.dropdown-menu');
                dropdownMenu.classList.toggle('open');
            });

            const navbarTop = navbar.offsetTop;
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > navbarTop) {
                    navbar.classList.add('fixed');
                } else {
                    navbar.classList.remove('fixed');
                }
            });
        });
    </script>
</header>