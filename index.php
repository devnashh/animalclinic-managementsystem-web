<!DOCTYPE html>
<html lang="en">
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>QUALIPAWS Animal Health Clinic</title>
    <link rel="shortcut icon" href="landing_page/images/Qplogo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link href="landing_page/css/index.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar.shrink {
            padding: 1px 0;
        }

        .service-header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .service-front {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-1" style="background-color: #fff;" id="topNav">
        <div class="container-fluid">
            <div class="navbar-brand d-flex align-items-center justify-content-between fs-2 w-100" href="#">
                <div class="d-flex align-items-center fw-semibold">
                    <img src="landing_page/images/Qplogo.png" alt="Logo" width="70" class="me-2 d-none d-lg-block"
                        style="background-color: white; border-radius: 50%;">
                    <span class="text-primary fs-1 d-none d-lg-block">Quali<span class="text-success">paws</span> Animal
                        Health Clinic</span>
                    <span class="text-primary fs-6 d-block d-lg-none">Quali<span class="text-success">paws</span> Animal
                        Health Clinic</span>
                </div>
                <!-- Navbar Toggler -->
                <div>
                    <button class="btn btn-primary btn-sm d-block d-lg-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon fw-bold"></span>
                    </button>
                </div>
            </div>

            <div class="collapse navbar-collapse justify-content-end px-4" id="navbarNav">
                <ul class="navbar-nav text-center fs-5">
                    <li class="nav-item"><a class="nav-link btn btn-info text-dark px-2 py-1 me-2" href="#">Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link btn btn-info text-dark px-2 py-1 me-2"
                            href="#services">Services</a>
                    </li>
                    <li class="nav-item"><a class="nav-link btn btn-info text-dark px-2 py-1 me-2"
                            href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-info text-dark px-2 py-1 me-2"
                            href="#contact">Contact</a>
                    </li>
                    <li class="nav-item"><a class="nav-link btn btn-success text-success fw-bold px-2 py-1 me-2"
                            href="accounts/signin.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-2 p-lg-5 mt-lg-5" id="background">
        <div class="row my-5 py-5">
            <div class="col-lg-6 mt-lg-5">
                <h1 class="fw-bold lh-1 text-success text-center text-lg-start" id="welcome"><span
                        class="fs-3 text-primary">Welcome to</span><br /> Qualipaws</h1>
                <p class="mt-4 fs-4 text-center text-lg-start fw-semibold text-info d-none d-lg-block">
                    Trusted Care for Your Pets, Fast, Easy, and Convenient Booking! <br />
                </p>
                <p class="mt-4 fs-5 text-center text-lg-start fw-semibold text-info d-block d-lg-none">
                    Trusted Care for Your Pets, Fast, Easy, and Convenient Booking! <br />
                </p>
                <span class="mt-4 fs-3 text-center text-lg-start fw-bold text-info d-none d-lg-block">Your Pet Deserves Quality Care</span>
                <span class="mt-4 fs-5 text-center text-lg-start fw-bold text-info d-block d-lg-none">Your Pet Deserves Quality Care</span>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0 mt-5">
                <video class="video border shadow" autoplay loop muted>
                    <source src="landing_page/Videos/QPV.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>

    <div class="p-5 my-5" id="services"></div>

    <div class="mt-5 services">
        <h1 class="fw-bold text-center mt-5"><i class="fa-solid fa-shield-dog me-2"></i>SERVICES WE PROVIDE</h1>
        <div class="d-flex justify-content-center">
            <div class="container row mt-5 p-0">
                <div class="col-lg-4 service-item p-0 m-0 d-flex justify-content-center align-items-center">
                    <div class="service-item-inner">
                        <div class="service-front p-5" data-title="Surgery">
                            <h3 class="service-header">Surgery</h3>
                            <img src="landing_page/images/Surgery.png" alt="Surgery Image">
                        </div>
                        <div class="service-back">
                            <h2>Surgery</h2>
                            <p>Our clinic offers a variety of surgical services, including routine spaying and
                                neutering, as well as more complex procedures.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 service-item p-0 m-0 d-flex justify-content-center align-items-center">
                    <div class="service-item-inner">
                        <div class="service-front" data-title="Grooming">
                            <h3 class="service-header">Grooming</h3>
                            <img src="landing_page/images/Grooming.png" alt="Grooming Image">
                        </div>
                        <div class="service-back">
                            <h2>Grooming</h2>
                            <p>We offer professional grooming services to keep your pets looking their best, including
                                baths, haircuts, and nail trimming.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 service-item p-0 m-0 d-flex justify-content-center align-items-center">
                    <div class="service-item-inner">
                        <div class="service-front" data-title="Deworming">
                            <h3 class="service-header">Deworming</h3>
                            <img src="landing_page/images/Deworming.png" alt="Deworming Image">
                        </div>
                        <div class="service-back">
                            <h2>Deworming</h2>
                            <p>Our deworming treatments protect your pet from harmful parasites, preventing infections
                                that can impact their health.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 service-item p-0 m-0 d-flex justify-content-center align-items-center">
                    <div class="service-item-inner">
                        <div class="service-front" data-title="Vaccination">
                            <h3 class="service-header">Vaccination</h3>
                            <img src="landing_page/images/Vaccination.png" alt="Vaccination Image">
                        </div>
                        <div class="service-back">
                            <h2>Vaccination</h2>
                            <p>Protect your pet's health with our comprehensive vaccination services tailored to your
                                pet's needs.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 service-item p-0 m-0 d-flex justify-content-center align-items-center">
                    <div class="service-item-inner">
                        <div class="service-front" data-title="Home Service">
                            <h3 class="service-header">Home Service</h3>
                            <img src="landing_page/images/HomeService.png" alt="Home Service Image">
                        </div>
                        <div class="service-back">
                            <h2>Home Service</h2>
                            <p>Qualipaws provides home services for consultations, vaccinations, and basic treatments,
                                offering quality veterinary care in the comfort of your pet's home.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 service-item p-0 m-0 d-flex justify-content-center align-items-center">
                    <div class="service-item-inner">
                        <div class="service-front" data-title="Consultation">
                            <h3 class="service-header">Consultation</h3>
                            <img src="landing_page/images/Consultation.png" alt="Consultation Image">
                        </div>
                        <div class="service-back">
                            <h2>Consultation</h2>
                            <p>Qualipaws offers expert consultations to assess your pet's health, address concerns, and
                                provide personalized care plans, ensuring their well-being.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="about-section" id="about">
        <div class="about-container">

                <div class="about-header ">
                    <h1 class="fw-bold text-center mt-5"><i class="fa-solid fa-shield-cat me-2"></i>About Qualipaws</h1>
                    <p class="text-center">Dedicated to providing exceptional care for your beloved pets</p>
                </div>
                <div class="d-flex justify-content-center align-items-center mt-5">
                <div class="container row p-5 rounded-lg shadow">
                    <div class="col-lg-4 bg-liht">
                        <img src="landing_page/images/Qplogo.png" alt="Our Clinic" class="img-fluid object-fit-cover">
                    </div>
                    <div class="col-lg-8 p-lg-5 mt-5 mt-lg-0 text-center text-lg-start">
                        <h2 class="fw-bold">Our Story</h2>
                        <p class="mt-2">Qualipaws Animal Health Clinic, established in January 2021 in San Jose Del Monte, Bulacan,
                            provides essential pet healthcare services for dogs and cats. Staffed by a team of seven,
                            including a resident veterinarian, the clinic offers consultations, surgeries, grooming,
                            vaccinations, deworming, and pet supplies. Home services are also available for added
                            convenience. Service hours are 9 AM to 6 PM, with consultations until 5 PM and grooming
                            until 3 PM. Fridays are tentative based on the veterinarian's availability.</p>
                    </div>
                </div>
            </div>

            <div class="about-content">
                <div class="about-info row d-none">
                    <div class="col-4">
                        <img src="landing_page/images/paw.png" alt="Our Clinic">
                    </div>
                    <div class="section-text col-8">
                        <h2>Our Story</h2>
                        <p>Qualipaws Animal Health Clinic, established in January 2021 in San Jose Del Monte, Bulacan,
                            provides essential pet healthcare services for dogs and cats. Staffed by a team of seven,
                            including a resident veterinarian, the clinic offers consultations, surgeries, grooming,
                            vaccinations, deworming, and pet supplies. Home services are also available for added
                            convenience. Service hours are 9 AM to 6 PM, with consultations until 5 PM and grooming
                            until 3 PM. Fridays are tentative based on the veterinarian's availability.</p>
                    </div>
                </div>

                <!-- Chatbot Button -->
                <button class="chatbot-button" id="chatbotToggle">
                    <i class="bi bi-chat-dots"></i>
                </button>
                <!-- Chatbot Window -->
                <div class="chatbot-window" id="chatbotWindow">
                    <div class="chatbot-header">Qualipaws Chatbot</div>
                    <div class="chatbot-body" id="chatbotBody">
                        <div class="text-muted">
                            <h5>Hello paw parents! <i class="fa fa-paw"></i> I am Qualipaws chatbot, Ask a Question?
                            </h5>
                            <!-- Dog and Cat icons -->
                            <div style="display: flex; justify-content: center; align-items: center; margin-top: 10px;">
                                <i class="fa fa-dog" style="font-size: 24px; margin-right: 10px;"></i>
                                <i class="fa fa-cat" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="chatbot-footer">
                        <input type="text" id="chatbotInput" placeholder="Type a question...">
                        <button id="chatbotSend">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="contact-section" id="contact">
        <div class="contact-container">
            <div class="contact-header">
                <h1>Contact Us</h1>
                <p>We're here to help with all your pet care needs</p>
            </div>

            <div class="contact-content">
                <div class="contact-info">
                    <div class="info-item">
                        <i class="fas fa-location-dot"></i>
                        <div class="info-text">
                            <h3>Location</h3>
                            <p>F. Halili National road, Barangay. Tungkong Mangga<br> City of San Jose Del monte
                                Bulacan, San Jose del Monte, Philippines</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div class="info-text">
                            <h3>Phone</h3>
                            <p>0932 375 9347</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div class="info-text">
                            <h3>Email</h3>
                            <p>qualipawssjdm@gmail.com</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div class="info-text">
                            <h3>Hours</h3>
                            <p>Mon-Sat: 8:00 AM - 8:00 PM<br>Sunday: 9:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                </div>

                <div class="contact-form">
                    <h2>Send us a Message</h2>
                    <form id="contactForm">
                        <div class="form-group">
                            <input type="text" id="name" name="name" required placeholder="Your Name">
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" required placeholder="Your Email">
                        </div>
                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" placeholder="Your Phone">
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" required placeholder="Your Message"></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="landing_page/js/chatbot.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.innerWidth <= 767) {
                const serviceItems = document.querySelectorAll('.service-item');

                serviceItems.forEach(item => {
                    item.addEventListener('click', function () {
                        this.classList.toggle('flipped');

                        // Remove flipped class from other items
                        serviceItems.forEach(otherItem => {
                            if (otherItem !== this) {
                                otherItem.classList.remove('flipped');
                            }
                        });
                    });
                });
            }
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            window.addEventListener("scroll", function () {
                let navbar = document.querySelector(".navbar");
                if (window.scrollY > 50) { // Adjust the threshold as needed
                    navbar.classList.add("shrink");
                } else {
                    navbar.classList.remove("shrink");
                }
            });
        });
    </script>

    <script>
        let navbar = document.querySelector("#topNav");

        window.addEventListener("scroll", function () {
            if (window.scrollY > 0) {
                navbar.style.transition = "all 0.2s ease-in-out";
                navbar.classList.remove("py-0");
                navbar.classList.add("py-0");
                navbar.classList.add("shadow");
                navbar.style.backgroundColor = "#B6DEFF";
            } else {
                navbar.style.transition = "all 0.2s ease-in-out";
                navbar.classList.remove("py-0");
                navbar.classList.add("py-0");
                navbar.classList.remove("shadow");
                navbar.style.backgroundColor = "#fff";
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>