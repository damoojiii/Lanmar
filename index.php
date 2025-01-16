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
try {
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", "$username", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

  // Fetch descriptions from the database
$sqlDescriptions = "SELECT description, description_2 FROM about LIMIT 1"; 
$resultDescriptions = $conn->query($sqlDescriptions);
$descriptions = [];

  if ($resultDescriptions->num_rows > 0) {
      $row = $resultDescriptions->fetch_assoc();
      $descriptions['description'] = $row['description'];
      $descriptions['description_2'] = $row['description_2'];
  } else {
      $descriptions['description'] = "Default description 1 if not found.";
      $descriptions['description_2'] = "Default description 2 if not found.";
  }

  // Query featured feedbacks
  $featuredQuery = "SELECT f.feedback_id, f.comment, f.rating, f.is_featured, f.created_at, 
  u.firstname, u.lastname 
  FROM feedback_tbl f
  JOIN users u ON f.user_id = u.user_id
  WHERE f.is_featured = 1 
  ORDER BY f.created_at DESC";
  $featuredStmt = $pdo->query($featuredQuery);
  $featuredFeedbacks = $featuredStmt->fetchAll(PDO::FETCH_ASSOC);

  // Query non-featured feedbacks
  $nonFeaturedQuery = "SELECT f.feedback_id, f.comment, f.rating, f.is_featured, f.created_at, 
  u.firstname, u.lastname 
  FROM feedback_tbl f
  JOIN users u ON f.user_id = u.user_id
  WHERE f.is_featured = 0 
  ORDER BY f.created_at DESC";
  $nonFeaturedStmt = $pdo->query($nonFeaturedQuery);
  $nonFeaturedFeedbacks = $nonFeaturedStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Lanmar</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/" rel="icon">
  <link href="assets/img/" rel="apple-touch-icon">

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    @font-face {
        font-family: 'nautigal';
        src: url(font/TheNautigal-Regular.ttf);
    }
    .sitename{
      font-family: 'nautigal';
      font-size: 40px;
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
      overflow: hidden;
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

    .room-slideshow-container {
        position: relative;
        max-width: 1200px;
        margin: auto;
        background: white;
        padding: 5px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .room-slide {
        display: none;
        padding: 5px;
    }

    .room-prev, .room-next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        padding: 16px;
        margin-top: -22px;
        color: #333;
        font-weight: bold;
        font-size: 24px;
        border-radius: 0 3px 3px 0;
        user-select: none;
        background-color: rgba(255,255,255,0.8);
        transition: 0.3s ease;
    }

    .room-next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    .room-prev:hover, .room-next:hover {
        background-color: rgba(0,0,0,0.8);
        color: white;
    }

    .room-dot {
        cursor: pointer;
        height: 12px;
        width: 12px;
        margin: 0 5px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .room-dot.active, .room-dot:hover {
        background-color: #717171;
    }

    .fade {
        animation-name: fade;
        animation-duration: 5s;
    }

    @keyframes fade {
        from {opacity: .1}
        to {opacity: 1}
    }

    .room-description {
        padding: 0 10px;
    }

    .room-description h3 {
        margin: 0 0 5px 0;
    }

    .room-description p {
        margin: 0 0 5px 0;
    }

    .room-features {
        margin: 5px 0;
        padding: 0;
    }

    .room-dots {
        margin-top: 5px;
    }
    .feedback-page {
        padding: 20px;
    }

    .feedback-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: start;
        word-wrap: break-word; 
        word-break: break-word;
    }

    .feedback-card {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        width: calc(33.333% - 20px); 
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .feedback-card h4 {
        margin: 0 0 10px;
        font-size: 18px;
    }

    .rating {
        color: #ffc107;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .feedback-card .feedback-text {
        font-size: 14px;
        color: #555;
        margin-bottom: 15px;
    }

    .button-container {
        display: flex;
        justify-content: flex-end;
        margin-top: auto; 
    }

    .feedback-card button {
        margin: 0;
    }

    .feedback-line {
        margin: 40px 0;
    }
    @media (max-width: 768px) {
        .feedback-card {
            width: calc(50% - 20px); 
        }
        .hero{
          min-height: 50vh;
        }
    }

    @media (max-width: 576px) {
        .feedback-card {
            width: 100%; 
        }
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
          <li><a href="accomodation.php">Accommodations</a></li>
          <li><a href="#gallery">Gallery</a></li>
          <li><a href="#amenities">Amenities</a></li>
          <li><a href="#location">Locate Us</a></li>
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
              <form action="login.php" action="GET">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="date-in" class="form-label">Check-in Date</label>
                    <input id="date-in" class="form-control" type="text" placeholder="Select a date" name="dateIn" readonly required>
                  </div>
                  <div class="col-md-6">
                    <label for="checkout" class="form-label">Check-out Date</label>
                    <input id="date-out" class="form-control" type="text" placeholder="Select check-out date" name="dateOut" readonly required>
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
              <h5 style="text-align: justify;">
                <?php echo htmlspecialchars($descriptions['description']); ?>
              </h5>
              <p style="text-indent: 2em; text-align: justify;">
                <?php echo htmlspecialchars($descriptions['description_2']); ?>
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
          <div class="mySlides fade">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="Gallery image <?php echo $index + 1; ?>" style="width:100%">
          </div>
          <?php endforeach; ?>

          <!-- Next and previous buttons -->
          <a class="prev" onclick="moveSlide(-1)">&#10094;</a>
          <a class="next" onclick="moveSlide(1)">&#10095;</a>
        </div>
      </div>
    </section>

    <!-- End Gallery Section -->

    <!-- Room Showcase Section -->
    <section id="room-showcase" class="room-showcase section">
        <div class="container">
            <div class="section-header text-center">  
                <h2>Featured Rooms</h2>
                <p>Experience luxury and comfort in our signature accommodations</p>
            </div>
            
            <div class="room-slideshow-container">
                <?php
                // Fetch all rooms
                $query = "SELECT * FROM rooms ORDER BY room_id ASC";
                $result = mysqli_query($conn, $query);

                if (!$result) {
                    die('Query Error: ' . mysqli_error($conn));
                }

                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="room-slide fade">
                        <div class="row">
                            <div class="col-lg-6">
                                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                                     class="img-fluid" 
                                     alt="<?php echo htmlspecialchars($row['room_name']); ?>">
                            </div>
                            <div class="col-lg-6">
                                <div class="room-description">
                                    <h3><?php echo htmlspecialchars($row['room_name']); ?></h3>
                                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                                    <ul class="room-features list-unstyled">
                                        <li><i class="bi bi-check-circle"></i> Minimum Capacity: <?php echo htmlspecialchars($row['minpax']); ?> persons</li>
                                        <li><i class="bi bi-check-circle"></i> Maximum Capacity: <?php echo htmlspecialchars($row['maxpax']); ?> persons</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                
            </div>

            <!-- Dots indicator -->
            <div class="room-dots text-center">
                <?php
                mysqli_data_seek($result, 0); // Reset result pointer
                $index = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<span class="room-dot" onclick="currentRoomSlide(' . ($index + 1) . ')"></span>';
                    $index++;
                }
                ?>
            </div>
        </div>
    </section>

    <section id="feedback" class="feedback">
      <div class="container">
            <div class="section-header text-center">
            <h2>Guest Feedbacks</h2>
            <p>What Our Guests Say</p>
            </div>

            <div class="feedback-container">
                <?php if (!empty($featuredFeedbacks)): ?>
                    <?php foreach ($featuredFeedbacks as $feedback): ?>
                        <div class="feedback-card selected">
                            <h4><?= htmlspecialchars(ucwords($feedback['firstname'] . ' ' . $feedback['lastname'])); ?></h4>
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-solid fa-star" style="color: <?= $i <= $feedback['rating'] ? '#FFD43B' : '#CCC'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p><strong><?= ['Not Good', 'Bad', 'Okay', 'Very Good', 'Amazing'][$feedback['rating'] - 1]; ?></strong></p>
                            <p class="feedback-text"><?= htmlspecialchars($feedback['comment']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No featured feedbacks added yet.</p>
                <?php endif; ?>
            </div>
      </div>
    </section>
    <section id="amenities" class="amenities section">
      <div class="container">
        <div class="section-header text-center">
          <h2 style="color: white;">Our Amenities</h2>
          <p style="color: white;">Comfort and Convenience at Your Fingertips</p>
        </div>

        <div class="row g-4">
          <div class="col-lg-3 col-md-6" data-aos="fade-up">
            <div class="amenity-card text-center">
              <i class="bi bi-wifi fs-1"></i>
              <h4>Free WiFi</h4>
              <p>Stay connected with high-speed internet access throughout your stay</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="amenity-card text-center">
              <i class="bi bi-snow fs-1"></i>
              <h4>Air Conditioning</h4>
              <p>Climate controlled rooms for your comfort in any weather</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="amenity-card text-center">
              <i class="bi bi-8-circle fs-1"></i>
              <h4>Billiards</h4>
              <p>Challenge friends and family to an exciting game of billiards</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="amenity-card text-center">
              <i class="bi bi-water fs-1"></i>
              <h4>Swimming Pool</h4>
              <p>Refreshing pool perfect for relaxation and recreation</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="amenity-card text-center">
              <i class="bi bi-p-circle fs-1"></i>
              <h4>Free Parking</h4>
              <p>Secure parking space available for all guests</p>
            </div>
          </div>
          
          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="amenity-card text-center">
            <i class="bi bi-music-note-beamed fs-1"></i>
              <h4>Videoke</h4>
              <p>Enjoy singing with friends and family with our videoke facility</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="amenity-card text-center">
              <i class="bi bi-shield-check fs-1"></i>
              <h4>24/7 Security</h4>
              <p>Round-the-clock security for your peace of mind</p>
            </div>
          </div>
          
          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="700">
            <div class="amenity-card text-center">
              <i class="bi bi-telephone fs-1"></i>
              <h4>24/7 Support</h4>
              <p>Always available to assist with your needs</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Amenities Css -->
    <style>
      .amenities {
        padding: 60px 0;
        background-color: #19315D;
      }

      .amenity-card {
        background: #fff;
        padding: 30px 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, .5);
        height: 100%;
        transition: transform 0.3s ease;
      }

      .amenity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 10px rgba(0, 0, 0, .5);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
      }

      .amenity-card i {
        color: #19315D;
        margin-bottom: 20px;
      }

      .amenity-card h4 {
        color: #19315D;
        margin-bottom: 15px;
        font-size: 1.2rem;
      }

      .amenity-card p {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
      }
      
    </style>

    <!-- Location Section -->
    <section id="location" class="location section">
      <div class="container">
        <div class="section-title text-center mb-5">
          <h2>Our Location</h2>
          <p>Find us at</p>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-6 mb-4">
            <div class="location-info p-4 bg-white rounded shadow">
              <h3>Address</h3>
              <p><i class="bi bi-geo-alt me-2"></i>Purok 6, Brgy. Poblacion, Madridejos, Cebu</p>
              
              <h3 class="mt-4">Contact Information</h3>
              <p><i class="bi bi-telephone me-2"></i>+63 912 345 6789</p>
              <p><i class="bi bi-envelope me-2"></i>lanmarresort89xzy@gmail.com</p>
              
              <h3 class="mt-4">Operating Hours</h3>
              <p><i class="bi bi-clock me-2"></i>Open 24/7</p>
            </div>
          </div>
          
          <div class="col-lg-6">
            <div class="map-container rounded shadow">
            <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7730.660502155013!2d120.77971866572528!3d14.350289715941608!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33962bafffffffff%3A0xf5abf31d416b2a5!2sLanmar%20Resort!5e0!3m2!1sen!2sph!4v1737045490238!5m2!1sen!2sph"
            width="650"
            height="410"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            </div>
          </div>
        </div>
      </div>
    </section>

    <style>
      .location {
        padding: 60px 0;
        background-color: #f8f9fa;
      }
      .location .section-title {
        margin-bottom: 30px;
      }

      .row {
        display: flex;
        flex-wrap: wrap;
      }
      
      .location-info {
        height: 100%;
      }

      .location-info h3 {
        color: #19315D;
        font-size: 1.5rem;
        margin-bottom: 2rem;
      }

      .location-info p {
        color: #6c757d;
        margin-bottom: 0.5rem;
      }

      .location-info i {
        color: #19315D;
        
      }

      .map-container {
        height: 100%;
      }

      .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
        border-radius: 10px;
      }

      @media (max-width: 768px) {
        .map-container {
          margin-top: 20px;
          min-height: 410px;
        }
      }
    </style>
  



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
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/bootstrap/js/all.min.js"></script>
  <script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>


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
  }, 4000); // Show each slide for 4 seconds total
  </script>

  <script>
  let roomSlideIndex = 1;
  let roomSlideInterval;

  document.addEventListener('DOMContentLoaded', function() {
      showRoomSlides(roomSlideIndex);
      startRoomAutoSlide();
  });

  function startRoomAutoSlide() {
      roomSlideInterval = setInterval(() => {
          moveRoomSlide(1);
      }, 5000); // Show each slide for 4 seconds total
  }

  function moveRoomSlide(n) {
      clearInterval(roomSlideInterval);
      showRoomSlides(roomSlideIndex += n);
      startRoomAutoSlide();
  }

  function currentRoomSlide(n) {
      clearInterval(roomSlideInterval);
      showRoomSlides(roomSlideIndex = n);
      startRoomAutoSlide();
  }

  function showRoomSlides(n) {
      let slides = document.getElementsByClassName("room-slide");
      let dots = document.getElementsByClassName("room-dot");
      
      if (n > slides.length) {roomSlideIndex = 1}
      if (n < 1) {roomSlideIndex = slides.length}
      
      for (let i = 0; i < slides.length; i++) {
          slides[i].style.display = "none";
      }
      for (let i = 0; i < dots.length; i++) {
          dots[i].className = dots[i].className.replace(" active", "");
      }
      
      slides[roomSlideIndex-1].style.display = "block";
      dots[roomSlideIndex-1].className = " active";
  }
  </script>
  <script>
    let slideIndex1 = 0;
    let slideInterval;

    document.addEventListener('DOMContentLoaded', function() {
        showSlides();
        startAutoSlide();
    });

    function startAutoSlide() {
        slideInterval = setInterval(() => {
            moveSlide(1);
        }, 5000); // Change image every 5 seconds
    }

    function moveSlide(n) {
        clearInterval(slideInterval);
        showSlides(slideIndex1 += n);
        startAutoSlide();
    }

    function showSlides() {
        let slides = document.getElementsByClassName("mySlides");

        if (slideIndex1 >= slides.length) {slideIndex1 = 0}    
        if (slideIndex1 < 0) {slideIndex1 = slides.length - 1}

        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";  
        }

        slides[slideIndex1].style.display = "block";  
    }

  </script>
<script>
let fp = '';
let fp1 = '';

const bookedTimeSlots = {}; // Store booked timeslots
const disabledDates = {
  checkIn: [],
  checkOut: []
};

const earliestTime = 6; // 6:00 AM
const earliestTime24hour = 0; // 12:00 AM
const latestTime = 23.5; // 11:30 PM
const minimumStay = 12;
const cleanupTime = 2;

// Fetch todays date
const today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();
const formattedToday = formatDate(today);

const checkInTimeSelect = document.querySelector('select[name="checkin"]');
const checkOutTimeSelect = document.querySelector('select[name="checkout"]');

// Format time in 24-hour format
function formatTime24(date) {
  const hours = date.getHours().toString().padStart(2, '0');
  const minutes = date.getMinutes().toString().padStart(2, '0');
  return `${hours}:${minutes}`;
}

function getNextDay(date) {
  const currentDate = new Date(date);
  currentDate.setDate(currentDate.getDate() + 1); // Move to the next day

  const year = currentDate.getFullYear();
  const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); 
  const day = currentDate.getDate().toString().padStart(2, '0');

  return `${year}-${month}-${day}`;
}

// Function to check if a time is within booked slots
function isTimeBlocked(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      
      const slotStart = slotStartHour * 60 + slotStartMin; 
      const slotEnd = slotEndHour * 60 + slotEndMin;       
      const currentTime = time * 60;                       

      const earliestPossibleCheckIn = slotEnd;
      //console.log(date);
      //console.log((earliestTime <= slotStart || currentTime >= slotStart) && currentTime <= slotEnd);

      // If the current time is within a blocked slot, or if it violates the minimum stay
      if ((earliestTime <= slotStart || currentTime >= slotStart) && currentTime <= slotEnd) {
        return true;  // Time is blocked
      }
      if (currentTime < earliestPossibleCheckIn) {
        return true;  // Time violates the minimum stay rule
      }

      return false;
    });
  }
  return false;
}

// Function to check if a checkout time is blocked due to future bookings
function isCheckoutTimeBlocked(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      
      const slotStart = slotStartHour * 60 + slotStartMin;  // Start time in minutes
      const slotEnd = slotEndHour * 60 + slotEndMin;        // End time in minutes
      const currentTime = Math.round(time * 60);                        // Current time in minutes

      // Calculate cleanup time (2 hours before the next booking starts)
      const nextBookingStartWithCleanup = slotStart - (cleanupTime * 60); // Subtract cleanup period (120 minutes)

      // Block if the current time overlaps with the booking 
      if ((currentTime >= slotStart && currentTime <= slotEnd) || currentTime > nextBookingStartWithCleanup) {
        return true;  // Time is blocked
      }

      return false;
    });
  }
  return false;
}

function hasPreviousDaySpillover(date) {
  const prevDate = new Date(date);
  prevDate.setDate(prevDate.getDate() - 1);
  const formattedPrevDate = prevDate.toISOString().split('T')[0];

  if (bookedTimeSlots[formattedPrevDate]) {
    return bookedTimeSlots[formattedPrevDate].some(slot => {
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      return slotEndHour < earliestTime; // Spillover to the next day if the checkout is before 6 AM
    });
  }
  return false;
}
function hasNextDaySpillover(date) {
  const prevDate = new Date(date);
  const formattedPrevDate = prevDate.toISOString().split('T')[0];

  if (bookedTimeSlots[formattedPrevDate]) {
    return bookedTimeSlots[formattedPrevDate].some(slot => {
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      return slotEndHour > earliestTime; // Spillover to the next day if the checkout is before 6 AM
    });
  }
  return false;
}

function isTimeAvailable(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      
      const slotStart = slotStartHour * 60 + slotStartMin;  
      const slotEnd = slotEndHour * 60 + slotEndMin;        
      const currentTime = time * 60;          
      
      // If the current time is within a blocked slot
      if (currentTime >= slotStart && currentTime <= slotEnd) {
        return false;  // Time is blocked
      }
      if(currentTime < slotEnd){
        return false;
      }

      return true; // Time is available
    });
  }
  return true; // If no booking exists for this date, time is available
}
function isTimeAvailableCheckIn(date, time) {
  const currentTime = time * 60;  

  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].every(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);

      const slotStart = slotStartHour * 60 + slotStartMin;  
      const slotEnd = slotEndHour * 60 + slotEndMin;        

      // Ensure times are correctly compared when the slot starts at or after midnight
      if (slotStart === 0 && currentTime < slotEnd) {
        return false;  
      }

      // Block time if it overlaps with a booked time slot
      if ((currentTime >= slotStart && currentTime <= slotEnd) || currentTime < slotEnd) {
        return false;  // Time is blocked
      }

      return true;  // Time is available
    });
  }
  
  return true; // If no booking exists for this date, time is available
}

//Convert time to minutes
function timeToMinutes(time) {
  const [hour, min] = time.split(':').map(Number);
  return hour * 60 + min;
}

function isTimeAvailableForCheckIn(date, time) {
  const minimumStayMinutes = minimumStay * 60;  // Minimum 12-hour stay in minutes

  // Only allow time slots between 6:00 AM and 11:30 PM for check-in
  if (time < earliestTime || time > latestTime) {
    return false;  // Time is out of the allowed range for check-in
  }
  if (!isTimeAvailableCheckIn(date, time)) {
    return false;
  }

  // Find the first booking slot after the selected check-in time
  if (bookedTimeSlots[date]) {
    const futureSlots = bookedTimeSlots[date].filter(slot => timeToMinutes(slot.start) > time * 60);
    
    if (futureSlots.length > 0) {
      // Get the start time of the first booking slot after the check-in time
      const firstFutureSlotStart = timeToMinutes(futureSlots[0].start);

      // Check if the gap between the selected check-in time and the next booking is less than 12 hours
      if (firstFutureSlotStart - (time * 60) < minimumStayMinutes) {
        return false; // Gap is too small, doesnt allow a minimum stay of 12 hours
      }
    }
  }

  return true;
}

function isTimeAvailableForCheckOut(date, time) {
  // Allow spillover times from previous bookings
  return isTimeAvailable(date, time) || (hasPreviousDaySpillover(date) || hasNextDaySpillover(date));
}

function isDateFullyBookedForCheckIn(dateStr) {

  for (let time = earliestTime; time <= latestTime; time += 0.5) {
    if (isTimeAvailableForCheckIn(dateStr, time)) {
      return false; // Theres at least one available slot, so the date is not fully booked
    }
  }
  return true; // No available times, date is fully booked
}

function isDateFullyBookedForCheckOut(dateStr) {
  const cutoffTimeMinutes = 4 * 60;
  // If the date has bookings, check if the date is fully booked or not
  if (bookedTimeSlots[dateStr]) {
    const slots = bookedTimeSlots[dateStr];

    // Sort the slots by their start time to find the earliest booking
    const sortedSlots = slots.sort((a, b) => timeToMinutes(a.start) - timeToMinutes(b.start));

    const firstSlotStart = timeToMinutes(sortedSlots[0].start);
    // check-out can be allowed until then
    if (firstSlotStart < cutoffTimeMinutes) {
      return true;  // Date is not fully booked, check-out is allowed before the first booking
    }

    // If the first booking starts at or before the cutoff time, block the entire date
    for (let time = earliestTime24hour; time <= latestTime; time += 0.5) {

        //console.log(isTimeAvailableForCheckOut(dateStr, time), dateStr, time);
      if (isTimeAvailableForCheckOut(dateStr, time)) {
        return false;  // Theres at least one available slot for check-out
      }
    }
  }
  
  return true;  // No available times for check-out, or the day is fully booked
}

function findFirstFullyBookedDate(selectedCheckInDate) {
  const sortedDates = Object.keys(bookedTimeSlots).sort(); 
  for (let date of sortedDates) {
    if (date > selectedCheckInDate && isDateFullyBookedForCheckOut(date)) {
      return date; // Return the first fully booked date after the check-in date
    }
  }
  return null; // No fully booked date found
}

function updateDisabledDates(selectedCheckInDate) {
    disabledDates.checkIn = [];
    disabledDates.checkOut = [];

  for (const date in bookedTimeSlots) {
    if (isDateFullyBookedForCheckIn(date)) {
        disabledDates.checkIn.push(date); 
    }

    if (isDateFullyBookedForCheckOut(date)) {
        disabledDates.checkOut.push(date); 
    }
  }

  // Find the maximum check-out date
  const maxCheckOutDate = findFirstFullyBookedDate(selectedCheckInDate);

  // Update flatpickr options for both #date-in and #date-out
  fp.set('disable', disabledDates.checkIn);
  fp1.set('maxDate', null);

  const maxDate = new Date(selectedCheckInDate);
    maxDate.setDate(maxDate.getDate() + 5); 
    fp1.set('maxDate', maxDate);

  // Set maxDate for #date-out based on the found date
  if (maxCheckOutDate) {
    fp1.set('maxDate', maxCheckOutDate); // Limit checkout to the first fully booked date
  }

  fp1.set('disable', disabledDates.checkOut);
}


function formatDate(date) {
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const day = date.getDate().toString().padStart(2, '0');
  return `${year}-${month}-${day}`;
}

// Fetch booked time slots from the server
fetch(`fetch-booking.php`)
    .then(response => response.json())
    .then(bookings => {
      bookings.forEach(booking => {
        const dateIn = booking.dateIn;
        const dateOut = booking.dateOut;
        const checkin = booking.checkin;
        const checkout = booking.checkout;
        
        // Calculate the cleanup end time by adding 2 hours to the checkout time
        const endTime = new Date(`${dateOut} ${checkout}`);
        const cleanupEndTime = new Date(endTime.getTime() + (cleanupTime * 60 * 60 * 1000) - (1 * 60 * 1000)); // Add 2 hours and subtract 1 minute
    
        // If dateIn and dateOut are the same
        if (dateIn === dateOut) {
            if (!bookedTimeSlots[dateIn]) {
                bookedTimeSlots[dateIn] = [];
            }
    
            bookedTimeSlots[dateIn].push({
                date: dateIn,
                start: checkin,
                end: formatTime24(cleanupEndTime) 
            });
        } 
        // If dateIn and dateOut are different
        else {
            const dateInObj = new Date(dateIn);
            const dateOutObj = new Date(dateOut);
            const intermediateDate = new Date(dateInObj);
    
            // Store booking for the check-in date
            if (!bookedTimeSlots[dateIn]) {
                bookedTimeSlots[dateIn] = [];
            }
            bookedTimeSlots[dateIn].push({
                date: dateIn,
                start: checkin,
                end: '23:30' 
            });
    
            // Block intermediate days fully between dateIn and dateOut
            while (intermediateDate.setDate(intermediateDate.getDate() + 1) < dateOutObj.getTime()) {
                const formattedIntermediateDate = intermediateDate.toISOString().split('T')[0]; 
                
                if (!bookedTimeSlots[formattedIntermediateDate]) {
                    bookedTimeSlots[formattedIntermediateDate] = [];
                }
    
                bookedTimeSlots[formattedIntermediateDate].push({
                    date: formattedIntermediateDate,
                    start: '00:00',
                    end: '23:30' // Block the entire intermediate day
                });
            }
    
            // Store booking for the check-out date, including cleanup time
            if (!bookedTimeSlots[dateOut]) {
                bookedTimeSlots[dateOut] = [];
            }
            bookedTimeSlots[dateOut].push({
                date: dateOut,
                start: '00:00',
                end: formatTime24(cleanupEndTime)
            });
        }
    });
        
        // Initialize flatpickr after booking data is fetched
        initializeFlatpickr();
        // Disable Dates
        updateDisabledDates(null);
    })
    .catch(error => console.error('Error fetching bookings:', error));

function initializeFlatpickr() {
  fp = flatpickr("#date-in", {
    enableTime: false,
    dateFormat: "Y-m-d",
    minDate: new Date().fp_incr(1),
    showMonths: 1,
    disableMobile: "true", 
    onChange: function (selectedDates, dateStr, instance) {
      document.querySelector("#date-in").value = dateStr;

      fp1.set('minDate', dateStr); // Set min date for checkout based on check-in date
      fp1.setDate(null); // Reset checkout date

      // Update the disabled dates and max checkout range based on check-in date
      updateDisabledDates(dateStr);
    }
  });

  fp1 = flatpickr("#date-out", {
    enableTime: false,
    dateFormat: "Y-m-d",
    disableMobile: "true",
    minDate: new Date().fp_incr(1),
    onChange: function (selectedDates, dateStr, instance) {
      document.querySelector("#date-out").value = dateStr;

    }
  });

}
  </script>
</body>

</html>
