<?php
session_start();
include '../koneksi.php';

// Pastikan admin_id sudah ada dalam sesi
if (isset($_SESSION['admin_id'])) {
   $admin_id = $_SESSION['admin_id'];
} else {
   // Jika belum login, arahkan ke halaman login
   header('Location: login.php');
   exit();
}

// Proses update status pembayaran
if (isset($_POST['update_payment'])) {
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   
   $update_payment = $koneksi->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);

   header('Location: kelola_pesanan.php');
   exit();
}

// Proses hapus pesanan
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   
   $delete_order = $koneksi->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);

   header('Location: kelola_pesanan.php'); // Refresh halaman setelah penghapusan
   exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Daftar Pesanan</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      th,
      td {
         padding: 12px 16px;
         text-align: left;
         font-size: 14px;
         border: 1px solid #ddd;
      }

      th {
         background-color: #f8f8f8;
         font-weight: bold;
      }

      tr:nth-child(even) {
         background-color: #f9f9f9;
      }

      .empty {
         text-align: center;
         padding: 20px;
         color: red;
         font-size: 16px;
      }

      .delete-btn,
      .option-btn {
         width: 100%;
         padding: 10px;
         text-align: center;
         font-size: 14px;
         border: none;
         cursor: pointer;
         border-radius: 4px;
         transition: background-color 0.3s ease;
         color: white;
      }

      .delete-btn {
         background-color: #f44336;
      }

      .delete-btn:hover {
         background-color: #d32f2f;
      }

      .option-btn {
         background-color: #4CAF50;
      }

      .option-btn:hover {
         background-color: #388e3c;
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

      .add-product-btn:hover {
         background-color: #0056b3;
      }
   </style>
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="show-products">
      <h1 class="heading">Daftar Pesanan</h1>

      <div class="table-container">
         <table>
            <thead>
               <tr>
                  <th>Nama</th>
                  <th>No HP</th>
                  <th>Alamat</th>
                  <th>Total Produk</th>
                  <th>Total Pembayaran</th>
                  <th>Metode Pembayaran</th>
                  <th>Status Pembayaran</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $select_orders = $koneksi->prepare("SELECT * FROM `orders`");
               $select_orders->execute();
               if ($select_orders->rowCount() > 0) {
                  while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                     ?>
                     <tr>
                        <form action="" method="post">
                           <td><?= $fetch_orders['name']; ?></td>
                           <td><?= $fetch_orders['number']; ?></td>
                           <td><?= $fetch_orders['addres']; ?></td>
                           <td><?= $fetch_orders['total_products']; ?></td>
                           <td>Rp <?= number_format($fetch_orders['total_price'], 0, ',', '.'); ?></td>
                           <td><?= $fetch_orders['method']; ?></td>
                           <td>
                              <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                              <select name="payment_status" class="select">
                                 <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
                                 <option value="pending">Tertunda</option>
                                 <option value="completed">Selesai</option>
                              </select>
                           </td>
                           <td>
                              <div class="flex-btn">
                                 <input type="submit" value="Perbarui" class="option-btn" name="update_payment">
                                 <a href="kelola_pesanan.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn"
                                    onclick="return confirm('Hapus pesanan ini?');">Hapus</a>
                              </div>
                           </td>
                        </form>
                     </tr>
                     <?php
                  }
               } else {
                  echo '<tr><td colspan="8" class="empty">Belum ada pesanan!</td></tr>';
               }
               ?>
            </tbody>
         </table>
      </div>
   </section>

   <script src="../js/admin_script.js"></script>
</body>

</html>