<?php
	include 'db/db_config.php';
	extract($_POST);
			$db->update('program',"nama='$nama',penyerapan='$penyerapan',efisiensi='$efisiensi',
									teknologi='$teknologi',realisasianggaran='$realisasianggaran',
									infrastruktur='$infrastruktur',anggaran='$anggaran',efektifitas='$efektifitas'")->where("id_calon_kr='$id_calon_kr'")->count();
			header('location:tampil_program.php');		
?>