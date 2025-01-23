<?php
	include 'db/db_config.php';
	extract($_POST);
	$pass = ($password);
	
	// Query untuk memilih data berdasarkan username dan password
	$sql = $db->select('*', 'admin')->where("username='$username' and password='$pass'");
	$check = $sql->count();
	
	if ($check == 1) {
		foreach ($sql->get() as $data) {
			$id_user = $data['id'];
			$role = $data['role']; // Ambil role pengguna dari database
		}
		
		session_start();
		$_SESSION['id'] = $id_user;
		$_SESSION['nama'] = $username;
		$_SESSION['role'] = $role; // Simpan role pengguna di sesi
		
		// Redirect berdasarkan role pengguna
		if ($role == 'pimpinan') {
			header('location:index.php'); // Ganti dengan halaman pimpinan
		} else if ($role == 'admin') {
			header('location:index.php'); // Ganti dengan halaman admin
		} else {
			header('location:login.php'); // Redirect ke login jika role tidak dikenali
		}
	} else {
		header('location:login.php'); // Redirect ke login jika username/password salah
	}
?>
