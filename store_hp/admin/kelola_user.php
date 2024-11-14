<?php

include '../koneksi.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_user = $koneksi->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_user->execute([$delete_id]);
   $delete_orders = $koneksi->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_orders->execute([$delete_id]);
   $delete_messages = $koneksi->prepare("DELETE FROM `messages` WHERE user_id = ?");
   $delete_messages->execute([$delete_id]);
   $delete_cart = $koneksi->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $koneksi->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:kelola_user.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      /* Container with horizontal scrolling */
      .table-container {
         width: 100%;
         overflow-x: auto;
         margin-top: 20px;
      }

      /* Table styling */
      table {
         width: 100%;
         border-collapse: collapse;
         min-width: 700px; /* Set a minimum width to allow scrolling */
         background-color: #fff;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         border-radius: 8px;
         margin: 0 auto;
      }

      th, td {
         padding: 15px;
         text-align: left;
         border: 1px solid #ddd;
         font-size: 14px;
      }

      th {
         background-color: #f8f8f8;
         font-weight: bold;
         text-align: center;
      }

      /* Styling for delete button */
      .delete-btn {
         display: inline-block;
         padding: 8px 12px;
         color: #fff;
         background-color: #f44336;
         text-align: center;
         border-radius: 4px;
         text-decoration: none;
         font-weight: bold;
         transition: background-color 0.3s ease;
      }

      .delete-btn:hover {
         background-color: #d32f2f;
      }

      /* Responsive adjustments */
      @media (max-width: 768px) {
         th, td {
            font-size: 12px;
            padding: 10px;
         }
      }
   </style>
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="show-products">
      <h1 class="heading">User Accounts</h1>

      <div class="table-container">
         <table>
            <thead>
               <tr>
                  <th>User ID</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $select_accounts = $koneksi->prepare("SELECT * FROM `users`");
               $select_accounts->execute();
               if ($select_accounts->rowCount() > 0) {
                  while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <td><?= $fetch_accounts['id']; ?></td>
                        <td><?= $fetch_accounts['name']; ?></td>
                        <td><?= $fetch_accounts['email']; ?></td>
                        <td><a href="kelola_user.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Hapus akun ini? Data terkait user ini juga akan dihapus!')" class="delete-btn">Delete</a></td>
                     </tr>
               <?php
                  }
               } else {
                  echo '<tr><td colspan="4" style="text-align: center;">Tidak ada akun yang tersedia!</td></tr>';
               }
               ?>
            </tbody>
         </table>
      </div>
   </section>

   <script src="../js/admin_script.js"></script>

</body>

</html>
