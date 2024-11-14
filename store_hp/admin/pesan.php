<?php

include '../koneksi.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_message = $koneksi->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:pesan.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesan</title>

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
         min-width: 800px; /* Adjust minimum width to allow horizontal scrolling */
         background-color: #fff;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         border-radius: 8px;
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
      <h1 class="heading">Pesan</h1>

      <div class="table-container">
         <table>
            <thead>
               <tr>
                  <th>User ID</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>No HP</th>
                  <th>Pesan</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $select_messages = $koneksi->prepare("SELECT * FROM `messages`");
               $select_messages->execute();
               if ($select_messages->rowCount() > 0) {
                  while ($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <td><?= $fetch_message['user_id']; ?></td>
                        <td><?= $fetch_message['name']; ?></td>
                        <td><?= $fetch_message['email']; ?></td>
                        <td><?= $fetch_message['number']; ?></td>
                        <td><?= $fetch_message['message']; ?></td>
                        <td><a href="pesan.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('Hapus pesan ini?');" class="delete-btn">Hapus</a></td>
                     </tr>
               <?php
                  }
               } else {
                  echo '<tr><td colspan="6" style="text-align: center;">Kamu tidak punya pesan</td></tr>';
               }
               ?>
            </tbody>
         </table>
      </div>
   </section>

   <script src="../js/admin_script.js"></script>
</body>

</html>