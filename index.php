<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'includes/db.php'; 
include 'includes/navbar.php'; 

// --- FETCH ONLY 5 ITEMS ---
$sql = "SELECT * FROM food_posts ORDER BY id DESC LIMIT 5";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Bridge | Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; padding-top: 80px; }
        .hero-bg {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/bg.png');
            background-size: cover;
            background-position: center;
            height: 500px;
        }
        .bg-orange { background-color: #f39c12; }
        .bg-green { background-color: #2d5a27; }
        .btn-hover:hover { transform: translateY(-3px); transition: 0.3s; }
    </style>
</head>
<body class="bg-gray-50">

    <section class="hero-bg flex items-center justify-center text-center text-white px-4">
        <div>
            <h1 class="text-4xl md:text-6xl font-black mb-4 uppercase tracking-tight">
                Bridging the Gap Between <br> <span class="text-green-400">Surplus</span> & <span class="text-orange">Hunger</span>
            </h1>
            <p class="text-lg md:text-xl mb-8 text-gray-200">Don't let good food go to waste. Join our community today.</p>
            <div class="flex flex-col md:flex-row justify-center gap-4">
                <a href="register.php" class="bg-orange px-8 py-4 rounded-xl font-bold text-lg btn-hover shadow-lg">Donate Food</a>
                <a href="login.php" class="bg-green-600 px-8 py-4 rounded-xl font-bold text-lg btn-hover shadow-lg">Request Food</a>
            </div>
        </div>
    </section>

    <section class="py-20 max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-black text-center text-gray-800 mb-12 italic uppercase tracking-widest">Live Food Feed</h2>
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 mb-8">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs font-bold uppercase tracking-widest">
                    <tr>
                        <th class="p-6">Item</th>
                        <th class="p-6">Category</th>
                        <th class="p-6">Location</th>
                        <th class="p-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php 
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) { 
                            $status_text = htmlspecialchars($row['status']);
                            $status_class = (strtolower($status_text) == 'available') ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600';
                            ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-6">
                                    <div class="font-bold text-gray-800"><?php echo htmlspecialchars($row['food_name']); ?></div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase"><?php echo htmlspecialchars($row['quantity']); ?> portions</div>
                                </td>
                                <td class="p-6 text-sm text-gray-600"><?php echo htmlspecialchars($row['category']); ?></td>
                                <td class="p-6 text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt text-orange mr-1"></i>
                                    <?php echo htmlspecialchars($row['pickup_location']); ?>
                                </td>
                                <td class="p-6">
                                    <span class="<?php echo $status_class; ?> px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr><td colspan="4" class="p-20 text-center text-gray-400 italic font-bold">No food items found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <div class="text-center">
            <a href="login.php" class="inline-flex items-center gap-2 text-green-600 font-bold hover:underline">
                View More Live Food <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <section class="bg-green py-16 text-white">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            <div><h3 class="text-5xl font-black mb-2 italic">315+</h3><p class="text-green-300 font-bold uppercase text-xs">Meals Donated</p></div>
            <div><h3 class="text-5xl font-black mb-2 italic">75+</h3><p class="text-green-300 font-bold uppercase text-xs">Active Donors</p></div>
            <div><h3 class="text-5xl font-black mb-2 italic">18</h3><p class="text-green-300 font-bold uppercase text-xs">Cities Covered</p></div>
        </div>
    </section>

    <section class="py-24 max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-black text-center text-gray-800 mb-16 italic uppercase tracking-widest">How It Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center group">
                <div class="w-16 h-16 bg-orange text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-lg group-hover:rotate-12 transition">1</div>
                <h4 class="text-xl font-bold mb-4">List Food</h4>
                <p class="text-gray-500 text-sm">Upload details of surplus food from your event, restaurant, or home easily.</p>
            </div>
            <div class="text-center group">
                <div class="w-16 h-16 bg-green text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-lg group-hover:-rotate-12 transition">2</div>
                <h4 class="text-xl font-bold mb-4">Get Matched</h4>
                <p class="text-gray-500 text-sm">Our system notifies nearby NGOs or individuals who need it the most.</p>
            </div>
            <div class="text-center group">
                <div class="w-16 h-16 bg-orange text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-lg group-hover:rotate-12 transition">3</div>
                <h4 class="text-xl font-bold mb-4">Handover</h4>
                <p class="text-gray-500 text-sm">The food is picked up safely and delivered to the hungry souls.</p>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-100">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-black text-center text-gray-800 mb-16 italic uppercase tracking-widest">Partner Stories</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col md:flex-row items-center gap-6 bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                    <img src="assets/images/ngo_patner.png" class="w-28 h-28 rounded-full object-cover shadow-md" alt="NGO">
                    <div>
                        <i class="fas fa-quote-left text-green-200 text-3xl mb-2"></i>
                        <p class="text-gray-600 italic text-sm mb-4 leading-relaxed">"Food Bridge has been a game changer! We feed those in need efficiently."</p>
                        <span class="font-bold text-gray-800 text-xs uppercase tracking-widest">- NGO Partner</span>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center gap-6 bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                    <img src="assets/images/vendorfeed.png" class="w-28 h-28 rounded-full object-cover shadow-md" alt="Vendor">
                    <div>
                        <i class="fas fa-quote-left text-orange-200 text-3xl mb-2"></i>
                        <p class="text-gray-600 italic text-sm mb-4 leading-relaxed">"Donating surplus food gives us immense satisfaction every day."</p>
                        <span class="font-bold text-gray-800 text-xs uppercase tracking-widest">- Restaurant Owner</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

</body>
</html>