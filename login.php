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

    <title>Log in</title>
  </head>
  <body>
      <div class="container">
          <div class="row">
              <div class="col-md-4 offset-md-4 form-div">
                  <form action="login.php" method="post">
                      <h2 class="text-center">Log in</h2>

                      <?php if(isset($errors['login_fail'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $errors['login_fail']; ?>
                        </div>
                      <?php endif; ?>

                      <div class="mb-3">
                          <label for="username" class="form-label">Email or Username</label>
                          <input type="text" class="form-control" name="username" value="<?php echo $username; ?>">
                          <?php if(isset($errors['username'])) : ?>
                              <div class="form-text text-danger">
                                  <?php echo $errors['username']; ?>
                              </div>
                          <?php endif; ?>
                      </div>

                      <div class="mb-3">
                          <label for="password" class="form-label">Password</label>
                          <input type="password" class="form-control" name="password" value="<?php echo $password; ?>">
                          <?php if(isset($errors['password'])) : ?>
                              <div class="form-text text-danger">
                                  <?php echo $errors['password']; ?>
                              </div>
                          <?php endif; ?>
                      </div>
                      
                      <button type="submit" class="btn btn-primary w-100" name="login">Log in</button>

                      <p class="text-center mt-4">Don't have an account? <a href="signup.php">Sign Up</a></p>
                  </form>
              </div>
          </div>
      </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    
  </body>
</html>