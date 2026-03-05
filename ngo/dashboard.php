<?php
session_start();
include '../includes/db.php';

// Auth Check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ngo'){
    header("Location: /food-wastage-system/login.php");
    exit();
}

$ngo_id = $_SESSION['user_id'];
$message = "";

// Fetch User Name
$user_query = $conn->query("SELECT name FROM users WHERE id = '$ngo_id'");
$user_data = $user_query->fetch_assoc();
$user_name = $user_data['name']; 

// LOGIC: Handle New Food Request
if(isset($_POST['request_food'])){
    $food_id = $conn->real_escape_string($_POST['food_id']);
    $check = $conn->query("SELECT * FROM food_requests WHERE food_id='$food_id' AND ngo_id='$ngo_id'");
    if($check->num_rows == 0){
        $sql = "INSERT INTO food_requests (food_id, ngo_id, status) VALUES ('$food_id','$ngo_id', 'Pending')";
        if($conn->query($sql)){
            $message = "Request sent successfully!";
        }
    }
}

$requested_ids = [];
$req_res = $conn->query("SELECT food_id FROM food_requests WHERE ngo_id='$ngo_id'");
while($r = $req_res->fetch_assoc()) { $requested_ids[] = $r['food_id']; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NGO Dashboard | Food Bridge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f7f2; }
        .sidebar-gradient { background: linear-gradient(180deg, #2d5a27 0%, #1a3a17 100%); }
        .sidebar-link.active { background: rgba(255,255,255,0.15); border-left: 4px solid #f39c12; }
        .hidden-section { display: none; }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="w-72 sidebar-gradient text-white flex flex-col fixed h-full shadow-2xl z-50">
        <div class="p-8 text-center border-b border-white/10">
            <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg rotate-3">
                <i class="fas fa-hand-holding-heart text-2xl"></i>
            </div>
            <h1 class="text-xl font-black tracking-tighter uppercase italic">Food Bridge</h1>
            <p class="text-[10px] text-green-400 font-bold tracking-widest uppercase mt-1">NGO Panel</p>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2">
            <button onclick="showSection('find')" id="btn-find" class="sidebar-link active w-full flex items-center space-x-3 p-4 rounded-xl transition hover:bg-white/5">
                <i class="fas fa-search"></i> <span class="font-semibold">Find Food</span>
            </button>
            <button onclick="showSection('requests')" id="btn-requests" class="sidebar-link w-full flex items-center space-x-3 p-4 rounded-xl transition hover:bg-white/5">
                <i class="fas fa-clipboard-check"></i> <span class="font-semibold">My Requests</span>
            </button>
            <a href="../feedback.php" class="w-full flex items-center space-x-3 p-4 rounded-xl transition hover:bg-white/5">
                <i class="fas fa-comment-dots"></i> <span class="font-semibold">Feedback</span>
            </a>
        </nav>

        <div class="p-6 border-t border-white/10">
            <a href="../logout.php" class="flex items-center space-x-3 p-4 text-red-400 hover:bg-red-500/10 rounded-xl transition font-bold">
                <i class="fas fa-power-off"></i> <span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="ml-72 flex-1 p-10">
        
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 id="page-title-main" class="text-3xl font-black text-gray-800 italic uppercase">Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
                <p class="text-gray-500 font-medium tracking-tight">Making a difference, one meal at a time.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase leading-none">NGO Partner</p>
                    <p class="text-sm font-bold text-green-700"><?php echo htmlspecialchars($user_name); ?></p>
                </div>
                <div class="w-12 h-12 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold shadow-lg">
                    <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                </div>
            </div>
        </header>

        <div id="search-container" class="mb-10 relative max-w-2xl">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="foodSearch" placeholder="Search food, category or location..." 
                   class="w-full pl-14 pr-6 py-4 rounded-2xl border-none shadow-sm focus:ring-2 focus:ring-green-500 outline-none"
                   onkeyup="searchFood()">
        </div>

        <?php if($message): ?>
            <div id="alert" class="bg-green-600 text-white p-4 rounded-2xl mb-8 flex justify-between items-center shadow-lg">
                <span class="font-bold"><i class="fas fa-check-circle mr-2"></i> <?php echo $message; ?></span>
                <button onclick="document.getElementById('alert').remove()"><i class="fas fa-times"></i></button>
            </div>
        <?php endif; ?>

        <section id="sec-find">
            <div id="foodGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $food_query = "SELECT food_posts.*, users.name as v_name FROM food_posts JOIN users ON food_posts.vendor_id = users.id WHERE food_posts.status='Fresh' ORDER BY id DESC";
                $food_res = $conn->query($food_query);
                while($row = $food_res->fetch_assoc()):
                    $already_req = in_array($row['id'], $requested_ids);
                ?>
                <div class="food-card-item bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <span class="bg-green-100 text-green-700 text-[10px] font-black px-3 py-1 rounded-full uppercase cat-tag"><?php echo $row['category']; ?></span>
                            <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest"><?php echo date('h:i A', strtotime($row['upload_time'])); ?></span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4 food-title"><?php echo $row['food_name']; ?></h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><i class="fas fa-weight-hanging text-orange-500 w-5"></i> <strong>Qty:</strong> <?php echo $row['quantity']; ?></p>
                            <p><i class="fas fa-store text-orange-500 w-5"></i> <strong>Vendor:</strong> <?php echo $row['v_name']; ?></p>
                            <p><i class="fas fa-map-marker-alt text-orange-500 w-5"></i> <span class="loc-tag"><?php echo $row['pickup_location']; ?></span></p>
                        </div>
                        <form method="POST" class="mt-6">
                            <input type="hidden" name="food_id" value="<?php echo $row['id']; ?>">
                            <?php if($already_req): ?>
                                <button type="button" disabled class="w-full bg-gray-100 text-gray-400 py-3 rounded-xl font-bold cursor-not-allowed">Requested</button>
                            <?php else: ?>
                                <button type="submit" name="request_food" class="w-full bg-[#2d5a27] hover:bg-green-800 text-white py-3 rounded-xl font-bold transition shadow-lg">Request Pickup</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section id="sec-requests" class="hidden-section">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="p-6">Food Item</th>
                            <th class="p-6">Vendor</th>
                            <th class="p-6">Request Date</th>
                            <th class="p-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $my_reqs = $conn->query("SELECT food_requests.*, food_posts.food_name, users.name as v_name FROM food_requests JOIN food_posts ON food_requests.food_id = food_posts.id JOIN users ON food_posts.vendor_id = users.id WHERE food_requests.ngo_id = '$ngo_id' ORDER BY food_requests.id DESC");
                        while($r_row = $my_reqs->fetch_assoc()):
                            
                            // LOGIC: COLOR CODING BASED ON STATUS
                            $status = $r_row['status'];
                            $status_class = "bg-gray-100 text-gray-600"; // Default
                            
                            if($status == 'Pending') {
                                $status_class = "bg-orange-100 text-orange-600";
                            } elseif($status == 'Approved' || $status == 'Accepted') {
                                $status_class = "bg-green-100 text-green-600";
                            } elseif($status == 'Rejected') {
                                $status_class = "bg-red-100 text-red-600"; // RED COLOR FOR REJECTED
                            }
                        ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-6 font-bold text-gray-800"><?php echo $r_row['food_name']; ?></td>
                            <td class="p-6 text-gray-600 font-medium"><?php echo $r_row['v_name']; ?></td>
                            <td class="p-6 text-gray-400 text-xs italic"><?php echo $r_row['request_time']; ?></td>
                            <td class="p-6 text-center">
                                <span class="<?php echo $status_class; ?> px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <script>
        function showSection(id) {
            document.querySelectorAll('section').forEach(sec => sec.classList.add('hidden-section'));
            document.querySelectorAll('.sidebar-link').forEach(btn => btn.classList.remove('active'));
            document.getElementById('sec-' + id).classList.remove('hidden-section');
            document.getElementById('btn-' + id).classList.add('active');
            
            let userName = "<?php echo $user_name; ?>";
            document.getElementById('page-title-main').innerText = id === 'find' ? 'Welcome, ' + userName + '!' : 'My Requests';
            document.getElementById('search-container').style.display = id === 'find' ? 'block' : 'none';
        }

        function searchFood() {
            let input = document.getElementById('foodSearch').value.toLowerCase();
            let cards = document.getElementsByClassName('food-card-item');
            for (let i = 0; i < cards.length; i++) {
                let title = cards[i].querySelector('.food-title').innerText.toLowerCase();
                let category = cards[i].querySelector('.cat-tag').innerText.toLowerCase();
                let location = cards[i].querySelector('.loc-tag').innerText.toLowerCase();
                cards[i].style.display = (title.includes(input) || category.includes(input) || location.includes(input)) ? "" : "none";
            }
        }
    </script>
</body>
</html>