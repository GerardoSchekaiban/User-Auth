<?php require_once 'controllers/authController.php'; ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css">

    <title>Home</title>
  </head>
  <body>
      <div class="container">
          <div class="row">
              <div class="col-md-4 offset-md-4 form-div">
              <?php if(isset($_SESSION['message'])) : ?>
                <div class="alert alert-success">
                    <?php 
                      echo $_SESSION['message'];
                      unset($_SESSION['message']);
                    ?>
                </div>
              <?php endif; ?>
              <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
              <a href="#" class="link-danger">Log Out</a>
              </div>
          </div>
      </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    
  </body>
</html>