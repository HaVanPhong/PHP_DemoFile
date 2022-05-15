<?php
session_start();
$_SESSION['loggedin'] = false;
$_SESSION['current_account']= false;
include './menu.php';

if (!empty($_POST)) {

  switch ($_POST) {
  case isset($_POST['A']):
    $account = [
        'password' => hash('md5', $_POST['password']),
        'email' => strtolower($_POST['email']),
      ];
    $fp = fopen('account.db', 'r');
    while (($line = fgets($fp)) !== false) {
        $temp = explode(",", $line);
        if (($temp[0] == $account['password']) && (trim($temp[1]) == $account['email'])){
            $_SESSION['loggedin'] = true;
            $_SESSION['current_account']= $account['email'];
            header('location: my_account.php');
        }
    }
    echo "INVALID INFORMATION.";
    fclose($fp);
  break;

  case isset($_POST['B']):
    if (isset($_FILES['avt'])){
      echo "file toots";
    }else {
      echo "file ddeue vl";
    }
    $account = [
        'password' => hash('md5', $_POST['password']),
        'email' => strtolower($_POST['email']),
        'fullname' => strtolower($_POST['fullname']),
        'avt' => strtolower(uploadFile($_FILES['avt']))
      ];
    $fp = fopen('account.db', 'a');
    fputcsv($fp, $account); 
    fclose($fp);
    
    
    break;
  }
}


function uploadFile($F){
  if (isset($F)){
    $file = $F;
    $filename= $file['name'];
    $filename= explode('.', $filename);
    echo "filename: ".$filename;
    $ext = end($filename);
    $new_file= uniqid().'.'.$ext;
  
    $allow_size=100;
    //kiểm tra định dạng
    $allow_ext=['png', 'jpg', 'jpeg', 'gif', 'jfif'];
  
  
    if (in_array($ext, $allow_ext)){
      $size= $file['size']/1024/1024; //convert to MB
      if ($size <= $allow_size){
        $target= 'images/'.$new_file;
        $upload= move_uploaded_file($file['tmp_name'], $target);
        if ($upload){
          return $target;
        }
      }
    }
  }  
  return "null";
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>InstaKilogram</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>

    <!-- Instakilogam icon -->
    <div class="row container-fluid m-auto bg-white sticky-top">
        <div class="col">
          <div class="text-start">
            <a href="login.php"><img src="https://i.ibb.co/8gyz20H/icon.png" class="icon"></a>
          </div>
        </div>
    </div>

    <!-- Login form -->
    <div class="container-fluid bg-1 p-2">
      <h1>Login</h1>
    </div>
    <form method="post" action="login.php">
        <div class="container-fluid p-2">
          <div class="row gx-5">
            <div class="col-1">
              <label for="email" class="form-label">Email:</label>
            </div>
            <div class="col-3 pb-3">
              <input type="email" class="form-control"placeholder="email@gmail.com" name="email" required>
            </div>
          </div>
          <div class="row gx-5">
            <div class="col-1">
              <label for="Password" class="form-label">Password:</label>
            </div>
            <div class="col-3 pb-3">
              <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
          </div>
          <div class="row gx-5">
            <div class = "col-1">
              <input type="submit" name="A" value="Log In" class="btn bg-1">
            </div>
          </div>
        </div>   
    </form>
    <br>
    <!-- <div class="container Sign up">
            <label>Don't have an account ?</label>
            <a href="url">Sign up</a>
        </div>
        <div class="container Login">
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="disabledFieldsetCheck" disabled>
                <label class="form-check-label" for="disabledFieldsetCheck">
                    Can't check this
                </label>
            </div>
            </div>
              
        </div> -->
    <div class="container-fluid bg-1 p-2">
      <h1>Register</h1>
    </div>
    <form method="post" action="login.php" id="register" enctype="multipart/form-data"> 
      <div class="container-fluid">
        <div class="row gx-5 p-2">
          <div class="col-1">
            <label for="email" class="form-label">Email:</label>
          </div>
          <div class="col-3 pb-3">
            <input type="email" class="form-control" placeholder="email@gmail.com" name="email" id="email" required>
          </div>
        </div>
        <div class="row gx-5">
          <div class="col-1">
            <label for="password1" class="form-label">Password1:</label>
          </div>
          <div class="col-3 pb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" id="password1" required>
          </div>
        </div>

        <!-- .... -->
        <div class="row gx-5">
          <div class="col-1">
            <label for="password2" class="form-label">Password2:</label>
          </div>
          <div class="col-3 pb-3">
            <input type="password" class="form-control" placeholder="Password" id="password2" required>
          </div>
        </div>

        <div class="row gx-5">
          <div class="col-1">
            <label for="fullname" class="form-label">Fullname:</label>
          </div>
          <div class="col-3 pb-3">
            <input type="text" class="form-control" placeholder="Fullname" name="fullname" required>
          </div>
        </div>

        <div class="row gx-5">
          <div class="col-1">
            <label for="avt" class="form-label">Avatar:</label>
          </div>
          <div class="col-3 pb-3">
            <input type="file" class="form-control" name="avt" id="file" required>
          </div>
        </div>
        

        <div class="row gx-5">
          <div class = "col-1">
            <input type="button" name="B" value="Register" class="btn bg-1" id="btnSubmit" onclick="submitRegister()">
          </div>
        </div> 
      </div>   
    </form>
        
  </body>
  <script>
    let btnSubmit= document.getElementById("btnSubmit");
    let formRegister= document.getElementById('register');
    let email= document.getElementById("email");
    let password1= document.getElementById("password1");
    let password2= document.getElementById("password2");
    let fullname= document.getElementById("fullname");
    let file= document.getElementById("file");
    let check= true;
    function submitRegister(){
        if (password1.value!==password2.value){
          alert("vui long nhap ddung password");
          check=false;
        }
        if (fullname.value.split(" ").length<=2){
          check=false;
        }
        if (check){
          formRegister.submit();
        }
    }
  </script>
</html>
