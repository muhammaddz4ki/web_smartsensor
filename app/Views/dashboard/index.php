<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <!-- FAVICON -->
    <link rel="shortcut icon" href="<?= base_url('assets/icon/polmanLogo.png') ?>" type="image/x-icon" />

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>" />

    <!-- Swiper Js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <title>Project 2AEC2</title>
</head>

<body>
    <!-- NAVBAR SECTION -->
<nav id="navbar">
    <div class="navbar container">
        <a href="<?= base_url('/') ?>" class="nav__logo">
            <img src="<?= base_url('assets/icon/polmanLogo.png') ?>" alt="" />
            <h2>PROJECT 2AEC2</h2>
        </a>
        <div class="nav__menu">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#solutions">Solutions</a></li>
                <li><a href="#contact">Contact</a></li>
                <?php if(session()->get('user_id')): ?>
                    <li class="user-greeting">
                        <span>Hello, <?= esc(session()->get('username')) ?></span>
                    </li>
                    <li><a href="<?= site_url('logout') ?>">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?= site_url('login') ?>">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

    <!-- MAIN SECTION -->
    <main>
        <section class="home__page" id="home">
            <div class="home container">
                <div class="home__title">
                    <h1>
                        Manage Your Business <br />
                        Easier
                        <span>with Our <br />
                            IoT Products and Solutions</span>
                    </h1>
                    <p>
                        Control and manage multiple devices anywhere to support your
                        business. Solve challenges and unlock endless IoT possibilities.
                    </p>
                    <div class="btn_banner">
                        <a href="#services">Get Started</a>
                    </div>
                </div>
                <div class="home__img">
                    <img src="<?= base_url('assets/images/Banner.png') ?>" alt="" />
                </div>
            </div>
        </section>

        <section class="services__page" id="services">
            <div class="services container">
                <div class="services__title">
                    <h1>Our <span>Services</span></h1>
                    <p>
                        We provide your business with solutions through multiple devices,
                        connectivity options, <br />
                        and integrated platforms to make your work more efficient.
                    </p>
                </div>
                <div class="services__card">
                    <a href="<?= site_url('monitoring') ?>" class="card">
                        <img src="<?= base_url('assets/images/ultrasonik.png') ?>" alt="" />
                        <h1>Sensor Ultrasonik</h1>
                        <p>
                            Support your devices with our IoT innovation compatible with
                            LoRa, WiFi, and Bluetooth Connectivity.
                        </p>
                    </a>
                    <a href="<?= site_url('monitoring2') ?>" class="card">
                        <img src="<?= base_url('assets/images/ldr.png') ?>" alt="" />
                        <h1>Sensor DHT11</h1>
                        <p>
                            Support your devices with our IoT innovation compatible with
                            LoRa, WiFi, and Bluetooth Connectivity.
                        </p>
                    </a>
                    <a href="<?= site_url('monitoring3') ?>" class="card">
                        <img src="<?= base_url('assets/images/dht11.png') ?>" alt="" />
                        <h1>Sensor LDR</h1>
                        <p>
                            Support your devices with our IoT innovation compatible with
                            LoRa, WiFi, and Bluetooth Connectivity.
                        </p>
                    </a>
                </div>
            </div>
        </section>
        
                <section class="solutions__page" id="solutions">
            <div class="solutions container">
                <div class="solutions__title">
                    <h1>Solution <span>for Your Project</span></h1>
                    <p>
                        We provide your business with solutions through multiple devices,
                        connectivity options, <br />
                        and integrated platforms to make your work more efficient.
                    </p>
                </div>
                <div class="solutions_content">
                    <div class="sol__img">
                        <img src="<?= base_url('assets/images/Banner2.png') ?>" alt="" />
                    </div>
                    <div class="sol__banner"></div>
                </div>
            </div>
        </section>

        <section class="stats__page">
            <div class="stats container">
                <div class="stats__cards">
                    <div class="stat__card">
                        <h2>Total Devices</h2>
                        <p><?= isset($totalDevices) ? $totalDevices : 0 ?></p>
                    </div>
                    <div class="stat__card">
                        <h2>Total Applications</h2>
                        <p><?= isset($totalApplications) ? $totalApplications : 0 ?></p>
                    </div>
                    <div class="stat__card">
                        <h2>Active Devices</h2>
                        <p><?= isset($activeDevices) ? $activeDevices : 0 ?></p>
                    </div>
                </div>
            </div>
        </section>
        <section class="contact__page" id="contact">
            <div class="contact container">
                <div class="contact__content">
                    <div class="contact__form">
                        <h1>Get in <span>touch</span></h1>
                        <form action="" method="post">
                            <input type="text" placeholder="Your Name" name="name" />
                            <input type="email" placeholder="Your Email" name="email" />
                            <textarea name="message"></textarea>
                            <button type="submit">Submit</button>
                        </form>
                    </div>
                    <div class="contact__banner">
                        <img src="<?= base_url('assets/images/Banner3.png') ?>" alt="" />
                    </div>
                </div>
            </div>
        </section>

    <!-- FOOTER SECTION -->
    <footer class="container">
        <div class="logo__footer">
            <img src="<?= base_url('icon/polmanLogo2.png') ?>" alt="" />
            <div class="menu__footer">
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-solid fa-globe"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                <a href="#"><i class="fa-solid fa-phone"></i></a>
            </div>
        </div>
        <p>&copy; 2024 AEC22. All rights reserved.</p>
    </footer>
    <script src="<?= base_url('dist/js/main.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>

</html>
