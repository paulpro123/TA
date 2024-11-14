<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <style>
      .sidebar {
         position: fixed;
         top: 0;
         left: 0;
         height: 100vh;
         width: 250px;
         background-color: var(--black);
         display: flex;
         flex-direction: column;
         align-items: center;
         padding-top: 2rem;
         box-shadow: var(--box-shadow);
         z-index: 1000;
      }

      .sidebar .logo {
         font-size: 2.5rem;
         color: var(--white);
         font-weight: 600;
         text-align: center;
         margin-bottom: 2rem;
      }

      .sidebar .logo span {
         color: var(--main-color);
      }

      .sidebar .navbar {
         display: flex;
         flex-direction: column;
         align-items: center;
         gap: 1rem;
         margin-bottom: 2rem;
      }

      .sidebar .navbar a {
         font-size: 1.8rem;
         color: var(--white);
         padding: 0.8rem 1.5rem;
         width: 100%;
         text-align: center;
         transition: background-color 0.2s;
         border-radius: 0.5rem;
      }

      .sidebar .navbar a:hover {
         background-color: var(--main-color);
      }

      .sidebar .profile {
         margin-top: auto;
         padding: 2rem;
         text-align: center;
         background-color: var(--light-bg);
         color: var(--black);
         border-radius: 0.5rem;
         box-shadow: var(--box-shadow);
         width: 90%;
      }

      .sidebar .profile p {
         font-size: 1.8rem;
         font-weight: 500;
      }

      .sidebar .profile .btn,
      .sidebar .profile .option-btn,
      .sidebar .profile .delete-btn {
         width: 100%;
         font-size: 1.5rem;
         margin-top: 1rem;
      }

      .sidebar .profile .flex-btn {
         display: flex;
         gap: 1rem;
         margin-top: 1rem;
      }

      .sidebar .profile .option-btn:hover {
         background-color: var(--orange);
      }

      .sidebar .profile .delete-btn:hover {
         background-color: var(--red);
      }

      body {
         margin-left: 250px;
         /* To prevent content from hiding behind sidebar */
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

   <div class="sidebar">
      <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="../admin/dashboard.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
               fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
               <path
                  d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5" />
            </svg> dashboard</a>
         <a href="../admin/daftar_product.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
               fill="currentColor" class="bi bi-box-fill" viewBox="0 0 16 16">
               <path fill-rule="evenodd"
                  d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />
            </svg> kelola produk</a>
         <a href="../admin/kelola_pesanan.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
               fill="currentColor" class="bi bi-bag-fill" viewBox="0 0 16 16">
               <path
                  d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4z" />
            </svg> kelola pesanan</a>
         <a href="../admin/kelola_admin.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
               fill="currentColor" class="bi bi-person-check-fill" viewBox="0 0 16 16">
               <path fill-rule="evenodd"
                  d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0" />
               <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
            </svg> kelola admin</a>
         <a href="../admin/kelola_user.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
               fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
               <path
                  d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
            </svg> kelola user</a>
         <a href="../admin/pesan.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
               class="bi bi-chat-right-text-fill" viewBox="0 0 16 16">
               <path
                  d="M16 2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h9.586a1 1 0 0 1 .707.293l2.853 2.853a.5.5 0 0 0 .854-.353zM3.5 3h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1m0 2.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1m0 2.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1" />
            </svg> pesan</a>
         <a href="admin_logout.php" class="delete-btn" onclick="return confirm('Keluar dari situs web?');">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
               class="bi bi-door-closed-fill" viewBox="0 0 16 16">
               <path
                  d="M12 1a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2a1 1 0 0 1 1-1zm-2 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
            </svg>
            Logout
         </a>

      </nav>

      <div class="profile">
         <?php
         $select_profile = $koneksi->prepare("SELECT * FROM `admins` WHERE id = ?");
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

         if ($fetch_profile) {
            echo "<p>{$fetch_profile['name']}</p>";
         } else {
            echo "<p>Profil tidak ditemukan</p>";
         }
         ?>
         <a href="update_profile.php" class="btn">Update Profile</a>
      </div>

   </div>
   </section>

   </header>
</body>

</html>