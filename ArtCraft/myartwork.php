<?php require __DIR__.'/myartwork-action.php'; ?>
<html>
<html lang="en">
<head>
<link rel="stylesheet" href="style.css">
<script src="script.js" defer></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Artworks — NSBM ArtCraft</title>
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
        padding: 13px 40px;
        color: #fff;
        height: 58px;
        position: sticky; top: 0; z-index: 20;
    }
    .logo { font-size: 23px; font-weight: 800; display: flex; align-items: center; gap: 8px; letter-spacing: .4px; cursor: pointer; }
    .logo .w { color: #fff; }
    .logo .g { color: #25c364; }

    nav { display: flex; align-items: center; gap: 26px; }
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
        width: 205px;
        background: #0a2540;
        flex-shrink: 0;
        padding: 14px 0;
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

    
    main { flex: 1; padding: 32px 40px 60px; min-width: 0; }
    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .07);
        padding: 30px 34px 20px;
        max-width: 980px;
    }
    .card h1 { font-size: 24px; font-weight: 800; color: #0f172a; }
    .card .subtitle { font-size: 13.5px; color: #7c8aa0; margin: 6px 0 22px; }

    
    .tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 8px; border-bottom: 1px solid #eef2f6; padding-bottom: 14px; }
    .tab {
        border: none; background: transparent; cursor: pointer;
        font-family: inherit; font-size: 13px; font-weight: 600;
        color: #55677d; padding: 8px 16px; border-radius: 20px;
        transition: all .2s;
    }
    .tab:hover { background: #f1f5f9; }
    .tab.active { background: #25c364; color: #fff; }
    .tab.rejected-tab { color: #ef4444; }
    .tab.rejected-tab:hover { background: #fef2f2; }

    
    .art-row {
        display: flex; align-items: center; gap: 18px;
        padding: 16px 4px;
        border-bottom: 1px solid #f1f5f9;
        transition: background .2s;
    }
    .art-row:hover { background: #fafcfd; }
    .art-row.hidden { display: none; }

    .thumb {
        width: 58px; height: 58px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(15,23,42,.12);
    }

    .art-info { flex: 1; min-width: 0; }
    .art-title { font-size: 14.5px; font-weight: 700; color: #0f172a; }
    .art-cat { font-size: 12.5px; color: #7c8aa0; margin-top: 2px; }
    .art-date { font-size: 12px; color: #9aa8ba; margin-top: 3px; }

    .badge {
        font-size: 12px; font-weight: 700;
        padding: 6px 16px; border-radius: 20px;
        flex-shrink: 0;
    }
    .badge.approved { background: #dcfce7; color: #16a34a; }
    .badge.pending  { background: #fef3c7; color: #d97706; }
    .badge.rejected { background: #fee2e2; color: #dc2626; }

    .actions { display: flex; gap: 16px; flex-shrink: 0; }
    .actions a {
        font-size: 13px; font-weight: 600; text-decoration: none;
        color: #475569; transition: color .2s;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .actions a:hover { color: #25c364; }
    .actions a.delete { color: #ef4444; }
    .actions a.delete:hover { color: #dc2626; }
    .actions a i { font-size: 12px; }

    .empty-msg { padding: 40px; text-align: center; color: #94a3b8; font-size: 14px; display: none; }

    
    @media (max-width: 1000px) {
        .sidebar, .rail { display: none; }
        main { padding: 22px 16px 50px; }
        header { padding: 13px 20px; }
        nav { display: none; }
        .badge { display: none; }
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
        <?php if ($user['role']==='admin'): ?>
        <a href="admin.php"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
        <?php else: ?><a href="homepage.html"><i class="fa-solid fa-house"></i> Home</a><?php endif; ?>
        <a href="myartwork.html"><i class="fa-solid fa-palette"></i> My Artworks</a>
        <a href="upload.html"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Artwork</a>
        <a href="about.html"><i class="fa-regular fa-user"></i> Profile</a>
        
        <a href="exhibitions.php"><i class="fa-solid fa-calendar"></i> Exhibitions</a>
        <a href="logout.html"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
    </aside>

    
    <main>
<div class="app-tools app-mobile-links" aria-label="Student navigation">
<a href="myartwork.php">My Artworks</a><a href="upload.php">Upload Artwork</a><a href="exhibitions.php">Exhibitions</a><a href="homepage.html">Home</a>
</div><?php require __DIR__.'/myartwork-view.php'; ?>

</main>

    
    <aside class="rail">
        <span class="rail-icon active"><i class="fa-regular fa-image"></i></span>
        <span class="rail-icon"><i class="fa-solid fa-pen-to-square"></i></span>
        <span class="rail-icon"><i class="fa-regular fa-user"></i></span>
        <span class="rail-icon"><i class="fa-solid fa-gear"></i></span>
        <span class="rail-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
    </aside>
</div>



</body>
</html>
