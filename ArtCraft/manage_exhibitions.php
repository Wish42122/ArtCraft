<?php
require __DIR__.'/bootstrap.php';
$admin = require_user(true);
?>
<?php
$kind = 'exhibitions';
require __DIR__.'/artwork-fields.php';
require __DIR__.'/admin-crud.php';
?>
<html>
<html lang="en">
<head>
<link rel="stylesheet" href="style.css">
<script src="script.js" defer></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Exhibitions — NSBM ArtCraft Admin</title>
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
        padding: 0 36px;
        color: #fff;
        height: 56px;
        position: sticky; top: 0; z-index: 30;
    }
    .logo { font-size: 22px; font-weight: 800; display: flex; align-items: center; gap: 8px; letter-spacing: .4px; cursor: pointer; }
    .logo .w { color: #fff; }
    .logo .g { color: #25c364; }

    nav { display: flex; align-items: center; gap: 26px; }
    nav a { color: #dbe4ee; text-decoration: none; font-size: 13.5px; font-weight: 500; transition: color .2s; }
    nav a:hover { color: #25c364; }

    .nav-right { display: flex; align-items: center; gap: 18px; }
    .search-icon { color: #dbe4ee; font-size: 13px; cursor: pointer; }
    .login-btn { color: #fff; text-decoration: none; font-size: 13.5px; font-weight: 500; }
    .register-btn {
        background: #25c364; color: #fff; padding: 7px 17px; border-radius: 6px;
        text-decoration: none; font-size: 13.5px; font-weight: 600; transition: background .2s;
    }
    .register-btn:hover { background: #1eb057; }

    
    .layout { display: flex; min-height: calc(100vh - 56px); }

    
    .sidebar {
        width: 195px;
        background: #0a2540;
        flex-shrink: 0;
        padding: 14px 0;
        position: sticky; top: 56px;
        height: calc(100vh - 56px);
    }
    .sidebar a {
        display: flex; align-items: center; gap: 12px;
        padding: 13px 20px;
        color: #8fa6c4; text-decoration: none;
        font-size: 13px; font-weight: 500;
        transition: all .2s;
    }
    .sidebar a i { width: 16px; text-align: center; font-size: 13px; }
    .sidebar a:hover { color: #fff; background: rgba(255,255,255,.05); }
    .sidebar a.active { background: #25c364; color: #fff; font-weight: 600; }

   
    .rail {
        width: 46px; background: #0a2540; flex-shrink: 0;
        display: flex; flex-direction: column; align-items: center;
        padding-top: 14px; gap: 12px;
        position: sticky; top: 56px; height: calc(100vh - 56px);
    }
    .rail-icon {
        width: 30px; height: 30px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #54749a; font-size: 13px; cursor: pointer; transition: all .2s;
    }
    .rail-icon:hover { color: #fff; background: rgba(255,255,255,.08); }
    .rail-icon.active { background: #25c364; color: #fff; }

    main { flex: 1; padding: 26px 32px 60px; min-width: 0; }
    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 3px 14px rgba(15,23,42,.07);
        padding: 26px 30px 14px;
        max-width: 1050px;
    }
    .card h1 { font-size: 23px; font-weight: 800; color: #0f172a; }
    .card .subtitle { font-size: 13px; color: #7c8aa0; margin: 6px 0 20px; }

    
    .toolbar { display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap; }
    .toolbar select {
        border: 1px solid #dde4ec; background: #fbfcfe; border-radius: 8px;
        height: 40px; padding: 0 32px 0 13px;
        font-size: 13px; color: #334155; font-family: inherit; outline: none; cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%2394a3b8' stroke-width='1.6' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
    }
    .toolbar .search-wrap {
        flex: 1 1 240px; display: flex; align-items: center;
        background: #fbfcfe; border: 1px solid #dde4ec; border-radius: 8px; padding: 0 13px;
    }
    .toolbar .search-wrap input {
        border: none; outline: none; background: transparent; flex: 1;
        padding: 11px 8px; font-size: 13px; font-family: inherit; color: #334155;
    }
    .toolbar .search-wrap input::placeholder { color: #a5b1c2; }
    .btn-search {
        background: #25c364; color: #fff; border: none; border-radius: 8px;
        height: 40px; padding: 0 24px; font-size: 13.5px; font-weight: 600;
        font-family: inherit; cursor: pointer; transition: background .2s;
    }
    .btn-search:hover { background: #1eb057; }

    
    table { width: 100%; border-collapse: collapse; }
    thead th {
        text-align: left;
        font-size: 12px; font-weight: 700; color: #7c8aa0;
        text-transform: uppercase; letter-spacing: .4px;
        padding: 10px 12px;
        border-bottom: 2px solid #eef2f6;
    }
    tbody td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px; color: #334155;
        vertical-align: middle;
    }
    tbody tr { transition: background .15s; }
    tbody tr:hover { background: #f8fafc; }
    tbody tr.hidden { display: none; }

    td.idx { color: #94a3b8; font-weight: 600; width: 36px; }
    td img.thumb {
        width: 46px; height: 46px; border-radius: 9px; object-fit: cover;
        box-shadow: 0 2px 6px rgba(15,23,42,.15); display: block;
    }
    .t-title { font-weight: 700; color: #0f172a; }
    .t-artist { color: #55677d; }
    .t-cat { color: #55677d; }

    .pill {
        display: inline-block;
        font-size: 11.5px; font-weight: 700;
        padding: 5px 14px; border-radius: 20px;
    }
    .pill.pending  { background: #ffedd5; color: #ea580c; }
    .pill.approved { background: #dcfce7; color: #16a34a; }
    .pill.rejected { background: #fee2e2; color: #dc2626; }

    .btn-view, .btn-reject {
        border: none; border-radius: 7px; cursor: pointer;
        font-family: inherit; font-size: 12.5px; font-weight: 600;
        padding: 7px 16px; transition: filter .2s, transform .1s;
    }
    .btn-view   { background: #25c364; color: #fff; margin-right: 8px; }
    .btn-reject { background: #ef4444; color: #fff; }
    .btn-view:hover, .btn-reject:hover { filter: brightness(.92); }
    .btn-view:active, .btn-reject:active { transform: scale(.96); }
    .btn-approve { background: #25c364; color: #fff; border: none; border-radius: 7px; cursor: pointer; font-family: inherit; font-size: 12.5px; font-weight: 600; padding: 7px 14px; margin-right: 8px; }
    .btn-approve:hover { filter: brightness(.92); }

    .empty-msg { padding: 36px; text-align: center; color: #94a3b8; font-size: 14px; display: none; }

    
    @media (max-width: 1000px) {
        .sidebar, .rail { display: none; }
        header { padding: 0 18px; }
        nav { display: none; }
        main { padding: 18px 14px 50px; }
        .card { padding: 20px 16px 10px; }
        thead th:nth-child(4), tbody td:nth-child(4) { display: none; }
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
        <a href="admin.html"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
        <a href="manage_artworks.html"><i class="fa-regular fa-image"></i> Manage Artworks</a>
        <a href="manage_users.html"><i class="fa-solid fa-users"></i> Manage Users</a>
        <a href="manage_categories.php"><i class="fa-solid fa-layer-group"></i> Categories</a>
        <a href="manage_exhibitions.php" class="active"><i class="fa-solid fa-calendar"></i> Exhibitions</a>
        <a href="reports.php"><i class="fa-solid fa-chart-column"></i> Reports</a>
        <a href="about.html"><i class="fa-solid fa-gear"></i> Profile</a>
        <a href="logout.html"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
    </aside>

   
    <main>
<div class="app-tools app-mobile-links" aria-label="Admin navigation">
<a href="admin.php">Dashboard</a><a href="manage_artworks.php">Artworks</a><a href="manage_users.php">Users</a><a href="manage_categories.php">Categories</a><a href="manage_exhibitions.php">Exhibitions</a><a href="reports.php">Reports</a>
</div><?php require __DIR__.'/admin-crud-view.php'; ?>

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
