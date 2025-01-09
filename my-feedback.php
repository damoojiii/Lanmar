<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

session_start();
include "role_access.php";
checkAccess('user');

$userId = $_SESSION['user_id'];

// Fetch user's feedback
$query = "SELECT feedback_id, rating, comment, created_at, updated_at FROM feedback_tbl WHERE user_id = :user_id LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->execute([':user_id' => $userId]);
$feedback = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanmar Resort</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/fontawesome.min.css">
    <?php include "sidebar-design.php"; ?>
    <style>

        .container{
            display: flex;
            width: 100%;
            padding: 0;
            gap: 20px;
        }
        .legend{
            display: flex;
            justify-content: center;
        }
        .feedback-page {
            padding: 20px;
        }

        .feedback-form, .my-feedback {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .feedback-form h3, .my-feedback h3 {
            margin-bottom: 15px;
            font-size: 20px;
        }

        .rating-section {
            margin-bottom: 20px;
        }

        .rating-section label {
            font-weight: bold;
        }

        .stars {
            display: flex;
            gap: 5px;
            margin-top: 10px;
            cursor: pointer;
        }

        .stars img {
            width: 30px;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .stars img:hover {
            transform: scale(1.2);
        }

        .rating-meaning {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }


        .comment-section {
            margin-bottom: 20px;
        }

        .comment-section textarea {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 14px;
        }

        .feedback-section {
            padding: 20px;
            margin: 0 auto;
        }

        .feedback-card {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;  
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            flex-wrap: wrap; 
        }

        .feedback-content {
            flex: 1;
            word-wrap: break-word; 
            word-break: break-word; 
        }

        .feedback-card p {
            margin: 0 0 10px;
            font-size: 16px;
            color: #333;
            line-height: 1.6; /* Optional: Adjust line height for better readability */
        }

        .feedback-card button {
            margin-left: 15px;
        }

        .rating-star {
            width: 24px;
            height: 24px;
            vertical-align: middle;
        }

        /* No Feedback Message */
        .no-feedback {
            text-align: center;
            font-size: 16px;
            color: #555;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .btn-primary{
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
        }
        

        @media (max-width: 768px) {
            .navbar {
                margin-left: 0;
                width: 100%; 
            }
            .navbar.shifted {
                margin-left: 250px; 
                width: calc(100% - 250px); 
            }
            .feedback-form, .my-feedback {
                padding: 15px;
            }

            .stars img {
                width: 24px;
            }

            .stars {
                gap: 3px;
            }


            .feedback-section {
                padding: 15px;
            }

            .feedback-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .feedback-content {
                margin-bottom: 10px;
            }

            .rating-star {
                width: 20px;
                height: 20px;
            }

            .feedback-card button {
                align-self: stretch;
                margin-top: 10px;
                text-align: center;
            }

            .feedback-card p {
                font-size: 14px;
            }

            .no-feedback {
                font-size: 14px;
                padding: 15px;
            }
        }
        @media (max-width: 480px) {
            .rating-star {
                width: 18px;
                height: 18px;
            }

            .feedback-card p {
                font-size: 12px;
            }

            .feedback-card button {
                font-size: 14px;
                padding: 8px;
            }

            .no-feedback {
                font-size: 12px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div id="sidebar" class="d-flex flex-column p-3 text-white position-fixed vh-100">
    <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">Lanmar Resort</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index1.php" class="nav-link text-white">Book Here</a>
        </li>
        <li><a href="my-reservation.php" class="nav-link text-white">My Reservations</a></li>
        <li><a href="my-notification.php" class="nav-link text-white">Notification</a></li>
        <li><a href="chats.php" class="nav-link text-white">Chat with Lanmar</a></li>
        <li><a href="my-feedback.php" class="nav-link text-white active">Feedback</a></li>
        <li><a href="settings_user.php" class="nav-link text-white">Settings</a></li>
    </ul>
    <hr>
    <a href="logout.php" class="nav-link text-white">Log out</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <button id="hamburger" class="navbar-toggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
            </ul>
        </div>
    </div>
</nav>
<!-- Main content -->
<div id="main-content" class="mt-4 pt-3">
    <div class="feedback-page">
        <!-- Feedback Form -->
        <div class="feedback-form">
            <h3>Give Us Your Feedback</h3>
            <form id="feedbackForm">
                <div class="rating-section">
                    <label>Rate Us:</label>
                    <div class="stars">
                        <img src="font/star-regular.svg" data-value="1" alt="1 Star">
                        <img src="font/star-regular.svg" data-value="2" alt="2 Stars">
                        <img src="font/star-regular.svg" data-value="3" alt="3 Stars">
                        <img src="font/star-regular.svg" data-value="4" alt="4 Stars">
                        <img src="font/star-regular.svg" data-value="5" alt="5 Stars">
                    </div>
                    <p class="rating-meaning">Pick a star</p>
                    <input type="hidden" id="ratingValue" name="rating" value="">
                </div>


                <div class="comment-section">
                    <label for="feedbackComment">Your Comments:</label>
                    <textarea id="feedbackComment" rows="4" placeholder="Write your feedback here..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Feedback</button>
            </form>
        </div>

        <div class="feedback-section">
            <h2 class="section-title">My Feedback</h2>

            <?php if ($feedback): ?>
                <div class="feedback-card">
                    <div class="feedback-content">
                        <p><strong>Your Rating:</strong> 
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <img 
                                    src="<?= $i <= $feedback['rating'] ? 'font/star-solid.svg' : 'font/star-regular.svg'; ?>" 
                                    alt="<?= $i <= $feedback['rating'] ? 'Filled Star' : 'Empty Star'; ?>" 
                                    class="rating-star">
                            <?php endfor; ?>
                            (<?= htmlspecialchars(
                                $feedback['rating'] == 5 ? 'Amazing' : 
                                ($feedback['rating'] == 4 ? 'Very Good' : 
                                ($feedback['rating'] == 3 ? 'Okay' : 
                                ($feedback['rating'] == 2 ? 'Bad' : 'Not Good')))); ?>)
                        </p>
                        <p><strong>Your Comment:</strong> <?= htmlspecialchars($feedback['comment']); ?></p>
                        <?php if ($feedback['updated_at']): ?>
                            <p><strong>Updated At:</strong> <?= date("F j, Y, g:i A", strtotime($feedback['updated_at'])); ?></p>
                        <?php else: ?>
                            <p><strong>Created At:</strong> <?= date("F j, Y, g:i A", strtotime($feedback['created_at'])); ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="edit-feedback.php?id=<?= htmlspecialchars($feedback['feedback_id']); ?>" class="btn btn-secondary btn-sm">Edit</a>
                </div>
            <?php else: ?>
                <div class="no-feedback">
                    <p>You have not submitted any feedback yet. Please provide your feedback to help us improve!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>



<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>
</html>

<script>
    document.getElementById('hamburger').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
    
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('shifted');
    
    const mainContent = document.getElementById('main-content');
    mainContent.classList.toggle('shifted');
});

const stars = document.querySelectorAll('.stars img');
const ratingMeaning = document.querySelector('.rating-meaning');
const ratingInput = document.querySelector('#ratingValue');

const meanings = ['Not Good', 'Bad', 'Okay', 'Very Good', 'Amazing'];

stars.forEach((star) => {
    star.addEventListener('click', () => {
        const rating = star.getAttribute('data-value');
        ratingInput.value = rating;

        // Update stars
        stars.forEach((s, index) => {
            if (index < rating) {
                s.src = 'font/star-solid.svg'; // Shaded star
            } else {
                s.src = 'font/star-regular.svg'; // Unshaded star
            }
        });

        // Update meaning
        ratingMeaning.textContent = meanings[rating - 1];
    });
});

document.querySelector('#feedbackForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    const formData = new FormData();
    formData.append('rating', document.querySelector('#ratingValue').value);
    formData.append('comment', document.querySelector('#feedbackComment').value);

    fetch('submit_feedback.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message); // Display success message
                location.reload();  // Reload the page to update feedback display
            } else {
                alert(data.message); // Display error message
            }
        })
        .catch(error => console.error('Error:', error));
});

</script>