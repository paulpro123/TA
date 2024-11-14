<?php

include '../koneksi.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>pesanan</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">
   <style>
      /* Tabel Pesanan */
.orders-table {
   width: 100%;
   border-collapse: collapse;
   margin-top: 20px;
   background: #fff;
   box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.orders-table th, .orders-table td {
   padding: 12px 15px;
   text-align: left;
   border-bottom: 1px solid #ddd;
   font-size: 14px;
}

.orders-table th {
   background-color: #f4f4f4;
   color: #333;
   font-weight: 600;
}

.orders-table td {
   background-color: #fafafa;
}

.orders-table tr:hover {
   background-color: #f1f1f1;
}

.orders-table td span {
   font-weight: bold;
}

/* Styling untuk status pembayaran */
.orders-table td {
   color: #333;
}

.orders-table td:nth-child(9) {
   font-weight: bold;
   color: green;
}

.orders-table td:nth-child(9)[style*="color:red"] {
   color: red;
}

/* Responsive Design */
@media (max-width: 768px) {
   .orders-table th, .orders-table td {
      font-size: 12px;
      padding: 10px;
   }
   
   .orders-table {
      font-size: 12px;
   }
}

   </style>

</head>
<body>
   
<?php include 'user_header.php'; ?>

<section class="orders">
   <h1 class="heading">Pesanan Anda</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">Silakan login untuk melihat pesanan Anda.</p>';
      } else {
         $select_orders = $koneksi->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
   ?>
   
   <table class="orders-table">
      <thead>
         <tr>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Nomor Handphone</th>
            <th>Alamat</th>
            <th>Metode Pembayaran</th>
            <th>Pesanan</th>
            <th>Total Harga</th>
            <th>Status Pembayaran</th>
         </tr>
      </thead>
      <tbody>
         <?php
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
         ?>
         <tr>
            <td><?= $fetch_orders['placed_on']; ?></td>
            <td><?= $fetch_orders['name']; ?></td>
            <td><?= $fetch_orders['email']; ?></td>
            <td><?= $fetch_orders['number']; ?></td>
            <td><?= $fetch_orders['addres']; ?></td>
            <td><?= $fetch_orders['method']; ?></td>
            <td><?= $fetch_orders['total_products']; ?></td>
            <td>Rp<?= $fetch_orders['total_price']; ?>/-</td>
            <td style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></td>
         </tr>
         <?php
            }
         ?>
      </tbody>
   </table>
   
   <?php
         } else {
            echo '<p class="empty">Belum ada pesanan!</p>';
         }
      }
   ?>

   </div>
</section>














<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>