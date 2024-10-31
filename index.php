<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Main Body -->
  <section class="container py-5" id="sec-9525">
    <div class="row justify-content-center">
      <!-- Log In Container -->
      <div class="col-md-6 col-lg-4 mb-4 mt-3">
        <div class="card shadow p-4">
          <h2 class="text-center">Log In</h2>
          <form action="Home.php" method="POST">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="text-right">
              <button type="submit
              " class="btn login-btn" style="background-color: #FAED5E; color: black;">Log In</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Welcome Box -->
      <div class="col-md-6 col-lg-6">
        <div class="box-379576 text-white" style="background-color: #379576; padding: 20px; border-radius: 5px;">
          <h4 class="text-center">Welcome to ERPTS</h4>
          <p>From the Assessor’s Module you can:</p>
          <ul>
            <li>Search for information in Owner’s Declaration (OD), Assessor’s Field Sheet/FAAS, Tax Declaration (TD),
              or RPTOP.</li>
            <li>Encode new real property information.</li>
          </ul>
          <p>To begin:</p>
          <ul>
            <li>Encode Property Information in the OD</li>
            <li>Select or encode Owner Information</li>
            <li>Encode Real Property Information in the AFS/FAAS</li>
            <li>Encode Tax-related Information in the TD</li>
            <li>Generate the RPTOP</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
</body>

</html>