<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="canonical" href="https://topcolleges.co.in" />
    <title>
      Top Colleges in India | Best Colleges for Engineering, Management, Medical
      & More
    </title>
    <meta
      name="description"
      content="Explore the best engineering colleges in India with detailed course info, fees,
     admissions, and rankings. Top Colleges"
    />
    <meta
      name="keywords"
      content="engineering colleges India, Top Colleges best engineering colleges, top engineering universities, college
     admissions"
    />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/fav.png" />

    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="images/fav.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="images/fav.png" />

    <!-- Stylesheets Start -->
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:400,500,700"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/meanmenu.min.css" />
    <link rel="stylesheet" href="css/responsive.css" />
  </head>
  <body class="">
  <?php
  if(session_status() === PHP_SESSION_NONE) session_start();
  if(!empty($_SESSION['flash_success'])): ?>
    <div class="container mt-3"><div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div></div>
  <?php endif; ?>
  <?php if(!empty($_SESSION['flash_error'])): ?>
    <div class="container mt-3"><div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div></div>
  <?php endif; ?>
