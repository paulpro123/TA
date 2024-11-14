<?php
session_start();
include '../koneksi.php';

// Pastikan admin_id sudah ada dalam sesi
if (isset($_SESSION['admin_id'])) {
   $admin_id = $_SESSION['admin_id'];
} else {
   // Jika belum login, arahkan ke halaman login
   header('Location: login.php');
   exit();
}
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
   header('location:daftar_product.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Daftar Produk</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      .table-container {
         width: 100%;
         overflow-x: auto;
      }

      table {
         width: 100%;
         border-collapse: collapse;
      }

      th,
      td {
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

      .add-product-btn {
         display: inline-block;
         padding: 10px 20px;
         margin-bottom: 20px;
         color: #fff;
         background-color: #007bff;
         border: none;
         border-radius: 5px;
         text-decoration: none;
         font-weight: bold;
         cursor: pointer;
         transition: background-color 0.3s ease;
      }

      .add-product-btn:hover {
         background-color: #0056b3;
      }
   </style>
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="show-products">
      <h1 class="heading">Daftar Produk</h1>

      <!-- Button Tambah Produk -->
      <a href="upload_product.php" class="add-product-btn"><i class="fas fa-plus"></i> Tambah Produk</a>

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
                        <td><?= htmlspecialchars($fetch_products['name']); ?></td>
                        <td>Rp <?= number_format($fetch_products['price'], 0, ',', '.'); ?></td>
                        <td><?= htmlspecialchars($fetch_products['details']); ?></td>
                        <td><?= htmlspecialchars($fetch_products['stock']); ?></td>
                        <td>
                           <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Perbarui</a>
                           <a href="daftar_product.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
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
</body>

</html>