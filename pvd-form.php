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

  <title>Pixel Value Differencing</title>
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

      <li class="nav-item">
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
        <a class="nav-link" href="pvd-form.php">
          <i class="bi bi-dash-circle"></i>
          <span>Pixel Value Differencing</span>
        </a>
      </li><!-- End Error 404 Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="report.php">
          <i class="bi bi-file-earmark"></i>
          <span>Report</span>
        </a>
      </li><!-- End Blank Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Image Steganography using PVD</h1>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Encode Secret Message</h5>

              <!-- General Form Elements -->
              <form>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Secret Message</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="message">
                  </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-2 col-form-label">Message For</label>
                    <div class="col-sm-10">
                      <?php

                      $sql = "SELECT yourUsername FROM user;";
                      $result = mysqli_query($link, $sql);
                      echo "<select name='Select User Name' style='height:37px;' id='receiver' class='form-control' placeholder='Select Users Name' >";
                      echo '<option value=""> Select a User</option>';
                      while ($row = mysqli_fetch_assoc($result)) {
                          echo '<option value="' . $row['yourUsername'] . '">' . $row['yourUsername'] . '</option>';
                      }
                      echo '</select>';

                      // Close the database connection
                      mysqli_close($link);
                      ?>
                    </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">File Upload</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="imageFile">
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Encoded Image</label>
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary" onclick="encode(event);">Download</button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>
      </div>
      <input type="hidden" id="username" value="<?php echo $_SESSION["yourUsername"]?>">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Decode Secret Message</h5>

              <!-- General Form Elements -->
              <form>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">File Encoded Upload</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="imageFile1">
                  </div>
                </div>

                <div class="row mb-3">
                    <label for="inputText" class="col-sm-2 col-form-label">Secret Message</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="textMsg" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Decode</label>
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary" onclick="decode(event);">Decode</button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>
      </div>
    </section>

    <section class="section">
        <div class="row">
          <div class="col-lg-12">
  
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Previous executed records</h5>
                <p>Entries of current user only </p>
  
                <!-- Table with stripped rows -->
                <table class="table" id="tableData"></table>
                <!-- End Table with stripped rows -->
  
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
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
        function encode(event){
        event.preventDefault(); 
        var button = event.currentTarget; // get a reference to the button element
        var formData = new FormData();
        formData.append("message", $("#message").val());
        formData.append("receiver", $("#receiver").val());
        formData.append("username", $("#username").val());
        formData.append("imageFile", $("#imageFile").get(0).files[0]);
    
        if($("#message").val()=="" || $("#receiver").val()==""  || $("#imageFile").val()=="" )
        {
            alert("Some mandatory field(s) missing.");
        }
        else{
            $.ajax({
            type:"POST",
            url:"uploads/pvd_download.php",
                data:formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    button.disabled = true; // disable the button while the request is being processed
                },
                success: function(response) {
                  console.log(response);
                  
                  if($.trim(response)=="LARGE")
                    alert("Sorry, your file is too large.Sorry, your file was not uploaded.")
                  else if($.trim(response)=="INVALID IMAGE")
                    alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                  else if($.trim(response)=="EXISTS")
                    alert("Sorry, file already exists.Sorry, your file was not uploaded.");
                  else{
                    var pattern = /Image Name:\s*(\S+)/;
                    var match = response.match(pattern);
                    if (match && match[1]) {
                        var image_name = match[1];
                    } 

                    var url = URL.createObjectURL(new Blob([image_name], {type: 'image/png'}));

                    // Create a link element and set its href attribute to the URL object
                    var link = document.createElement("a");
                    link.href = url;

                    // Set the download attribute and filename of the link element
                    link.download = image_name;

                    // Append the link element to the document body
                    document.body.appendChild(link);

                    // Click the link element to trigger the download
                    link.click();

                    // Remove the link element from the document body
                    document.body.removeChild(link);
                    viewTable();
                  }
                },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
        },
        complete: function() {
            button.disabled = false; // re-enable the button after the request is complete
        }
     });
   }
   }


   function decode(event){
    event.preventDefault(); 
    var button = event.currentTarget; // get a reference to the button element
    var formData = new FormData();
    var fileInput = document.getElementById("imageFile1");
    const selectedFile = fileInput.files[0];

    if (selectedFile) {
      formData.append("imageFile", selectedFile.name);
    }
   // var fileInput = document.getElementById("imageFile1");
   // var selectedFile = fileInput
   formData.append("imageFile1", $("#imageFile1").get(0).files[0]);
    //formData.append("username", $("#username").value);

   if($("#imageFile1").val()=="" )
   {
     alert("Some Mandatory Fields Unattended");
   }
   else{
       $.ajax({
       type:"POST",
       url:"uploads/pvd_decode.php",
         data:formData,
         processData: false,
        contentType: false,
        beforeSend: function() {
            button.disabled = true; // disable the button while the request is being processed
        },
        success: function(response) {
          console.log("decoded : "+response);
          if($.trim(response)=="DENIED"){
            alert('You are not authoried to read this message');
          }
          else{
            document.getElementById('textMsg').value=response;
          }
            //let cleanedMessage = response.replace(/[^\x20-\x7E]/g, '');
            //alert(cleanedMessage);
        },
        error: function(xhr, status, error) {
            console.error(xhr, status, error);
        },
        complete: function() {
            button.disabled = false; // re-enable the button after the request is complete
        }
     });
   }
   }

   function viewTable(){
    $.ajax({
      type:"POST",
      url:"uploads/pvd_table.php",
        data:{},
        success:function(response){
        $("#tableData").html(response);
        }
    });
   }
   viewTable();
   </script>
</body>

</html>