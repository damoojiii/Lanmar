<?php
include("connection.php");

// Fetch background image
$sqlBackground = "SELECT image FROM settings_admin LIMIT 1"; 
$resultBackground = $conn->query($sqlBackground);
$backgroundImageUrl = '';

if ($resultBackground->num_rows > 0) {
    $row = $resultBackground->fetch_assoc();
    $backgroundImageUrl = $row['image'];
}

// Fetch gallery images
$sqlGallery = "SELECT image FROM gallery"; 
$resultGallery = $conn->query($sqlGallery);
$galleryImages = [];

if ($resultGallery->num_rows > 0) {
    while ($row = $resultGallery->fetch_assoc()) {
        $galleryImages[] = $row['image'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Lanmar Resort Homepage</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    @font-face {
        font-family: 'nautigal';
        src: url(font/TheNautigal-Regular.ttf);
    }
    .sitename{
      font-family: 'nautigal';
    }
    .hero {
        position: relative;
        overflow: hidden;
        background-size: cover; /* Ensure the image covers the entire section */
        background-position: center; /* Center the image */
    }

    .bg-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .bg-container .bg {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .booking-about {
      padding: 80px 0;
      background-color: #f8f9fa;
    }

    .booking-container {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .booking-container h3 {
      color: #333;
      margin-bottom: 1.5rem;
    }

    .about-content {
      padding: 2rem;
      height: 100%;
    }

    .about-content h3 {
      color: #333;
      margin-bottom: 1.5rem;
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
    }

    .btn-outline-light {
      border-color: #fff;
      transition: all 0.3s ease;
      padding: 0.5rem 1.5rem;
    }

    .btn-outline-light:hover {
      color: #fff !important;
      background-color: #0056b3;
      border-color: #0056b3;
    }

    @media (max-width: 991px) {
      .booking-container, .about-content {
        margin-bottom: 2rem;
      }
      .btn-outline-light {
        min-width: 100px;
        padding: 0.4rem 1rem;
      }
    }

    /** Gallery */
  
    .hero {
        position: relative;
        overflow: hidden;
    }

    .slideshow-container {
      position: relative;
      max-width: 100%;
      margin: auto;
    }

    .mySlides {
      display: none;
    }

    .prev, .next {
      cursor: pointer;
      position: absolute;
      top: 50%;
      width: auto;
      padding: 16px;
      color: white;
      font-weight: bold;
      font-size: 18px;
      transition: 0.6s ease;
      border-radius: 0 3px 3px 0;
      user-select: none;
    }

    .next {
      right: 0;
      border-radius: 3px 0 0 3px;
    }

    .prev:hover, .next:hover {
      background-color: rgba(0,0,0,0.8);
    }

    .gallery .container-fluid {
      overflow: hidden;
    }
    .slideshow-container {
      width: 100vw;
      position: relative;
      left: 50%;
      right: 50%;
      margin-left: -50vw;
      margin-right: -50vw;
    }
    .mySlides img {
      width: 100%;
      height: 70vh;
      object-fit: cover;
    }
  </style>

  </style>
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <h1 class="sitename">Lanmar Resort</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#accomo">Accommodations</a></li>
          <li><a href="#gallery">Gallery</a></li>
          <li><a href="#portfolio">Amenities</a></li>
          <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="login.php" class="btn btn-outline-light">Sign In</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->                  
    <section id="hero" class="hero section dark-background" style="background-image: url('<?php echo htmlspecialchars($backgroundImageUrl); ?>');">
        <div class="container text-center" data-aos="fade-up" data-aos-delay="100">
            <!-- Your content here -->
        </div>
    </section><!-- /Hero Section -->

     <!-- Booking and About Section -->
     <section id="booking-about" class="booking-about section" style="color: black !important; background-color: white !important;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="booking-container p-4 mb-5">
              <h3 class="text-center mb-3">Book Your Stay</h3>
              <form>
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="checkin" class="form-label">Check-in Date</label>
                    <input type="date" class="form-control" id="checkin" required onchange="calculateDays()">
                  </div>
                  <div class="col-md-6">
                    <label for="checkout" class="form-label">Check-out Date</label>
                    <input type="date" class="form-control" id="checkout" required onchange="calculateDays()">
                  </div>
                  <div class="col-12 mt-2">
                    <p id="daysDisplay" class="text-center fw-bold" style="color: #007bff;"></p>
                  </div>
                  <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary">Book Now</button>
                  </div>
                </div>
              </form>
            </div>
            
            <div class="about-content">
              <h2 class="text-center mb-4">About Our Resort</h2>
              <p style="text-indent: 2em; text-align: justify;">
                Welcome to our luxurious resort, where tranquility meets adventure. Nestled in a picturesque location, our resort offers a perfect blend of comfort, elegance, and natural beauty. Whether you're seeking a romantic getaway, a family vacation, or a solo retreat, we have something for everyone. Our carefully curated experiences cater to diverse tastes, ensuring that each guest finds their own slice of paradise within our grounds. From serene spa treatments to exhilarating outdoor activities, every moment at our resort is designed to create lasting memories and provide the ultimate escape from the everyday.
              </p>
              <p style="text-indent: 2em; text-align: justify;">
                Indulge in our world-class amenities, savor exquisite cuisine, and immerse yourself in the stunning surroundings. Our dedicated staff is committed to ensuring your stay is nothing short of extraordinary. Come and experience the magic of our resort - your paradise awaits!
              </p>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /Booking and About Section -->

        <!-- Gallery Section -->
        <section id="gallery" class="gallery section py-2">
      <div class="container-fluid px-0">
        <div class="section-header text-center mb-4">
          <h2>Our Gallery</h2>
          <p>Explore our beautiful resort through these images</p>
        </div>

        <div class="slideshow-container">
          <?php foreach ($galleryImages as $index => $image): ?>
          <div class="mySlides">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="Gallery image <?php echo $index + 1; ?>">
          </div>
          <?php endforeach; ?>

          <!-- Next and previous buttons -->
          <a class="prev" onclick="moveSlide(-1)">&#10094;</a>
          <a class="next" onclick="moveSlide(1)">&#10095;</a>
        </div>

      </div>
    </section><!-- End Gallery Section -->

    <!-- Room Showcase Section -->
    <section id="room-showcase" class="room-showcase section">
      <div class="container">
        <div class="section-header text-center">  
          <h2>Featured Room</h2>
          <p>Experience luxury and comfort in our signature accommodation</p>
        </div>
            
        <div class="row gy-4 justify-content-center">
          <div class="col-lg-6">
            <div class="room-image">
              <?php
                // Add error reporting for debugging
                error_reporting(E_ALL);
                ini_set('display_errors', 1);

                // Verify connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $query = "SELECT * FROM rooms WHERE is_featured = 1 LIMIT 1";
                $result = mysqli_query($conn, $query);

                if (!$result) {
                    die('Query Error: ' . mysqli_error($conn));
                }

                $row = mysqli_fetch_assoc($result);
                
                if ($row) {
                  $room_name = htmlspecialchars($row['room_name']);
                  $image_path = htmlspecialchars($row['image_path']);
                  echo '<img src="' . $image_path . '" class="img-fluid" alt="' . $room_name . '">';
                } else {
                  echo '<p>No featured room available at the moment.</p>';
                  // Debug output
                  echo '<p>Debug: No rows returned from query</p>';
                }
              ?>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="room-description">
              <?php
              if (isset($row) && $row) {
                echo '<h3>' . htmlspecialchars($row['room_name']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<ul class="room-features list-unstyled">';
                echo '<li><i class="bi bi-check-circle"></i> Capacity: ' . htmlspecialchars($row['capacity']) . ' persons</li>';
                echo '</ul>';
              } else {
                echo '<p>No featured room available at the moment.</p>';
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End Room Showcase Section -->

    
  </main>

  <footer id="footer" class="footer dark-background">
    <div class="container">
      <h3 class="sitename">Lanmar Resort</h3>
      <p>A Premuim Private Resort</p>
      <div class="social-links d-flex justify-content-center">
        <a href="https://www.facebook.com/lanmarresort"><i class="bi bi-facebook"></i></a>
        
      </div>
      <div class="container">
        <div class="copyright">
          <span>Copyright</span> <strong class="px-1 sitename">Lanmar</strong> <span>All Rights Reserved</span>
        </div>
        <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you've purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
          Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
      </div>
    </div>
  </footer>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>
    function calculateDays() {
      const checkin = new Date(document.getElementById('checkin').value);
      const checkout = new Date(document.getElementById('checkout').value);
      const daysDisplay = document.getElementById('daysDisplay');

      if (checkin && checkout && checkout > checkin) {
        const diffTime = Math.abs(checkout - checkin);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        daysDisplay.textContent = `You're booking for ${diffDays} day${diffDays > 1 ? 's' : ''}.`;
      } else {
        daysDisplay.textContent = '';
      }
    }
  </script>

  <script>
  let slideIndex = 1;
  showSlides(slideIndex);

  function moveSlide(n) {
    showSlides(slideIndex += n);
  }

  function currentSlide(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    let i;
    const slides = document.getElementsByClassName("mySlides");
    const dots = document.getElementsByClassName("dot");
    
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
    }
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }
    
    slides[slideIndex - 1].style.display = "block";  
    dots[slideIndex - 1].className += " active";
  }

  // Optional: Automatic slideshow
  setInterval(() => {
    moveSlide(1);
  }, 5000); // Change image every 5 seconds
  </script>


</body>

</html>
