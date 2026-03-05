<?php
session_start();
include 'includes/db.php';

if(isset($_POST['register'])){
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($check->num_rows > 0){
        $error = "This email is already registered!";
    } else {
        $sql = "INSERT INTO users (name, email, mobile, password, role) 
                VALUES ('$name', '$email', '$mobile', '$password', '$role')";
        
        if($conn->query($sql)){
            $success = "Registration successful! Redirecting to login...";
            header("refresh:2;url=login.php");
        } else {
            $error = "Oops! Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join Food Bridge | Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
    background: linear-gradient(rgba(45, 90, 39, 0.8), rgba(45, 90, 39, 0.8)), 
                url('assets/images/bg.png') no-repeat center center/cover;
    
}
        .reg-container { 
            padding: 120px 20px 80px 20px; 
            display: flex; justify-content: center; align-items: center; 
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            display: flex; overflow: hidden; width: 1100px; max-width: 100%;
        }
        .reg-left { padding: 50px; width: 55%; background: white; }
        .reg-right { 
            background: #2d5a27; color: white; padding: 50px; width: 45%; 
            display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;
        }
        .input-group { position: relative; margin-bottom: 18px; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .input-group input { 
            width: 100%; padding: 12px 15px 12px 45px; border: 2px solid #e2e8f0; 
            border-radius: 12px; outline: none; transition: 0.3s; 
        }
        .input-group input:focus { border-color: #f39c12; }
        
        /* Modern Role Selection */
        .role-card {
            display: flex; gap: 15px; margin-top: 10px;
        }
        .role-option {
            flex: 1; position: relative; cursor: pointer;
        }
        .role-option input { position: absolute; opacity: 0; cursor: pointer; }
        .role-box {
            padding: 15px; border: 2px solid #e2e8f0; border-radius: 12px;
            text-align: center; transition: 0.3s; font-weight: 600; color: #64748b;
        }
        .role-option input:checked + .role-box {
            border-color: #f39c12; background: #fffaf0; color: #f39c12;
        }
        .btn-reg {
            background: linear-gradient(90deg, #2d5a27 0%, #1a3a17 100%);
            color: white; padding: 14px; border-radius: 12px; width: 100%;
            font-weight: 700; transition: 0.3s; margin-top: 20px;
        }
        .btn-reg:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(45, 90, 39, 0.3); }
        .toggle-btn { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="reg-container">
        <div class="glass-card">
            <div class="reg-left">
                <div class="mb-8">
                    <h2 class="text-3xl font-black text-gray-800">Create Account</h2>
                    <p class="text-gray-500">Join the mission to end food wastage.</p>
                </div>

                <?php if(isset($error)): ?>
                    <div class="bg-red-50 text-red-600 p-3 rounded-xl mb-4 text-sm font-bold"><i class="fas fa-times-circle mr-2"></i><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if(isset($success)): ?>
                    <div class="bg-green-50 text-green-600 p-3 rounded-xl mb-4 text-sm font-bold"><i class="fas fa-check-circle mr-2"></i><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" placeholder="Full Name" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Email Address" required>
                        </div>
                        <div class="input-group">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="mobile" placeholder="Mobile Number" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Create Password" required>
                        <i class="fas fa-eye toggle-btn" onclick="togglePassword()"></i>
                    </div>

                    <div class="mt-4">
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-wider">Select Your Role</label>
                        <div class="role-card">
                            <label class="role-option">
                                <input type="radio" name="role" value="vendor" required>
                                <div class="role-box"><i class="fas fa-store mb-2 block text-xl"></i> Vendor</div>
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role" value="ngo">
                                <div class="role-box"><i class="fas fa-hand-holding-heart mb-2 block text-xl"></i> NGO</div>
                            </label>
                        </div>
                    </div>
            
                
                    <button type="submit" name="register" class="btn-reg uppercase tracking-widest">
                        Register Now
                    </button>
                </form>

                <div class="mt-6 text-center text-gray-500 text-sm">
                    Already part of the bridge? <a href="login.php" class="text-orange-500 font-extrabold hover:underline">Login here</a>
                </div>
            </div>

            <div class="reg-right hidden md:flex">
                <div class="mb-8">
                    <h3 class="text-3xl font-black italic">Food Bridge</h3>
                    <p class="text-green-200 mt-2">Connecting Surplus to Service</p>
                </div>
                <img src="assets/images/register.png" alt="Register" class="w-4/5 mb-8">
                <p class="text-sm text-green-100 opacity-70">"Giving is not just about making a donation. It's about making a difference."</p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        function togglePassword() {
            const pass = document.getElementById("password");
            const icon = event.target;
            if (pass.type === "password") {
                pass.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pass.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>