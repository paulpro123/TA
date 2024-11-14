<?php
include '../koneksi.php'; // Ensure the path to koneksi.php is correct

session_start();

if (!isset($_SESSION['user_id'])) {
   header('location:user_login.php');
   exit;
}

$user_id = $_SESSION['user_id'];
if (isset($_POST['add_to_cart'])) {
   $product_id = $_POST['pid'];
   $name = $_POST['name'];
   $price = $_POST['price'];
   $image_1 = $_POST['image'];
   $qty = $_POST['qty'];

   $insert_products = $koneksi->prepare("INSERT INTO `cart`(user_id, product_id, quantity, image, price, product_name) VALUES(?,?,?,?,?,?)");
   $insert_products->execute([$user_id, $product_id,$qty, $image_1, $price, $name]);
}
if (isset($_POST['delete'])) {
   $cart_id = filter_var($_POST['cart_id'], FILTER_SANITIZE_NUMBER_INT);
   $delete_cart_item = $koneksi->prepare("DELETE FROM cart WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}
if (isset($_GET['delete_all'])) {
   $delete_cart_item = $koneksi->prepare("DELETE FROM cart WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
   exit;
}
if (isset($_POST['update_qty'])) {
   $cart_id = filter_var($_POST['cart_id'], FILTER_SANITIZE_NUMBER_INT);
   $qty = filter_var($_POST['qty'], FILTER_SANITIZE_NUMBER_INT);
   $update_qty = $koneksi->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'Jumlah keranjang diperbarui';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Keranjang Shopping</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="../css/style.css">
</head>

<body>

   <?php include 'user_header.php'; ?>

   <section class="products shopping-cart">
      <h3 class="heading">Keranjang Shopping</h3>
      <div class="box-container">

         <?php
         $grand_total = 0;
         // Query to retrieve product details from the products table based on product_id in the cart
         try {
            $select_cart = $koneksi->prepare("
                SELECT cart.*, products.image_01, products.price AS product_price, products.name
                FROM cart
                JOIN products ON cart.product_id = products.id
                WHERE cart.user_id = ?
            ");
            $select_cart->execute([$user_id]);

            if ($select_cart->rowCount() > 0) {
               while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                  $sub_total = $fetch_cart['product_price'] * $fetch_cart['quantity'];
         ?>
                  <form action="" method="post" class="box">
                     <input type="hidden" name="cart_id" value="<?= htmlspecialchars($fetch_cart['id']); ?>">
                     <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_cart['product_id']); ?>" class="fas fa-eye"></a>
                     <img src="../uploaded_img/<?= htmlspecialchars($fetch_cart['image_01']); ?>" alt="image">
                     <div class="name"><?= htmlspecialchars($fetch_cart['product_name']); ?></div>
                     <div class="flex">
                        <div class="price">Rp <?= htmlspecialchars($fetch_cart['product_price']); ?></div>
                        <input type="number" name="qty" class="qty" min="1" max="99"
                           value="<?= htmlspecialchars($fetch_cart['quantity']); ?>">
                        <button type="submit" class="fas fa-edit" name="update_qty"></button>
                     </div>
                     <div class="sub-total"> Sub total: <span>Rp <?= htmlspecialchars($sub_total); ?></span> </div>
                     <input type="submit" value="Hapus item" onclick="return confirm('Hapus ini dari keranjang?');"
                        class="delete-btn" name="delete">
                  </form>
         <?php
                  $grand_total += $sub_total;
               }
            } else {
               echo '<p class="empty">Keranjang Anda kosong</p>';
            }
         } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
         }
         ?>
      </div>

      <div class="cart-total">
         <p>Hasil akhir: <span>Rp <?= htmlspecialchars($grand_total); ?></span></p>
         <a href="shop.php" class="option-btn">Lanjutkan Belanja</a>
         <a href="keranjang.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>"
            onclick="return confirm('Hapus semua dari keranjang?');">Hapus semua item</a>
         <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Lanjutkan ke Pembayaran</a>
      </div>

   </section>

   <?php include __DIR__ . '/../user/footer.php'; // Ensure the path is correct 
   ?>

   <script src="js/script.js"></script>

</body>

</html>