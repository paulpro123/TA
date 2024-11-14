<?php

include '../koneksi.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">

</head>

<body>

   <?php include 'user_header.php'; ?>

   <div class="home-bg">

      <section class="home">

         <div class="swiper home-slider">

            <div class="swiper-wrapper">

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="../images/home-img-1.png" alt="">
                  </div>
                  <div class="content">
                     <span>upto 50% off</span>
                     <h3>Smartphones Terbaru</h3>
                     <a href="shop.php" class="btn btn-primary">Beli Sekarang</a>
                  </div>
               </div>

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="../images/home-img-2.png" alt="">
                  </div>
                  <div class="content">
                     <span>diskon hingga 50%</span>
                     <h3>jam tangan terbaru</h3>
                     <a href="shop.php" class="btn">Beli Sekarang</a>
                  </div>
               </div>

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="../images/home-img-3.png" alt="">
                  </div>
                  <div class="content">
                     <span>diskon hingga 50%</span>
                     <h3>Headsets Terbaru</h3>
                     <a href="shop.php" class="btn">Beli Sekarang</a>
                  </div>
               </div>

            </div>

            <div class="swiper-pagination"></div>

         </div>

      </section>

   </div>

   </div>

   <div class="swiper-pagination"></div>

   </div>

   </section>

  <!-- Struktur produk-swiper -->
<section class="home-products">
   <h1 class="heading">Produk Terbaru</h1>
   <div class="">
      <div class="" style="display: flex; ">
         <?php
         $select_products = $koneksi->prepare("SELECT * FROM products");
         $select_products->execute();
         if ($select_products->rowCount() > 0) {
            while ($fetch_product = $select_products->fetch()) {
         ?>
               <form action="keranjang.php" method="post" class="swiper-slide slide" style="width:50%; margin-right: 20px;">
                  <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                  <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                  <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                  <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                  <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                  <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
                  <img src="../uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                  <div class="name"><?= $fetch_product['name']; ?></div>
                  <div class="stock"><h1>stock : <?= $fetch_product['stock']; ?></h1></div>
                  <div class="flex">
                     <div class="price"><span>Rp</span><?= $fetch_product['price']; ?><span></span></div>
                     <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1  ">
                  </div>
                  <input type="submit" value="Tambahkan Ke keranjang" class="btn" name="add_to_cart">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">belum ada produk yang ditambahkan!</p>';
         }
         ?>
      </div>
   </div>
</section>



   <?php include 'footer.php'; ?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <script src="js/script.js"></script>


</body>

</html>