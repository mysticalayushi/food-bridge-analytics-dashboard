<?php
session_start();
include 'includes/db.php';

if(isset($_POST['login'])){
    $email = $conn->real_escape_string($_POST['email']); // Security update
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == 'vendor'){
                header("Location: /food-wastage-system/vendor/dashboard.php");
            } elseif($user['role'] == 'ngo'){
                header("Location: /food-wastage-system/ngo/dashboard.php");
            } else {
                header("Location: /food-wastage-system/admin/dashboard.php");
            }
            exit();
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Food Bridge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .login-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 100px 20px 60px 20px; /* Space for Navbar */
            background: url('assets/images/bg.png'); /* Optional pattern */
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            display: flex;
            overflow: hidden;
            width: 1000px;
            max-width: 100%;
        }
        .login-left {
            background: #2d5a27; /* Food Bridge Green */
            color: white;
            padding: 60px;
            width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        .login-right {
            padding: 60px;
            width: 55%;
            background: white;
        }
        .input-box {
            position: relative;
            margin-bottom: 20px;
        }
        .input-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .input-box input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            outline: none;
            transition: 0.3s;
        }
        .input-box input:focus {
            border-color: #f39c12; /* Food Bridge Orange */
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
        }
        .btn-login {
            background: linear-gradient(90deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            font-weight: 700;
            transition: 0.3s;
            box-shadow: 0 10px 15px -3px rgba(243, 156, 18, 0.4);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 20px -3px rgba(243, 156, 18, 0.5);
        }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="login-container">
        <div class="glass-card">
            <div class="login-left hidden md:flex">
                <h2 class="text-4xl font-black mb-4 italic">Food Bridge</h2>
                <p class="text-green-100 text-lg">Every meal you share builds a bridge to a better tomorrow.</p>
                <div class="mt-10">
                    <img src="assets/images/login1.png" alt="Illustration" class="w-full opacity-80">
                </div>
            </div>

            <div class="login-right">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-black text-gray-800">Welcome Back</h2>
                    <p class="text-gray-500 font-medium">Please login to your account</p>
                </div>

                <?php if(isset($error)): ?>
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="input-box">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>

                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="passwordInput" placeholder="Password" required>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>

                    <div class="flex justify-between items-center mb-6 px-1">
                        <label class="flex items-center text-sm text-gray-500 font-semibold cursor-pointer">
                            <input type="checkbox" class="mr-2 accent-orange-500"> Remember me
                        </label>
                        <a href="#" class="text-sm text-orange-500 font-bold hover:underline">Forgot Password?</a>
                    </div>

                    <button type="submit" name="login" class="btn-login">
                        LOGIN TO SYSTEM
                    </button>
                </form>

                <div class="mt-8 text-center text-gray-500 font-medium">
                    Don't have an account? <a href="register.php" class="text-green-700 font-bold hover:underline">Sign up for free</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        // Password Visibility Toggle Logic
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>