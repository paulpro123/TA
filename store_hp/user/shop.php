<?php

include_once '../koneksi.php'; // Menggunakan include_once untuk memastikan satu kali pemanggilan

session_start();

// Memeriksa session user_id
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

include_once 'wishlist_cart.php'; // Menggunakan include_once untuk menghindari duplikasi

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>
   
<?php include_once 'user_header.php'; ?> <!-- Pastikan hanya sekali include -->

<section class="products">

   <h1 class="heading">Produk Terbaru</h1>

   <div class="box-container">

   <?php
     // Query untuk mengambil data produk
     $select_products = $koneksi->prepare("SELECT * FROM `products`"); 
     $select_products->execute();

     // Debug untuk melihat jumlah data yang diambil
     echo "<!-- Jumlah Produk Ditemukan: " . $select_products->rowCount() . " -->"; 

     if($select_products->rowCount() > 0){
        // Loop untuk menampilkan setiap produk
        while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_product['id']); ?>">
      <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_product['name']); ?>">
      <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_product['price']); ?>">
      <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_product['image_01']); ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_product['id']); ?>" class="fas fa-eye"></a>
      <img src="../uploaded_img/<?= htmlspecialchars($fetch_product['image_01']); ?>" alt="">
      <div class="name"><?= htmlspecialchars($fetch_product['name']); ?></div>
      <div class="stock"><h1>stock : <?= $fetch_product['stock']; ?></h1></div>
      <div class="flex">
         <div class="price"><span>Rp</span><?= htmlspecialchars($fetch_product['price']); ?><span></span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="Masukkan ke keranjang" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   } else {
      echo '<p class="empty">Tidak ada produk yang ditemukan!</p>';
   }
   ?>

   </div>

</section>

<?php include_once 'footer.php'; ?> <!-- Pastikan hanya sekali include -->

<script src="js/script.js"></script>

</body>
</html>
