<?php
// Initialize the session
session_start();
include("php/db_connect.php");
$db=new DB_Connect();
$link=$db->connect();
  if($link == false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
    header("location:pages-login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Report</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Stegano Audit</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION["yourUsername"]; ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            
            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-login.html">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-heading">Image Steganography Techniques</li>

      <li class="nav-item active">
        <a class="nav-link collapsed" href="lsb-form.php">
          <i class="bi bi-envelope"></i>
          <span>Least Significant Bit </span>
        </a>
      </li><!-- End Contact Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="sst-form.php">
          <i class="bi bi-card-list"></i>
          <span>Spread Spectrum Technique</span>
        </a>
      </li><!-- End Register Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="dct-form.php">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Discrete Cosine Transform</span>
        </a>
      </li><!-- End Login Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pvd-form.php">
          <i class="bi bi-dash-circle"></i>
          <span>Pixel Value Differencing</span>
        </a>
      </li><!-- End Error 404 Page Nav -->

      <li class="nav-item">
        <a class="nav-link" href="report.php">
          <i class="bi bi-file-earmark"></i>
          <span>Report</span>
        </a>
      </li><!-- End Blank Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Analysis </h1>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">

       

        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Time Chart (in sec) </h5>

              <!-- Pie Chart -->
              <canvas id="pieChart" style="max-height: 400px;"></canvas>
              <?php  
              $sql1 = "SELECT time FROM lsb";
              $result1 = mysqli_query($link, $sql1);
              $sum1 = 0;
              $count1 = mysqli_num_rows($result1);
              while($row1 = mysqli_fetch_assoc($result1)) {
                  $sum1 += $row1["time"];
              }
              $averageLSB = $sum1 / $count1;

              $sql2 = "SELECT time FROM sst";
              $result2 = mysqli_query($link, $sql2);
              $sum2 = 0;
              $count2 = mysqli_num_rows($result2);
              while($row2 = mysqli_fetch_assoc($result2)) {
                  $sum2 += $row2["time"];
              }
              $averageSST = $sum2 / $count2;

              $sql3 = "SELECT time FROM dct";
              $result3 = mysqli_query($link, $sql3);
              $sum3 = 0;
              $count3 = mysqli_num_rows($result3);
              while($row3 = mysqli_fetch_assoc($result3)) {
                  $sum3 += $row3["time"];
              }
              $averageDCT = $sum3 / $count3;

              $sql4 = "SELECT time FROM pvd";
              $result4 = mysqli_query($link, $sql4);
              $sum4 = 0;
              $count4 = mysqli_num_rows($result4);
              while($row4 = mysqli_fetch_assoc($result4)) {
                  $sum4 += $row4["time"];
              }
              $averagePVD = $sum4 / $count4;
           ?>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#pieChart'), {
                    type: 'pie',
                    data: {
                      labels: [
                        'LSB',
                        'SST',
                        'DCT',
                        'PVD'
                      ],
                      datasets: [{
                        label: 'Time taken to encode',
                        data: [<?php echo $averageLSB ?>, <?php echo $averageSST ?>, <?php echo $averageDCT ?>, <?php echo $averagePVD ?>],
                        backgroundColor: [
                          'rgb(255, 99, 132)',
                          'rgb(54, 162, 235)',
                          'rgb(255, 205, 86)',
                          'rgb(50, 205, 50)'
                        ],
                        hoverOffset: 4
                      }]
                    }
                  });
                });
              </script>
              <!-- End Pie CHart -->

            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">CPU Chart (in %)</h5>

              <!-- Pie Chart -->
              <canvas id="pieChart1" style="max-height: 400px;"></canvas>
              <?php  
              $sql1c = "SELECT cpu FROM lsb";
              $result1c = mysqli_query($link, $sql1c);
              $sum1c = 0;
              $count1c = mysqli_num_rows($result1c);
              while($row1c = mysqli_fetch_assoc($result1c)) {
                  $sum1c += $row1c["cpu"];
              }
              $averageLSBc = $sum1c / $count1c;

              $sql2c = "SELECT cpu FROM sst";
              $result2c = mysqli_query($link, $sql2c);
              $sum2c = 0;
              $count2c = mysqli_num_rows($result2c);
              while($row2c = mysqli_fetch_assoc($result2c)) {
                  $sum2c += $row2c["cpu"];
              }
              $averageSSTc = $sum2c / $count2c;

              $sql3c = "SELECT cpu FROM dct";
              $result3c = mysqli_query($link, $sql3c);
              $sum3c = 0;
              $count3c = mysqli_num_rows($result3c);
              while($row3c = mysqli_fetch_assoc($result3c)) {
                  $sum3c += $row3c["cpu"];
              }
              $averageDCTc = $sum3c / $count3c;

              $sql4c = "SELECT cpu FROM dct";
              $result4c = mysqli_query($link, $sql4c);
              $sum4c = 0;
              $count4c = mysqli_num_rows($result4c);
              while($row4c = mysqli_fetch_assoc($result4c)) {
                  $sum4c += $row4c["cpu"];
              }
              $averagePVDc = $sum4c / $count4c;
           ?>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#pieChart1'), {
                    type: 'pie',
                    data: {
                      labels: [
                        'LSB',
                        'SST',
                        'DCT',
                        'PVD'
                      ],
                      datasets: [{
                        label: 'CPU consumed to encode',
                        data: [<?php echo $averageLSBc ?>, <?php echo $averageSSTc ?>, <?php echo $averageDCTc ?>, <?php echo $averagePVDc ?>],
                        backgroundColor: [
                          'rgb(255, 99, 132)',
                          'rgb(54, 162, 235)',
                          'rgb(255, 205, 86)',
                          'rgb(50, 205, 50)'
                        ],
                        hoverOffset: 4
                      }]
                    }
                  });
                });
              </script>
              <!-- End Pie CHart -->

            </div>
          </div>
        </div>

        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Image Compression Chart (Size lost in kb)</h5>

              <!-- Doughnut Chart -->
              <canvas id="doughnutChart" style="max-height: 400px;"></canvas>
              <?php  
              $sql1s = "SELECT size,newSize FROM lsb";
              $result1s = mysqli_query($link, $sql1s);
              $sum1s = 0;
              $count1s = mysqli_num_rows($result1s);
              while($row1s = mysqli_fetch_assoc($result1s)) {
                $sum1s += $row1s["size"]- $row1s["newSize"];
              }
              $averageLSBs = $sum1s / $count1s;

              $sql2s = "SELECT size,newSize FROM sst";
              $result2s = mysqli_query($link, $sql2s);
              $sum2s = 0;
              $count2s = mysqli_num_rows($result2s);
              while($row2s = mysqli_fetch_assoc($result2s)) {
                  $sum2s += $row2s["size"]- $row2s["newSize"];
              }
              $averageSSTs = $sum2s / $count2s;

              $sql3s = "SELECT size,newSize FROM dct";
              $result3s = mysqli_query($link, $sql3s);
              $sum3s = 0;
              $count3s = mysqli_num_rows($result3s);
              while($row3s = mysqli_fetch_assoc($result3s)) {
                  $sum3s += $row3s["size"]- $row3s["newSize"];
              }
              $averageDCTs = $sum3s / $count3s;

              $sql4s = "SELECT size,newSize FROM dct";
              $result4s = mysqli_query($link, $sql4s);
              $sum4s = 0;
              $count4s = mysqli_num_rows($result4s);
              while($row4s = mysqli_fetch_assoc($result4s)) {
                  $sum4s += $row4s["size"]- $row4s["newSize"];
              }
              $averagePVDs = $sum4s / $count4s;
           ?>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#doughnutChart'), {
                    type: 'doughnut',
                    data: {
                      labels: [
                        'LSB',
                        'SST',
                        'DCT',
                        'PVD'
                      ],
                      datasets: [{
                        label: 'Image size loss',
                        data: [<?php echo $averageLSBs ?>, <?php echo $averageSSTs ?>, <?php echo $averageDCTs ?>, <?php echo $averagePVDs ?>],
                        backgroundColor: [
                          'rgb(255, 99, 132)',
                          'rgb(54, 162, 235)',
                          'rgb(255, 205, 86)',
                          'rgb(50, 205, 50)'
                        ],
                        hoverOffset: 4
                      }]
                    }
                  });
                });
              </script>
              <!-- End Doughnut CHart -->

            </div>
          </div>
        </div>


      </div>
    </section>

  </main><!-- End #main -->


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>