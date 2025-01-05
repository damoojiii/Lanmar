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

try {
  $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
      height: 70vh;
      overflow: hidden;
    }

    .mySlides {
      position: absolute;
      width: 100%;
      height: 100%;
      opacity: 0;
      transition: opacity 0.8s ease-in-out;
      display: none;
    }

    .mySlides.active {
      opacity: 1;
      display: block;
    }

    .mySlides img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .prev, .next {
      cursor: pointer;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      padding: 16px;
      color: white;
      font-weight: bold;
      font-size: 18px;
      background-color: rgba(0,0,0,0.3);
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background-color 0.3s ease;
      z-index: 2;
    }

    .next {
      right: 20px;
    }

    .prev {
      left: 20px;
    }

    .prev:hover, .next:hover {
      background-color: rgba(0,0,0,0.6);
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

    .slideshow-container-rooms {
      position: relative;
      margin: auto;
    }
    
    .room-slide {
      display: none;
    }
    
    .fade {
      animation-name: fade;
      animation-duration: 0.5s;
    }
    
    @keyframes fade {
      from {opacity: .4} 
      to {opacity: 1}
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
                <?php echo htmlspecialchars($descriptions['description']); ?>
              </p>
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
          <h2>Our Featured Rooms</h2>
          <p>Experience luxury and comfort in our signature accommodations</p>
        </div>
            
        <div class="slideshow-container-rooms">
          <?php
            // Add error reporting for debugging
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            // Verify connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $query = "SELECT * FROM rooms WHERE is_featured = 1";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die('Query Error: ' . mysqli_error($conn));
            }

            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <div class="room-slide fade">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="room-image">
                      <img src="uploads/<?php echo htmlspecialchars($row['image_path']); ?>" 
                           class="img-fluid" 
                           alt="<?php echo htmlspecialchars($row['room_name']); ?>">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="room-description">
                      <h3><?php echo htmlspecialchars($row['room_name']); ?></h3>
                      <p><?php echo htmlspecialchars($row['description']); ?></p>
                      <ul class="room-features list-unstyled">
                        <li><i class="bi bi-check-circle"></i> Capacity: 
                          <?php echo htmlspecialchars($row['minpax']) . '-' . htmlspecialchars($row['maxpax']); ?> persons
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
          <?php
            }
          ?>
          
          <!-- Navigation arrows -->
          <a class="prev" onclick="moveRoomSlide(-1)">&#10094;</a>
          <a class="next" onclick="moveRoomSlide(1)">&#10095;</a>
        </div>
      </div>
    </section><!-- End Room Showcase Section -->


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
                            <h4><?= htmlspecialchars($feedback['firstname'] . ' ' . $feedback['lastname']); ?></h4>
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
    <style>
      .settings-form button, 
        .save-btn {
            border-radius: 10px !important;
            padding: 13px 30px;
            background-color: #03045e;
            border: none;
            cursor: pointer;
            color: white;
        }
        .feedback-page {
        padding: 20px;
    }

    .feedback-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: start;
    }

    .feedback-card {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        width: calc(33.333% - 20px); / 3 cards per row /
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
        margin-top: auto; / Push the button to the bottom /
    }

    .feedback-card button {
        margin: 0;
    }

    .feedback-line {
        margin: 40px 0;
    }

    / Responsiveness for mobile devices /
    @media (max-width: 768px) {
        .feedback-card {
            width: calc(50% - 20px); / 2 cards per row on tablets /
        }
    }

    @media (max-width: 576px) {
        .feedback-card {
            width: 100%; / Full width for smaller screens */
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
    let roomSlideIndex = 1;
    let roomSlideTimer;
    showRoomSlides(roomSlideIndex);

    function moveRoomSlide(n) {
      clearTimeout(roomSlideTimer); // Clear the existing timer
      showRoomSlides(roomSlideIndex += n);
    }

    function showRoomSlides(n) {
      let i;
      let slides = document.getElementsByClassName("room-slide");
      
      if (slides.length === 0) return;
      
      if (n > slides.length) {roomSlideIndex = 1}    
      if (n < 1) {roomSlideIndex = slides.length}
      
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
      }
      
      slides[roomSlideIndex-1].style.display = "block";  

      // Decreased display time to 3 seconds (3000 milliseconds) for faster transitions
      roomSlideTimer = setTimeout(() => {
        showRoomSlides(roomSlideIndex += 1);
      }, 3000);
    }
  </script>

  <script>
    let slideIndex = 0;
    let slideTimer;

    showSlides();

    function moveSlide(n) {
      clearTimeout(slideTimer);
      slideIndex += n;
      showSlides();
    }

    function showSlides() {
      let slides = document.getElementsByClassName("mySlides");
      if (slides.length === 0) return;
      
      if (slideIndex >= slides.length) slideIndex = 0;
      if (slideIndex < 0) slideIndex = slides.length - 1;
      
      // Remove active class from all slides
      for (let slide of slides) {
        slide.classList.remove('active');
      }
      
      // Add active class to current slide
      slides[slideIndex].classList.add('active');
      
      // Auto advance
      slideTimer = setTimeout(() => {
        slideIndex++;
        showSlides();
      }, 5000);
    }
  </script>

</body>

</html>
