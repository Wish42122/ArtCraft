<?php
require __DIR__.'/bootstrap.php';
$featured = sql(ART_QUERY." WHERE a.status='approved' AND a.featured=1 ORDER BY a.id DESC LIMIT 12")->fetchAll();
?>
<html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSBM ArtCraft</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #333;
        }

        
        header {
            background-color: #061e36;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 60px;
            color: white;
            position: relative;
            z-index: 10;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.5px;
        }

        .logo .white-text {
            color: #ffffff;
        }

        .logo .green-text {
            color: #25c364;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        nav a {
            color: #e2e8f0;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        nav a:hover {
            color: #25c364;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-icon {
            color: #e2e8f0;
            cursor: pointer;
            font-size: 14px;
        }

        .login-btn {
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .register-btn {
            background-color: #25c364;
            color: white;
            padding: 7px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s;
        }

        .register-btn:hover {
            background-color: #1eb057;
        }

       
        .hero {
            position: relative;
            background: linear-gradient(90deg, #061e36 0%, #061e36 40%, rgba(6, 30, 54, 0.85) 60%, rgba(6, 30, 54, 0.2) 100%), 
                        url('https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?auto=format&fit=crop&w=1400&q=80');
            background-size: cover;
            background-position: center right;
            min-height: 480px;
            display: flex;
            align-items: center;
            padding: 0 60px;
            color: white;
        }

        .hero-content {
            max-width: 580px;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 12px;
        }

        .hero-title .white-text {
            color: #ffffff;
        }

        .hero-title .green-text {
            color: #25c364;
            display: block;
        }

        .hero-subtitle {
            color: #25c364;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .hero-description {
            color: #cbd5e1;
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 30px;
            max-width: 500px;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-green {
            background-color: #25c364;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .btn-outline {
            background-color: transparent;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            display: inline-block;
            cursor: pointer;
        }

        
        .container {
            padding: 40px ;
        }

        .section-title {
            color: #0b2545;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 16px;
            margin-bottom: 40px;
        }

        .category-card {
            text-align: center;
            cursor: pointer;
        }

        .category-image {
            width: 100%;
            height: 130px;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-image img {
            transform: scale(1.05);
        }

        .category-name {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
        }

        
        .artworks-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .artwork-card {
            position: relative;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .artwork-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .artwork-card:hover img {
            transform: scale(1.05);
        }

        .favorite-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255, 255, 255, 0.85);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .favorite-btn:hover {
            background: white;
            color: #ef4444;
        }
    </style>
</head>
<body>

    
    <header>
        <div class="logo">
            <span class="white-text">NSBM</span>
            <span class="green-text">ArtCraft</span>
        </div>
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

    
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="white-text">NSBM</span>
                <span class="green-text">ArtCraft</span>
            </h1>
            <h2 class="hero-subtitle">Showcasing The Creativity Of NSBM Students</h2>
            <p class="hero-description">
                A platform to explore, share and celebrate student arts and handcrafts from NSBM Green University.
            </p>
            <div class="hero-buttons">
                <a href="artgallery.html" class="btn-green">Explore Gallery</a>
                <a href="myartwork.html" class="btn-outline">Show Your Art</a>
            </div>
        </div>
    </section>

    
    <div class="container">
        
        <h2 class="section-title">Explore Categories</h2>
        <div class="categories-grid">
            <div class="category-card">
                <div class="category-image">
                    <img src="photos/photo (51).jpeg" alt="Painting">
                </div>
                <div class="category-name">Painting</div>
            </div>
            <div class="category-card">
                <div class="category-image">
                    <img src="photos/photo (52).jpeg" alt="Drawing">
                </div>
                <div class="category-name">Drawing</div>
            </div>
            <div class="category-card">
                <div class="category-image">
                    <img src="photos/photo (36).jpeg" alt="Handcrafts">
                </div>
                <div class="category-name">Handcrafts</div>
            </div>
           
            <div class="category-card">
                <div class="category-image">
                    <img src="photos/photo (44).jpeg" alt="Photography">
                </div>
                <div class="category-name">Photography</div>
            </div>
            <div class="category-card">
                <div class="category-image">
                    <img src="photos/photo (56).jpeg" alt="DigitalArt">
                </div>
                <div class="category-name">DigitalArt</div>
            </div>
            
        </div>

        
        <h2 class="section-title">Featured Artworks</h2>
        <div class="artworks-grid">
<?php require __DIR__.'/featured-view.php'; ?>

            <div class="artwork-card">
                <img src="photos/photo (54).jpeg" alt="Sunset Artwork">
                <div class="favorite-btn">
                    <i class="fa-regular fa-heart"></i>
                </div>
            </div>
            <div class="artwork-card">
                <img src="photos/photo (58).jpeg" alt="Portrait Sketch">
                <div class="favorite-btn">
                    <i class="fa-regular fa-heart"></i>
                </div>
            </div>
            <div class="artwork-card">
                <img src="photos/photo (57).jpeg" alt="Tree Painting">
                <div class="favorite-btn">
                    <i class="fa-regular fa-heart"></i>
                </div>
            </div>
            <div class="artwork-card">
                <img src="photos/photo (49).jpeg" alt="Abstract Portrait">
                <div class="favorite-btn">
                    <i class="fa-regular fa-heart"></i>
                </div>
            </div>
        </div>
    </div>

</body>
</html>