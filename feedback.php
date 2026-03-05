<?php
session_start();
include 'includes/db.php';

// Login check
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT name, role FROM users WHERE id='$user_id'")->fetch_assoc();

/* ===== HANDLE FORM SUBMISSION ===== */
if(isset($_POST['submit_feedback']) && isset($_POST['rating']) && !empty($_POST['comment'])){
    $rating = (int)$_POST['rating'];
    $comment = $conn->real_escape_string($_POST['comment']);

    $conn->query("INSERT INTO feedback (user_id, rating, comment) VALUES ('$user_id','$rating','$comment')");
    header("Location: feedback.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback | Food Bridge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; padding-top: 80px; }
        
        /* Star Rating Logic */
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; }
        .star-rating input { display: none; }
        .star-rating label { font-size: 35px; color: #cbd5e1; cursor: pointer; transition: 0.3s; }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #f39c12; }

        .btn-submit {
            background: linear-gradient(90deg, #2d5a27 0%, #1a3a17 100%);
            color: white; padding: 12px 25px; border-radius: 12px;
            font-weight: 700; transition: 0.3s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 15px rgba(45, 90, 39, 0.2); }
        
        .badge-role { padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .role-vendor { background: #dcfce7; color: #166534; }
        .role-ngo { background: #fef3c7; color: #92400e; }
        .role-admin { background: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-black text-gray-800 italic uppercase">Community Voice</h1>
            <p class="text-green-700 font-bold tracking-widest">Share Your Experience with Food Bridge</p>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-8 flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-bold">Thank you! Your feedback has been submitted successfully.</span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-16">
            <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-pen-nib text-orange-500"></i> Write a Review
                </h3>
                
                <form method="POST">
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-500 mb-2">How was your experience?</label>
                        <div class="star-rating">
                            <input type="radio" name="rating" value="5" id="s5" required><label for="s5">★</label>
                            <input type="radio" name="rating" value="4" id="s4"><label for="s4">★</label>
                            <input type="radio" name="rating" value="3" id="s3"><label for="s3">★</label>
                            <input type="radio" name="rating" value="2" id="s2"><label for="s2">★</label>
                            <input type="radio" name="rating" value="1" id="s1"><label for="s1">★</label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <textarea name="comment" class="w-full h-32 p-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-green-500 outline-none transition" placeholder="Tell us more about how we helped or where we can improve..." required></textarea>
                    </div>

                    <button type="submit" name="submit_feedback" class="btn-submit">
                        POST REVIEW <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </form>
            </div>

            <div class="bg-green-800 text-white p-8 rounded-3xl shadow-xl flex flex-col justify-center items-center text-center">
                <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center text-3xl mb-4 border-2 border-white/20">
                    <i class="fas fa-user"></i>
                </div>
                <h4 class="text-2xl font-black"><?php echo $user['name']; ?></h4>
                <div class="mt-2">
                    <span class="badge-role bg-orange-500 text-white">
                        <?php echo $user['role']; ?>
                    </span>
                </div>
                <p class="mt-6 text-green-100 text-sm italic opacity-80">
                    "Your feedback helps us bridge the gap between surplus food and those who need it most."
                </p>
            </div>
        </div>

        <h3 class="text-2xl font-black text-gray-800 mb-8 flex items-center gap-3 italic">
            <i class="fas fa-comments text-green-600"></i> RECENT REVIEWS
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $result = $conn->query("
                SELECT feedback.*, users.name, users.role 
                FROM feedback 
                JOIN users ON feedback.user_id = users.id 
                ORDER BY feedback.created_at DESC
            ");

            if($result->num_rows == 0): ?>
                <div class="col-span-full text-center py-10 text-gray-400 font-bold">No feedback received yet. Be the first!</div>
            <?php else: 
                while($row = $result->fetch_assoc()): ?>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h5 class="font-bold text-gray-800"><?php echo $row['name']; ?></h5>
                            <span class="badge-role role-<?php echo $row['role']; ?>">
                                <?php echo $row['role']; ?>
                            </span>
                        </div>
                        <div class="text-orange-400 text-xs">
                            <?php for($i=1; $i<=5; $i++) echo ($i <= $row['rating']) ? "★" : "☆"; ?>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6 italic">
                        "<?php echo $row['comment']; ?>"
                    </p>
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest border-t pt-4">
                        <i class="far fa-clock mr-1"></i> <?php echo date("d M, Y", strtotime($row['created_at'])); ?>
                    </div>
                </div>
            <?php endwhile; endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>
</html>