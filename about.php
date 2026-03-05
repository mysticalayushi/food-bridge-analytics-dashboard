<?php
session_start();
include 'includes/navbar.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>About Us</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:url('assets/images/bg.png') no-repeat center center/cover;
    position:relative;
    min-height:100vh;
}

/* Dark Overlay */
body::before{
    content:'';
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    z-index:-1;
}

/* Main Container */
.container{
    max-width:1100px;
    margin:60px auto;
    padding:40px;
    background:rgba(255,255,255,0.95);
    border-radius:20px;
    box-shadow:0 20px 50px rgba(0,0,0,0.3);
}

/* Hero Section */
.hero{
    text-align:center;
    margin-bottom:50px;
}

.hero h1{
    font-size:36px;
    color:#2563EB;
    margin-bottom:10px;
}

.hero p{
    color:#555;
    font-size:16px;
}

/* Cards Section */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
    margin-top:30px;
}

.card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h2{
    margin-bottom:10px;
    color:#16A34A;
}

.contact p{
    margin:5px 0;
}

.footer-text{
    text-align:center;
    margin-top:40px;
    font-weight:600;
    color:#16A34A;
}
</style>

<style>
    
    body {
        padding-top: 80px; 
    }
    .sidebar {
        top: 80px; 
        height: calc(100vh - 80px);
    }
</style>
</head>

<body>

<div class="container">

    <div class="hero">
        <h1>Food Wastage Reduction System</h1>
        <p>Together we can reduce food waste and feed those in need.</p>
    </div>

    <div class="cards">

        <div class="card">
            <h2>🌍 About the Project</h2>
            <p>
                Our platform connects restaurants, vendors, and households with NGOs to 
                redistribute surplus food efficiently and safely.
            </p>
        </div>

        <div class="card">
            <h2>🎯 Our Mission</h2>
            <p>
                To reduce food wastage, support underprivileged communities, and build 
                a sustainable ecosystem of responsible food sharing.
            </p>
        </div>

        <div class="card contact">
            <h2>📞 Contact Us</h2>
            <p><strong>Email:</strong> info@foodwastagereduction.com</p>
            <p><strong>Phone:</strong> +91 9876543210</p>
            <p><strong>Address:</strong> 123 Green Street, Delhi, India</p>
        </div>

    </div>

    <div class="footer-text">
        Join us in reducing food wastage and making a positive impact!
    </div>

</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>