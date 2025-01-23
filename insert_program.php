<?php
	include 'db/db_config.php';
	extract($_POST);

			$db->insert('program',"'','$no','$nama','$anggaran','$realisasianggaran','$efektifitas','$penyerapan','$teknologi',
			'$infrastruktur','$efisiensi'")->count();
			header('location:tampil_program.php');
		//} else {
		//	header('location:input_karyawan.php?error_msg=error_upload');
		//} 
?>