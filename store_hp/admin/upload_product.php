<?php
include '../koneksi.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['add_product'])) {

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
   $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
   $stock = filter_var($_POST['stock'], FILTER_SANITIZE_STRING);

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/' . $image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/' . $image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/' . $image_03;

   $select_products = $koneksi->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if ($select_products->rowCount() > 0) {
      $message[] = 'Nama produk sudah ada!';
   } else {
      $insert_products = $koneksi->prepare("INSERT INTO `products`(name, details, price, image_01, image_02, image_03, stock) VALUES(?,?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $price, $image_01, $image_02, $image_03, $stock]);

      if ($insert_products) {
         if ($image_size_01 > 2000000 || $image_size_02 > 2000000 || $image_size_03 > 2000000) {
            $message[] = 'Ukuran gambar terlalu besar!';
         } else {
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'Produk baru berhasil ditambahkan!';
         }
         
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tambah Produk</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
   <section class="add-products">
      <h1 class="heading">Tambah Produk</h1>

      <form action="" method="post" enctype="multipart/form-data">
         <div class="form-group">
            <label for="name">Nama Produk:</label>
            <input type="text" id="name" required maxlength="100" placeholder="Masukkan nama produk" name="name">
         </div>
         <div class="form-group">
            <label for="price">Harga Produk:</label>
            <input type="number" id="price" min="0" required max="9999999999" placeholder="Masukkan harga produk" name="price">
         </div>
         <div class="form-group">
            <label for="image_01">Gambar 01:</label>
            <input type="file" id="image_01" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" required>
         </div>
         <div class="form-group">
            <label for="image_02">Gambar 02:</label>
            <input type="file" id="image_02" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" required>
         </div>
         <div class="form-group">
            <label for="image_03">Gambar 03:</label>
            <input type="file" id="image_03" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" required>
         </div>
         <div class="form-group">
            <label for="details">Detail Produk:</label>
            <textarea id="details" name="details" placeholder="Masukkan detail produk" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
         <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" id="stock" min="0" required max="9999999999" placeholder="Masukkan stock" name="stock">
         </div>
         <input type="submit" value="Tambahkan Produk" class="btn" name="add_product">
      </form>
   </section>
</body>
</html>
