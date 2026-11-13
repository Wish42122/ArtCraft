<?php
require __DIR__.'/bootstrap.php';
$admin = require_user(true);
?>
<?php require __DIR__.'/dashboard-data.php'; ?>
<html>
<html lang="en">
<head>
<link rel="stylesheet" href="style.css">
<script src="script.js" defer></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard — NSBM ArtCraft</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, sans-serif;
        background: #f4f7f6;
        color: #333;
        min-height: 100vh;
    }

    
    header {
        background: #061e36;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 40px;
        color: #fff;
        height: 58px;
        position: sticky; top: 0; z-index: 30;
    }
    .logo { font-size: 23px; font-weight: 800; display: flex; align-items: center; gap: 8px; letter-spacing: .4px; cursor: pointer; }
    .logo .w { color: #fff; }
    .logo .g { color: #25c364; }

    nav { display: flex; align-items: center; gap: 28px; }
    nav a { color: #dbe4ee; text-decoration: none; font-size: 14px; font-weight: 500; transition: color .2s; }
    nav a:hover { color: #25c364; }

    .nav-right { display: flex; align-items: center; gap: 20px; }
    .search-icon { color: #dbe4ee; font-size: 14px; cursor: pointer; }
    .login-btn { color: #fff; text-decoration: none; font-size: 14px; font-weight: 500; }
    .register-btn {
        background: #25c364; color: #fff; padding: 8px 18px; border-radius: 6px;
        text-decoration: none; font-size: 14px; font-weight: 600; transition: background .2s;
    }
    .register-btn:hover { background: #1eb057; }

    
    .layout { display: flex; min-height: calc(100vh - 58px); }

    
    .sidebar {
        width: 200px;
        background: #0a2540;
        flex-shrink: 0;
        padding: 16px 0;
        position: sticky; top: 58px;
        height: calc(100vh - 58px);
    }
    .sidebar a {
        display: flex; align-items: center; gap: 12px;
        padding: 13px 22px;
        color: #8fa6c4; text-decoration: none;
        font-size: 13.5px; font-weight: 500;
        transition: all .2s;
    }
    .sidebar a i { width: 16px; text-align: center; font-size: 14px; }
    .sidebar a:hover { color: #fff; background: rgba(255,255,255,.05); }
    .sidebar a.active { background: #25c364; color: #fff; font-weight: 600; }

    
    .rail {
        width: 48px; background: #0a2540; flex-shrink: 0;
        display: flex; flex-direction: column; align-items: center;
        padding-top: 14px; gap: 12px;
        position: sticky; top: 58px; height: calc(100vh - 58px);
    }
    .rail-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #54749a; font-size: 14px; cursor: pointer; transition: all .2s;
    }
    .rail-icon:hover { color: #fff; background: rgba(255,255,255,.08); }
    .rail-icon.active { background: #25c364; color: #fff; }

    
    main { flex: 1; padding: 28px 36px 60px; min-width: 0; }

    .page-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
    .page-head h1 { font-size: 24px; font-weight: 800; color: #0f172a; }
    .page-head .subtitle { font-size: 13px; color: #7c8aa0; margin-top: 6px; }

    .admin-chip { display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .admin-chip img {
        width: 42px; height: 42px; border-radius: 50%; object-fit: cover;
        border: 2px solid #25c364;
    }
    .admin-chip .a-name { font-size: 13.5px; font-weight: 700; color: #0f172a; }
    .admin-chip .a-role { font-size: 11.5px; color: #94a3b8; }

    
    .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat {
        background: #fff; border-radius: 12px;
        box-shadow: 0 3px 12px rgba(15,23,42,.06);
        padding: 22px 24px;
    }
    .stat .num { font-size: 30px; font-weight: 800; color: #0f172a; line-height: 1; }
    .stat .num.green { color: #25c364; }
    .stat .num.red { color: #ef4444; }
    .stat .lbl { font-size: 13px; color: #7c8aa0; margin-top: 8px; font-weight: 500; }

    
    .panels { display: grid; grid-template-columns: 1.6fr 1fr; gap: 20px; }
    .panel {
        background: #fff; border-radius: 12px;
        box-shadow: 0 3px 12px rgba(15,23,42,.06);
        padding: 22px 24px;
    }
    .panel h2 { font-size: 15.5px; font-weight: 700; color: #0f172a; margin-bottom: 18px; }

    
    .chart-wrap { width: 100%; }
    .chart-wrap svg { width: 100%; height: auto; display: block; }

    
    .sub-row {
        display: flex; align-items: center; gap: 13px;
        padding: 11px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .sub-row:last-child { border-bottom: none; }
    .sub-row img {
        width: 46px; height: 46px; border-radius: 9px; object-fit: cover; flex-shrink: 0;
    }
    .sub-info { flex: 1; min-width: 0; }
    .sub-title { font-size: 13.5px; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sub-by { font-size: 12px; color: #94a3b8; margin-top: 3px; }
    .pill {
        font-size: 11px; font-weight: 700;
        padding: 5px 12px; border-radius: 20px; flex-shrink: 0;
    }
    .pill.pending { background: #ffedd5; color: #ea580c; }
    .pill.approved { background: #dcfce7; color: #16a34a; }

    
    @media (max-width: 1100px) {
        .stats { grid-template-columns: repeat(2, 1fr); }
        .panels { grid-template-columns: 1fr; }
    }
    @media (max-width: 900px) {
        .sidebar, .rail { display: none; }
        header { padding: 0 20px; }
        nav { display: none; }
        main { padding: 20px 16px 50px; }
    }
    @media (max-width: 560px) {
        .stats { grid-template-columns: 1fr; }
    }
</style>
<link rel="stylesheet" href="backend.css">
</head>
<body>


<header>
    <div class="logo"><span class="w">NSBM</span><span class="g">ArtCraft</span></div>
    <nav>
        <a href="homepage.html">Home</a>
        <a href="artgallery.html">Gallery</a>
        <a href="about.html">About</a>
        <a href="contact.html">Contact</a>
    </nav>
    <div class="nav-right">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <?php session_nav('login-btn','register-btn'); ?>
    </div>
</header>

<div class="layout">
   
    <aside class="sidebar">
        <a href="admin.html" class="active"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
        <a href="manage_artworks.html"><i class="fa-regular fa-image"></i> Manage Artworks</a>
        <a href="manage_users.html"><i class="fa-solid fa-users"></i> Manage Users</a>
        <a href="manage_categories.php"><i class="fa-solid fa-layer-group"></i> Categories</a>
        <a href="manage_exhibitions.php"><i class="fa-solid fa-calendar"></i> Exhibitions</a>
        <a href="reports.php"><i class="fa-solid fa-chart-column"></i> Reports</a>
        <a href="about.html"><i class="fa-solid fa-gear"></i> Profile</a>
        <a href="logout.html"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
    </aside>

    
    <main>
<div class="app-tools app-mobile-links" aria-label="Admin navigation">
<a href="admin.php">Dashboard</a><a href="manage_artworks.php">Artworks</a><a href="manage_users.php">Users</a><a href="manage_categories.php">Categories</a><a href="manage_exhibitions.php">Exhibitions</a><a href="reports.php">Reports</a>
</div>
        <div class="page-head">
            <div>
                <h1>Admin Dashboard</h1>
                <p class="subtitle">Manage the NSBM ArtCraft platform</p>
            </div>
            <div class="admin-chip">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=100&q=80" alt="Admin">
                <div>
                    <div class="a-name"><?=e($admin['fullname'])?></div>
                    <div class="a-role">Administrator</div>
                </div>
            </div>
        </div>

        
        <div class="stats">
            <div class="stat"><div class="num"><?=e($users)?></div><div class="lbl">Total Users</div></div>
            <div class="stat"><div class="num"><?=e($counts['total'])?></div><div class="lbl">Total Artworks</div></div>
            <div class="stat"><div class="num green"><?=e($counts['approved'])?></div><div class="lbl">Approved</div></div>
            <div class="stat"><div class="num red"><?=e($counts['pending'])?></div><div class="lbl">Pending</div></div>
        </div>

        
        <div class="panels">
            <div class="panel">
                <h2>Monthly Artwork Submissions</h2>
                <div class="chart-wrap">
                    <?php require __DIR__.'/chart-view.php'; ?>

                </div>
            </div>

            <div class="panel">
                <h2>Recent Submissions</h2>
<?php require __DIR__.'/recent-view.php'; ?>

            </div>
        </div>
    </main>

   
    <aside class="rail">
        <span class="rail-icon active"><i class="fa-solid fa-table-columns"></i></span>
        <span class="rail-icon"><i class="fa-regular fa-image"></i></span>
        <span class="rail-icon"><i class="fa-solid fa-users"></i></span>
        <span class="rail-icon"><i class="fa-regular fa-bell"></i></span>
        <span class="rail-icon"><i class="fa-solid fa-gear"></i></span>
        <span class="rail-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
    </aside>
</div>

</body>
</html>
