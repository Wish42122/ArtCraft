<?php
require __DIR__.'/bootstrap.php';
$admin = require_user(true);
?>
<?php
$kind = 'users';
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
<title>Manage Users — NSBM ArtCraft Admin</title>
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
        padding: 26px 30px 20px;
        max-width: 1050px;
    }
    .card h1 { font-size: 23px; font-weight: 800; color: #0f172a; }
    .card .subtitle { font-size: 13px; color: #7c8aa0; margin: 6px 0 20px; }

    
    .search-row { display: flex; gap: 10px; margin-bottom: 22px; max-width: 580px; }
    .search-input-wrap {
        flex: 1; display: flex; align-items: center;
        background: #fbfcfe; border: 1px solid #dde4ec; border-radius: 8px; padding: 0 14px;
        transition: border-color .2s;
    }
    .search-input-wrap i { color: #94a3b8; font-size: 13px; margin-right: 8px; }
    .search-input-wrap input {
        border: none; outline: none; background: transparent; flex: 1;
        padding: 11px 0; font-size: 13px; font-family: inherit; color: #334155;
    }
    .search-input-wrap input::placeholder { color: #a5b1c2; }
    .search-input-wrap:focus-within { border-color: #25c364; background: #fff; }

    .btn-search {
        background: #25c364; color: #fff; border: none; border-radius: 8px;
        height: 42px; padding: 0 26px; font-size: 13.5px; font-weight: 600;
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
        padding: 14px 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px; color: #334155;
        vertical-align: middle;
    }
    tbody tr { transition: background .15s; }
    tbody tr:hover { background: #f8fafc; }
    tbody tr.hidden { display: none; }

    td.idx { color: #94a3b8; font-weight: 600; width: 36px; }
    
    .avatar {
        width: 42px; height: 42px; border-radius: 50%; object-fit: cover;
        box-shadow: 0 2px 6px rgba(15,23,42,.12); display: block;
    }
    .u-name { font-weight: 700; color: #0f172a; }
    .u-id { color: #55677d; font-weight: 500; }
    .u-email { color: #55677d; }

    .pill-active {
        display: inline-block;
        font-size: 11.5px; font-weight: 700;
        padding: 5px 14px; border-radius: 20px;
        background: #dcfce7; color: #16a34a;
    }

    .btn-view {
        border: none; border-radius: 7px; cursor: pointer;
        font-family: inherit; font-size: 12.5px; font-weight: 600;
        padding: 7px 18px; background: #e0e7ff; color: #2563eb;
        transition: background .2s, transform .1s;
        text-decoration: none; display: inline-block;
    }
    .btn-view:hover { background: #dbeafe; color: #1d4ed8; }
    .btn-view:active { transform: scale(.96); }

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
        <a href="manage_users.html" class="active"><i class="fa-solid fa-users"></i> Manage Users</a>
        <a href="manage_categories.php"><i class="fa-solid fa-layer-group"></i> Categories</a>
        <a href="feedback.html"><i class="fa-solid fa-comments"></i> Feedback</a>
        <a href="manage_exhibitions.php"><i class="fa-solid fa-calendar"></i> Exhibitions</a>
        <a href="reports.php"><i class="fa-solid fa-chart-column"></i> Reports</a>
        <a href="about.html"><i class="fa-solid fa-gear"></i> Profile</a>
        <a href="logout.html"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
    </aside>

    
    <main>
<div class="app-tools app-mobile-links" aria-label="Admin navigation">
<a href="admin.php">Dashboard</a><a href="manage_artworks.php">Artworks</a><a href="manage_users.php">Users</a><a href="manage_categories.php">Categories</a><a href="manage_exhibitions.php">Exhibitions</a><a href="reports.php">Reports</a>
</div>
        <div class="card">
            <h1>Manage Users</h1>
            <p class="subtitle">View and manage registered students</p>
<?php notice($error); ?>
<?php require __DIR__.'/admin-form.php'; ?>


            
            <form class="search-row" method="get" action="manage_users.php">
                <div class="search-input-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" name="q" value="<?=e($q)?>" maxlength="120" aria-label="Search users" placeholder="Search users...">
                </div>
                <button class="btn-search" id="searchBtn">Search</button>
            </form>

           
            <div class="app-table"><table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody">
<?php require __DIR__.'/users-rows-view.php'; ?>
</tbody>
            </table></div>
            <div class="empty-msg" id="emptyMsg" style="display:<?=$rows?'none':'block'?>">No users match your search.</div>
        </div>
    </main>

    
    <aside class="rail">
        <span class="rail-icon active"><i class="fa-solid fa-users"></i></span>
        <span class="rail-icon"><i class="fa-regular fa-image"></i></span>
        <span class="rail-icon"><i class="fa-regular fa-bell"></i></span>
        <span class="rail-icon"><i class="fa-solid fa-gear"></i></span>
        <span class="rail-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
    </aside>
</div>



</body>
</html>