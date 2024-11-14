<?php

include '../koneksi.php';

session_start();

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $koneksi->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if ($select_admin->rowCount() > 0) {
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
   } else {
      $message[] = 'Username atau kata sandi salah!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin.css">
   <style>
      /* Style umum untuk seluruh halaman */
body {
   font-family: 'Arial', sans-serif;
   background: #f0f2f5; /* Warna latar belakang cerah */
   margin: 0;
   padding: 0;
   display: flex;
   justify-content: center;
   align-items: center;
   height: 100vh;
   background-image: linear-gradient(to right, #00c6ff, #0072ff); /* Gradasi warna latar belakang */
}

/* Style untuk form login */
.form-container {
   background: #fff;
   padding: 40px;
   border-radius: 10px;
   box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
   width: 100%;
   max-width: 400px;
   text-align: center;
}

h3 {
   color: #333;
   font-size: 24px;
   margin-bottom: 20px;
   font-weight: 600;
}

p {
   color: #666;
   font-size: 14px;
   margin-bottom: 20px;
}

span {
   font-weight: bold;
   color: #0072ff;
}

/* Input field styles */
input[type="text"], input[type="password"] {
   width: 100%;
   padding: 12px;
   margin: 10px 0;
   border: 1px solid #ccc;
   border-radius: 5px;
   font-size: 16px;
   transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input[type="text"]:focus, input[type="password"]:focus {
   border-color: #0072ff;
   box-shadow: 0 0 10px rgba(0, 114, 255, 0.5);
   outline: none;
}

/* Tombol submit */
input[type="submit"] {
   width: 100%;
   padding: 14px;
   background-color: #0072ff;
   color: #fff;
   font-size: 16px;
   border: none;
   border-radius: 5px;
   cursor: pointer;
   transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
   background-color: #005bb5;
}

/* Pesan error */
.message {
   background-color: #ffcccc;
   color: #cc0000;
   padding: 10px;
   margin-bottom: 20px;
   border-radius: 5px;
   text-align: center;
   font-size: 14px;
}

.message span {
   margin-right: 10px;
}

.message i {
   cursor: pointer;
}

/* Responsive design untuk perangkat mobile */
@media (max-width: 768px) {
   .form-container {
      padding: 30px;
      width: 90%;
   }

   h3 {
      font-size: 20px;
   }
}

   </style>

</head>

<body>

   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
         <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
   ?>

   <section class="form-container">

      <form action="" method="post">
         <h3>Masuk sekarang</h3>
         <p>default username = <span>admin</span> & password = <span>1</span></p>
         <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" required placeholder="enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="Masuk sekarang" class="btn" name="submit">
      </form>

   </section>

</body>

</html>