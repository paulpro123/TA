<?php

include '../koneksi.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_POST['add_product'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
   $stock = $_POST['stock'];
   $stock = filter_var($stock, FILTER_SANITIZE_STRING);

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
      $message[] = 'nama produk sudah ada!';
   } else {

      $insert_products = $koneksi->prepare("INSERT INTO `products`(name, details, price, image_01, image_02, image_03, stock) VALUES(?,?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $price, $image_01, $image_02, $image_03, $stock]);

      if ($insert_products) {
         if ($image_size_01 > 2000000 or $image_size_02 > 2000000 or $image_size_03 > 2000000) {
            $message[] = 'ukuran gambar terlalu besar!';
         } else {
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'produk baru ditambahkan!';
         }
      }
   }
};

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_product_image = $koneksi->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
   unlink('../uploaded_img/' . $fetch_delete_image['image_02']);
   unlink('../uploaded_img/' . $fetch_delete_image['image_03']);
   $delete_product = $koneksi->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $koneksi->prepare("DELETE FROM `cart` WHERE product_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $koneksi->prepare("DELETE FROM `wishlist` WHERE product_id = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:produk.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tambah Produk</title>

   <!-- Font Awesome untuk ikon -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Link ke CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <!-- Tambahkan CSS untuk membuat tabel lebih lebar dan rapi -->
   <style>
      .table-container {
         width: 100%;
         overflow-x: auto;
      }

      table {
         width: 100%;
         border-collapse: collapse;
      }

      th, td {
         padding: 15px;
         text-align: left;
         border: 1px solid #ddd;
      }

      th {
         background-color: #f2f2f2;
         font-weight: bold;
      }

      tr:nth-child(even) {
         background-color: #f9f9f9;
      }

      img {
         width: 120px;
         height: auto;
      }
   </style>
</head>

<body>

   <section class="add-products">
      <h1 class="heading">Tambah Produk</h1>

      <form action="" method="post" enctype="multipart/form-data">
         <div class="form-group">
            <label for="name">Nama Produk:</label>
            <span class="form-label">Nama produk yang akan ditambahkan</span>
            <input type="text" id="name" class="form-control" required maxlength="100" placeholder="Masukkan nama produk" name="name">
         </div>
         <div class="form-group">
            <label for="price">Harga Produk:</label>
            <span class="form-label">Harga dari produk yang akan dijual</span>
            <input type="number" id="price" min="0" class="form-control" required max="9999999999" placeholder="Masukkan harga produk" name="price">
         </div>
         <div class="form-group">
            <label for="image_01">Gambar 01:</label>
            <span class="form-label">Upload gambar pertama</span>
            <input type="file" id="image_01" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
         </div>
         <div class="form-group">
            <label for="image_02">Gambar 02:</label>
            <span class="form-label">Upload gambar kedua</span>
            <input type="file" id="image_02" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
         </div>
         <div class="form-group">
            <label for="image_03">Gambar 03:</label>
            <span class="form-label">Upload gambar ketiga</span>
            <input type="file" id="image_03" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
         </div>
         <div class="form-group">
            <label for="details">Detail Produk:</label>
            <span class="form-label">Deskripsi singkat produk</span>
            <textarea id="details" name="details" placeholder="Masukkan detail produk" class="form-control" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
         <div class="form-group">
            <label for="stock">Stock:</label>
            <span class="form-label">Jumlah stok yang tersedia</span>
            <input type="number" id="stock" min="0" class="form-control" required max="9999999999" placeholder="Masukkan stock" name="stock">
         </div>

         <input type="submit" value="Tambahkan Produk" class="btn" name="add_product">
      </form>
   </section>

   <section class="show-products">
      <h1 class="heading">Daftar Produk</h1>
      
      <div class="table-container">
         <table>
            <thead>
               <tr>
                  <th>Gambar</th>
                  <th>Nama Produk</th>
                  <th>Harga</th>
                  <th>Detail</th>
                  <th>Stock</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $select_products = $koneksi->prepare("SELECT * FROM `products`");
               $select_products->execute();
               if ($select_products->rowCount() > 0) {
                  while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <td><img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="Gambar Produk"></td>
                        <td><?= $fetch_products['name']; ?></td>
                        <td>Rp <?= number_format($fetch_products['price'], 0, ',', '.'); ?></td>
                        <td><?= $fetch_products['details']; ?></td>
                        <td><?= $fetch_products['stock']; ?></td>
                        <td>
                           <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Perbarui</a>
                           <a href="produk.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
                        </td>
                     </tr>
               <?php
                  }
               } else {
                  echo '<tr><td colspan="6" class="empty">Belum ada produk yang ditambahkan!</td></tr>';
               }
               ?>
            </tbody>
         </table>
      </div>
   </section>

   <script src="../js/admin_script.js"></script>

</body>

</html>
