<?php
include 'connection.php';
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

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    @font-face {
        font-family: 'nautigal';
        src: url(font/TheNautigal-Regular.ttf);
    }
    .sitename {
        font-family: 'nautigal';
        font-size: 40px;
        color: white;
        letter-spacing: 2px;
        margin-left: 20px;
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
        .btn-outline-light {
            min-width: 100px;
            padding: 0.4rem 1rem;
        }
    }

    /* Accommodation Page Styles */
    .accommodation-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('uploads/lanmar-pfp.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        align-items: center;
        color: white;
        margin-top: -80px;
        padding-top: 80px;
    }

    .hero-content h1 {
        font-size: 3.5rem;
        margin-bottom: 1rem;
    }

    .hero-content p {
        font-size: 1.2rem;
        opacity: 0.9;
    }

    .accommodations-section {
        padding: 5rem 0;
        background-color: #f8f9fa;
    }

    .room-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .room-card:hover {
        transform: translateY(-5px);
    }

    .room-image {
        height: 250px;
        overflow: hidden;
    }

    .room-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .room-details {
        padding: 1.5rem;
    }

    .room-details h3 {
        color: #19315D;
        margin-bottom: 0.5rem;
    }

    .room-description {
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .room-features {
        list-style: none;
        padding: 0;
        margin-bottom: 1.5rem;
    }

    .room-features li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .room-features i {
        color: #19315D;
    }

    .room-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-view-details, .btn-book-now {
        flex: 1;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-view-details {
        background-color: transparent;
        border: 1px solid #19315D;
        color: #19315D;
    }

    .btn-book-now {
        background-color: #19315D;
        color: white;
    }

    .btn-view-details:hover {
        background-color: #19315D;
        color: white;
    }

    .btn-book-now:hover {
        background-color: #142744;
    }

    @media (max-width: 768px) {
        .accommodation-hero {
            height: 70vh;
        }

        .hero-content h1 {
            font-size: 2.5rem;
        }

        .hero-content p {
            font-size: 1rem;
        }

        .room-card {
            margin-bottom: 2rem;
        }
    }

    /* Hero Content Styles */
    .hero-content {
        padding: 2rem;
        max-width: 800px;
        margin: 0 auto;
        width: 100%;
        position: relative;
        z-index: 2;
    }

    .hero-content h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        animation: fadeInDown 1s ease-out;
    }

    .hero-content p {
        font-size: 1.2rem;
        line-height: 1.6;
        opacity: 0.9;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        animation: fadeInUp 1s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }

        .hero-content p {
            font-size: 1rem;
        }
    }

    .room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        padding: 1rem 0;
    }

    .room-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .room-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .room-image {
        height: 250px;
        width: 100%;
    }

    .room-details {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .room-actions {
        margin-top: auto;
    }

    @media (max-width: 768px) {
        .room-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .room-image {
            height: 200px;
        }
    }

    .room-container:nth-child(even) .room-card-full {
        flex-direction: row-reverse;
    }

    .room-card-full {
        display: flex;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        min-height: 400px;
        margin-bottom: 4rem;
    }

    .room-image-full {
        flex: 0 0 50%;
        position: relative;
        overflow: hidden;
    }

    .room-image-full img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
    }

    .room-details-full {
        flex: 0 0 50%;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(5px);
        box-shadow: 0 50px 20px rgba(0,0,0,0.1);
    }

    .room-details-full h3 {
        color: #19315D;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .room-description {
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .room-container:nth-child(even) .room-card-full,
        .room-card-full {
            flex-direction: column;
            min-height: auto;
            margin-bottom: 2rem;
        }

        .room-image-full {
            flex: 0 0 100%;
            height: 300px;
            position: relative;
            order: -1;
        }

        .room-image-full img {
            position: relative;
            height: 300px;
            width: 100%;
            object-fit: cover;
        }

        .room-details-full {
            flex: 0 0 100%;
            padding: 1.5rem;
            order: 2;
        }

        .room-details-full h3 {
            font-size: 1.8rem;
            margin-bottom: 0.8rem;
        }

        .room-description {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .room-features {
            margin-bottom: 1.2rem;
        }

        .room-actions {
            flex-direction: column;
            gap: 0.8rem;
        }

        .btn-view-details, 
        .btn-book-now {
            width: 100%;
            padding: 0.8rem;
            text-align: center;
        }

        .section-title h2 {
            font-size: 2rem;
            padding-bottom: 15px;
        }
    }

    @media (max-width: 480px) {
        .room-image-full {
            height: 250px;
        }
        
        .room-image-full img {
            height: 250px;
        }

        .room-details-full {
            padding: 1.2rem;
        }

        .room-details-full h3 {
            font-size: 1.5rem;
        }
    }

    .section-title {
        text-align: center;
        color: #19315D;
        margin-bottom: 3rem;
    }

    .section-title h2 {
        font-size: 2.5rem;
        font-weight: 700;
        position: relative;
        padding-bottom: 20px;
    }

    .section-title h2::after {
        content: '';
        position: absolute;
        display: block;
        width: 50px;
        height: 3px;
        background: #19315D;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
    }
  </style>

</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <a href="index.php" class="sitename d-flex align-items-center me-auto me-lg-0" style="color: white; letter-spacing: 2px;">
        Lanmar Resort
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="accomodation.php" class="active">Accommodations</a></li>
          <li><a href="index.php#gallery">Gallery</a></li>
          <li><a href="index.php#amenities">Amenities</a></li>
          <li><a href="index.php#contact">Contact</a></li>
          <li><a href="login.php" class="btn btn-outline-light">Sign In</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <!-- Hero Section for Accommodations -->
  <section class="accommodation-hero">
    <div class="container">
      <div class="hero-content text-center" data-aos="fade-up">
        <h1>Our Accommodations</h1>
        <p>Experience luxury and comfort in our thoughtfully designed rooms</p>
      </div>
    </div>
  </section>

  <!-- Accommodations Section -->
  <section class="accommodations-section">
    <div class="container">
        <div class="section-title">
            <h2>Our Rooms</h2>
        </div>
        <?php
        // Fetch all rooms
        $query = "SELECT * FROM rooms ORDER BY room_id ASC";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die('Query Error: ' . mysqli_error($conn));
        }

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="room-container">
                <div class="room-card-full">
                    <div class="room-image-full">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($row['room_name']); ?>">
                    </div>
                    <div class="room-details-full">
                        <h3><?php echo htmlspecialchars($row['room_name']); ?></h3>
                        <p class="room-description"><?php echo htmlspecialchars($row['description']); ?></p>
                        <ul class="room-features">
                            <li><i class="bi bi-people"></i> <?php echo htmlspecialchars($row['minpax']); ?>-<?php echo htmlspecialchars($row['maxpax']); ?> Persons</li>
                            <li><i class="bi bi-wifi"></i> Free WiFi</li>
                            <li><i class="bi bi-snow"></i> Air Conditioning</li>
                        </ul>
                        <div class="room-actions">
                            <button class="btn-view-details">View Details</button>
                        
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
  </section>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
</html>
