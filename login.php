<?php
include("..\\connect.php");
session_start();

mysqli_select_db($connect,"restaurant");

// Create table if it doesn't exist
$query = "CREATE TABLE IF NOT EXISTS admins (
    name varchar(50),
    username varchar(50),
    password varchar(18),
    email varchar(50),
    photo varchar(100)
)";
mysqli_query($connect, $query);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    
    // Handle file upload
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileSize = $_FILES['photo']['size'];
        $fileType = $_FILES['photo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Check file extension and size (optional)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB
        
        if (in_array($fileExtension, $allowedExtensions) && $fileSize < $maxFileSize) {
            $uploadFileDir = '../uploads/';
            $dest_path = $uploadFileDir . $fileName;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $photo = $dest_path;
            } else {
                echo "Error moving the file.";
            }
        } else {
            echo "Invalid file extension or size.";
        }
    }
    
    if (!empty($name) && !empty($username) && !empty($password) && !empty($email)) {
        $query = "INSERT INTO admins (name, username, password, email, photo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("sssss", $name, $username, $password, $email, $photo);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $_SESSION['name'] = $name;
            $_SESSION['photo'] = $photo ? $photo : './images/user.png';
            header("Location: ../index.php");
            exit();
        } else {
            echo "Error registering the user.";
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Images Admin | Login/Register</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form method='POST' action="<?= $_SERVER['PHP_SELF'] ?>">
              <h1>Login Form</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" name=user-name />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" name=pass />
              </div>
              <div>
                <button class="btn btn-default submit" name=login>Log in</button>
                <a class="reset_pass" href="#">Lost your password?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-file-image-o"></i></i> Images Admin</h1>
                  <p>©2016 All Rights Reserved. Images Admin is a Bootstrap 4 template. Privacy and Terms</p>
                </div>
              </div>
            </form>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form method='POST' action="<?= $_SERVER['PHP_SELF'] ?>" >
              <h1>Create Account</h1>
              <div>
                <input type="text" class="form-control" placeholder="Fullname" required=""  name=name />
              </div>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" name=username />
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Email" required="" name=email />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" name=password />
              </div>
              <div>
                <input type="file" class="form-control" name="photo" />
              </div>
              <div>
                <button type="submit" class="btn btn-default submit" name=submit>Submit</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Log in </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-file-image-o"></i></i> Images Admin</h1>
                  <p>©2016 All Rights Reserved. Images Admin is a Bootstrap 4 template. Privacy and Terms</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>