<?php

include '../koneksi.php'; // Pastikan path ini benar

// Periksa apakah user_id sudah diset dalam session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
    exit;
}

// Menambahkan ke wishlist
if (isset($_POST['order'])) {
    if (empty($user_id)) {
        header('location:user_login.php');
        exit;
    } else {
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
}

// Menambahkan ke cart
if (isset($_POST['add_to_cart'])) {
    if (empty($user_id)) {
        header('location:user_login.php');
        exit;
    } else {
        $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
        $qty = filter_var($_POST['qty'], FILTER_SANITIZE_STRING);

        // Ambil data produk dari tabel `products`
        $get_product = $koneksi->prepare("SELECT * FROM `products` WHERE id = ?");
        $get_product->execute([$pid]);
        $product_data = $get_product->fetch(PDO::FETCH_ASSOC);

        // Pastikan data produk ada
        if ($product_data) {
            $product_name = $product_data['name'];
            $price = $product_data['price'];
            $image = $product_data['image_01']; // Ambil gambar produk dari database

            // Cek apakah user_id ada di tabel users
            $check_user = $koneksi->prepare("SELECT id FROM `users` WHERE id = ?");
            $check_user->execute([$user_id]);

            if ($check_user->rowCount() > 0) {
                // Cek apakah produk sudah ada di cart
                $check_cart = $koneksi->prepare("SELECT * FROM `cart` WHERE product_name = ? AND user_id = ?");
                $check_cart->execute([$product_name, $user_id]);

                if ($check_cart->rowCount() > 0) {
                    $message[] = 'sudah ditambahkan ke cart!';
                } else {
                    // Hapus dari wishlist jika produk ada di sana
                    $check_wishlist = $koneksi->prepare("SELECT * FROM `wishlist` WHERE product_name = ? AND user_id = ?");
                    $check_wishlist->execute([$product_name, $user_id]);

                    if ($check_wishlist->rowCount() > 0) {
                        $delete_wishlist = $koneksi->prepare("DELETE FROM `wishlist` WHERE product_name = ? AND user_id = ?");
                        $delete_wishlist->execute([$product_name, $user_id]);
                    }

                    // Tambahkan ke cart
                    if ($image) { // Pastikan image tidak null sebelum insert
                        $insert_cart = $koneksi->prepare("INSERT INTO `cart` (user_id, product_id, product_name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
                        $insert_cart->execute([$user_id, $pid, $product_name, $price, $qty, $image]);
                        $message[] = 'ditambahkan ke cart!';
                    } else {
                        $message[] = 'Gambar produk tidak ditemukan!';
                    }
                }
            } else {
                $message[] = 'User tidak ditemukan!';
            }
        } else {
            $message[] = 'Produk tidak ditemukan!';
        }
    }
}
?>