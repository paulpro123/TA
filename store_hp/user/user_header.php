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
<style>
   /* Logo Container */
   .logo {
      display: flex;
      align-items: center;
      font-family: Arial, sans-serif;
      font-size: 1.5rem;
      color: #333;
      text-decoration: none;
   }

   .logo-icon {
      width: 30px;
      height: 30px;
      margin-right: 8px;
   }

   /* Style untuk badge angka di atas ikon */
   .badge {
      position: absolute;
      top: -15px; /* Mengatur jarak badge dari atas ikon */
      right: -15px; /* Mengatur jarak badge dari kanan */
      background-color: #ff3b3b;
      color: white;
      font-size: 12px;
      font-weight: bold;
      border-radius: 50%;
      padding: 2px 6px;
      min-width: 18px;
      text-align: center;
      line-height: 18px; /* Menjaga badge tetap bulat dan sejajar */
   }

   .icons{
      display: flex;
      flex-direction: row;
   }

   /* Kontainer untuk ikon dan badge */
   .icon-container {
      position: relative;
      display: inline-block; /* Agar ikon dan badge sejajar di satu baris */
   }

   /* Menjaga ikon agar tetap sejajar */
   .icons a {
      display: flex;
      align-items: center;
      justify-content: center;
   }

   /* Ubah warna ikon wishlist dan keranjang menjadi hitam */
   .icons .fas.fa-heart,
   .icons .fas.fa-shopping-cart {
      color: black; /* Mengubah warna ikon menjadi hitam */
   }

   /* Styling untuk pencarian yang ada di sebelah logo dan profil */
   .search-container {
      display: flex;
      align-items: center;
      margin-left: 20px;
   }

   .search-container a {
      color: black; /* Warna ikon pencarian */
      font-size: 1.5rem;
   }
   .search-container i {
    font-size: 2rem; /* Ukuran ikon yang lebih besar, misalnya 2rem */
}

</style>

<header class="header">
   <section class="flex">
      <a href="index.php" class="logo">
         <img src="../images/Dinda_chaniago.png" alt="Logo" class="logo-icon"><b><i><h3>Wokenteng</h3></i></b>
      </a>

      <nav class="navbar">
         <a href="index.php">beranda</a>
         <a href="about.php">tentang</a>
         <a href="order.php">pesanan</a>
         <a href="shop.php">shop</a>
         <a href="kontak.php">pesan</a>
      </nav>

      <div class="icons">
         <?php
         $count_wishlist_items = $koneksi->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
         $count_wishlist_items->execute([$user_id]);
         $total_wishlist_counts = $count_wishlist_items->rowCount();

         $count_cart_items = $koneksi->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>

         <!-- Pencarian yang berada di sebelah logo dan profil -->
         <div class="search-container">
            <a href="pencarian.php"><i class="fas fa-search"></i></a>
         </div>

         <!-- Keranjang Icon dengan Badge -->
         <div class="icon-container">
            <a href="wishlist.php"><i class="fas fa-heart"></i>
               <?php if ($total_wishlist_counts > 0) { ?>
                  <span class="badge"><?= $total_wishlist_counts; ?></span>
               <?php } ?>
            </a>
         </div>

         <!-- Wishlist Icon dengan Badge -->
         <div class="icon-container">
            <a href="keranjang.php"><i class="fas fa-shopping-cart"></i>
               <?php if ($total_cart_counts > 0) { ?>
                  <span class="badge"><?= $total_cart_counts; ?></span>
               <?php } ?>
            </a>
         </div>

         <div id="user-btn" class="fas fa-user" onclick="toggleDropdown()"></div>
      </div>

      <div class="profile" id="user-dropdown" style="display: none;">
         <?php
         $select_profile = $koneksi->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <p><?= $fetch_profile["name"]; ?></p>
            <a href="update_user.php" class="btn">update profile</a>
            <div class="flex-btn">
               <a href="user_register.php" class="option-btn">register</a>
               <a href="user_login.php" class="option-btn">login</a>
            </div>
            <a href="user_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a>
            <?php
         } else {
            ?>
            <div class="flex-btn">
               <a href="user_register.php" class="option-btn">register</a>
               <a href="user_login.php" class="option-btn">login</a>
            </div>
            <?php
         }
         ?>
      </div>

   </section>

</header>

<style>
   /* Tambahkan gaya untuk dropdown */
   .profile {
      position: relative;
      /* Mengatur posisi relatif untuk dropdown */
   }

   #user-dropdown {
      position: absolute;
      right: 0;
      top: 100%;
      /* Tempatkan di bawah ikon */
      background: white;
      /* Warna latar belakang */
      border: 1px solid #ccc;
      /* Garis batas */
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      /* Bayangan */
      z-index: 1000;
      /* Pastikan berada di atas konten lain */
   }

   .profile p,
   .profile .btn,
   .profile .option-btn,
   .profile .delete-btn {
      padding: 10px;
      /* Padding untuk setiap item */
      display: block;
      /* Menampilkan item sebagai block */
      text-decoration: none;
      /* Menghilangkan garis bawah */
      color: #333;
      /* Warna teks */
   }

   .profile p {
      margin: 0;
      /* Menghilangkan margin */
   }

   .profile a:hover {
      background-color: #f1f1f1;
      /* Warna latar belakang saat hover */
   }
</style>

<script>
   // Fungsi untuk menampilkan atau menyembunyikan dropdown
   function toggleDropdown() {
      const dropdown = document.getElementById('user-dropdown');
      if (dropdown.style.display === 'none' || dropdown.style.display === '') {
         dropdown.style.display = 'block';
      } else {
         dropdown.style.display = 'none';
      }
   }

   // Menutup dropdown jika klik di luar elemen dropdown
   window.onclick = function (event) {
      if (!event.target.matches('#user-btn')) {
         const dropdown = document.getElementById('user-dropdown');
         if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
         }
      }
   }
</script>
