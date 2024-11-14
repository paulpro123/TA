<?php

include '../koneksi.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
   exit();
}

if (isset($_POST['order'])) {

   $name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : '';
   $number = isset($_POST['number']) ? filter_var($_POST['number'], FILTER_SANITIZE_STRING) : '';
   $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_STRING) : '';
   $method = isset($_POST['method']) ? filter_var($_POST['method'], FILTER_SANITIZE_STRING) : '';
   $addres = 'flat no. ' . (isset($_POST['flat']) ? $_POST['flat'] : '') . ', ' . 
              (isset($_POST['street']) ? $_POST['street'] : '') . ', ' . 
              (isset($_POST['city']) ? $_POST['city'] : '') . ', ' . 
              (isset($_POST['state']) ? $_POST['state'] : '') . ', ' . 
              (isset($_POST['country']) ? $_POST['country'] : '') . ' - ' . 
              (isset($_POST['pin_code']) ? $_POST['pin_code'] : '');

   $addres = filter_var($addres, FILTER_SANITIZE_STRING);
   $total_products = isset($_POST['total_products']) ? $_POST['total_products'] : '';
   $total_price = isset($_POST['total_price']) ? $_POST['total_price'] : '';

   $check_cart = $koneksi->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {

      $insert_order = $koneksi->prepare("INSERT INTO `orders`(user_id, name, number, email, method, addres, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $addres, $total_products, $total_price]);

      $delete_cart = $koneksi->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'pesanan berhasil ditempatkan!';
   } else {
      $message[] = 'keranjang Anda kosong';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">
</head>

<body>

   <?php include 'user_header.php'; ?>

   <section class="checkout-orders">

      <form action="" method="POST">

         <h3>pesanan Anda</h3>

         <div class="display-orders">
            <?php
            $grand_total = 0;
            $cart_items = [];
            $select_cart = $koneksi->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
               while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                  // Pengecekan isset langsung di dalam string interpolasi
                  $cart_items[] = (isset($fetch_cart['name']) ? $fetch_cart['name'] : 'Tidak ada nama produk') . ' (' . 
                                  (isset($fetch_cart['price']) ? $fetch_cart['price'] : 0) . ' x ' . 
                                  (isset($fetch_cart['quantity']) ? $fetch_cart['quantity'] : 0) . ') - ';
                  $total_products = implode($cart_items);
                  $grand_total += ((isset($fetch_cart['price']) ? $fetch_cart['price'] : 0) * 
                                  (isset($fetch_cart['quantity']) ? $fetch_cart['quantity'] : 0));
                  ?>
                  <p> <?= isset($fetch_cart['name']) ? $fetch_cart['name'] : 'Tidak ada nama produk'; ?>
                     <span>(<?= 'Rp' . (isset($fetch_cart['price']) ? $fetch_cart['price'] : 0) . '/- x ' . 
                        (isset($fetch_cart['quantity']) ? $fetch_cart['quantity'] : 0); ?>)</span>
                  </p>
                  <?php
               }
            } else {
               echo '<p class="empty">keranjang Anda kosong!</p>';
            }
            ?>
            <input type="hidden" name="total_products" value="<?= isset($total_products) ? $total_products : ''; ?>">
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
            <div class="grand-total">hasil akhir :<span>Rp<?= $grand_total; ?>/-</span></div>
         </div>

         <h3>tempatkan pesanan Anda</h3>

         <div class="flex">
            <!-- Form inputs -->
            <!-- Nama Anda -->
            <div class="inputBox">
               <span>Nama Anda :</span>
               <input type="text" name="name" placeholder="enter your name" class="box" maxlength="20" required>
            </div>

            <!-- Nomor Handphone -->
            <div class="inputBox">
               <span>Nomor handphone anda :</span>
               <input type="number" name="number" placeholder="enter your number" class="box" min="0" max="9999999999"
                  onkeypress="if(this.value.length == 10) return false;" required>
            </div>

            <!-- Email -->
            <div class="inputBox">
               <span>Email Anda :</span>
               <input type="email" name="email" placeholder="enter your email" class="box" maxlength="50" required>
            </div>

            <!-- Metode Pembayaran -->
            <div class="inputBox">
               <span>Metode Pembayaran :</span>
               <select name="method" class="box" required>
                  <option value="cash on delivery">cash on delivery</option>
                  <option value="credit card">credit card</option>
                  <option value="paytm">paytm</option>
                  <option value="paypal">paypal</option>
               </select>
            </div>

            <!-- Alamat dan Kode Pos -->
            <div class="inputBox">
               <span>Alamat 01 :</span>
               <input type="text" name="flat" placeholder="e.g. flat number" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Alamat 02 :</span>
               <input type="text" name="street" placeholder="e.g. street name" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Kota :</span>
               <input type="text" name="city" placeholder="e.g. mumbai" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Status :</span>
               <input type="text" name="state" placeholder="e.g. maharashtra" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Negara :</span>
               <input type="text" name="country" placeholder="e.g. India" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Kode Pos :</span>
               <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" min="0" max="999999"
                  onkeypress="if(this.value.length == 6) return false;" class="box" required>
            </div>
         </div>

         <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="Buat Pesanan">

      </form>

   </section>

   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>

</body>
</html>