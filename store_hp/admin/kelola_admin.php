<?php
include '../koneksi.php';

session_start();

// Pastikan admin_id sudah ada dalam sesi
if (isset($_SESSION['admin_id'])) {
   $admin_id = $_SESSION['admin_id'];
} else {
   // Jika belum login, arahkan ke halaman login
   header('Location: admin_login.php');
   exit();
}

// Menghapus akun admin
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_admins = $koneksi->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('Location: kelola_admin.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      /* Kontainer Tabel */
      .table-container {
         width: 100%;
         overflow-x: auto;
         margin-top: 20px;
         text-align: left;
         /* Agar tabel berada di kiri */
      }

      /* Tabel dan Border */
      table {
         width: 100%;
         max-width: 900px;
         margin: 0;
         /* Menghapus margin auto untuk menempatkan tabel di kiri */
         border-collapse: collapse;
         border-radius: 8px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      th,
      td {
         padding: 12px 16px;
         text-align: left;
         font-size: 14px;
         border-bottom: 1px solid #ddd;
      }

      th {
         background-color: #f8f8f8;
         font-weight: bold;
      }

      tr:nth-child(even) {
         background-color: #f9f9f9;
      }

      tr:hover {
         background-color: #f1f1f1;
      }

      .empty {
         text-align: center;
         padding: 20px;
         color: red;
         font-size: 16px;
      }

      .delete-btn,
      .option-btn {
         display: block;
         /* Agar tombol memanjang ke atas */
         width: 100%;
         padding: 8px;
         font-size: 14px;
         border: none;
         cursor: pointer;
         border-radius: 4px;
         text-align: center;
      }

      .delete-btn {
         background-color: #f44336;
         color: white;
      }

      .delete-btn:hover {
         background-color: #d32f2f;
      }

      .option-btn {
         background-color: #4CAF50;
         color: white;
      }

      .option-btn:hover {
         background-color: #388e3c;
      }

      /* Responsiveness */
      @media (max-width: 768px) {

         th,
         td {
            font-size: 12px;
            padding: 10px;
         }
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
   </style>
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="show-products">
      <h1 class="heading">Akun Admin</h1>

      <!-- Box untuk menambah admin -->
      <a href="register_admin.php" class="add-product-btn"><i class="fas fa-plus"></i> Tambah Admin</a>
      </div>

      <!-- Tabel Akun Admin -->
      <div class="table-container">
         <table>
            <thead>
               <tr>
                  <th>Admin ID</th>
                  <th>Nama Admin</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               // Query untuk menampilkan daftar akun admin
               $select_accounts = $koneksi->prepare("SELECT * FROM `admins`");
               $select_accounts->execute();
               if ($select_accounts->rowCount() > 0) {
                  while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
                     ?>
                     <tr>
                        <td><?= $fetch_accounts['id']; ?></td>
                        <td><?= htmlspecialchars($fetch_accounts['name']); ?></td>
                        <td>
                           <div class="flex-btn">
                              <a href="kelola_admin.php?delete=<?= $fetch_accounts['id']; ?>"
                                 onclick="return confirm('Hapus akun ini?')" class="delete-btn">Hapus</a>
                              <?php
                              if ($fetch_accounts['id'] == $admin_id) {
                                 echo '<a href="update_profile.php" class="option-btn">Update</a>';
                              }
                              ?>
                           </div>
                        </td>
                     </tr>
                     <?php
                  }
               } else {
                  echo '<tr><td colspan="3" class="empty">Tidak ada akun yang tersedia!</td></tr>';
               }
               ?>
            </tbody>
         </table>
      </div>
   </section>

   <script src="../js/admin_script.js"></script>

</body>

</html>