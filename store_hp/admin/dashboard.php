<?php

include '../koneksi.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="dashboard">

      <h1 class="heading">dashboard</h1>

      <div class="box-container">

      <div class="box">
    <h3>Selamat Datang!</h3>
    <?php if (is_array($fetch_profile)): ?>
        <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
        <a href="update_profile.php" class="btn">Memperbaharui Profil</a>
    <?php else: ?>
        <p>Data profil tidak ditemukan.</p>
    <?php endif; ?>
</div>


         <div class="box">
            <?php
            $total_pendings = 0;
            $select_pendings = $koneksi->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            if ($select_pendings->rowCount() > 0) {
               while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
                  $total_pendings += $fetch_pendings['total_price'];
               }
            }
            ?>
            <h3><span>Rp.</span><?= $total_pendings; ?><span>/-</span></h3>
            <p>total tertunda</p>
            <a href="kelola_pesanan.php" class="btn">lihat pesanan</a>
         </div>

         <div class="box">
            <?php
            $total_completes = 0;
            $select_completes = $koneksi->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
            if ($select_completes->rowCount() > 0) {
               while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
                  $total_completes += $fetch_completes['total_price'];
               }
            }
            ?>
            <h3><span>Rp.</span><?= $total_completes; ?><span>/-</span></h3>
            <p>pesanan selesai</p>
            <a href="kelola_pesanan.php" class="btn">lihat pesanan</a>
         </div>

         <div class="box">
            <?php
            $select_orders = $koneksi->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount()
            ?>
            <h3><?= $number_of_orders; ?></h3>
            <p>pesanan ditempatkan</p>
            <a href="kelola_pesanan.php" class="btn">lihat pesanan</a>
         </div>

         <div class="box">
            <?php
            $select_products = $koneksi->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount()
            ?>
            <h3><?= $number_of_products; ?></h3>
            <p>produk ditambahkan</p>
            <a href="" class="btn">Lihat produk</a>
         </div>

         <div class="box">
            <?php
            $select_users = $koneksi->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount()
            ?>
            <h3><?= $number_of_users; ?></h3>
            <p>normal users</p>
            <a href="kelola_user.php" class="btn">Lihat users</a>
         </div>

         <div class="box">
            <?php
            $select_admins = $koneksi->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount()
            ?>
            <h3><?= $number_of_admins; ?></h3>
            <p>admin users</p>
            <a href="kelola_admin.php" class="btn">Lihat admins</a>
         </div>

         <div class="box">
            <?php
            $select_messages = $koneksi->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()
            ?>
            <h3><?= $number_of_messages; ?></h3>
            <p>pesan baru</p>
            <a href="pesan.php" class="btn">Lihat messages</a>
         </div>

      </div>

   </section>












   <script src="../js/admin_script.js"></script>

</body>

</html>