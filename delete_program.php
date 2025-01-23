<?php
	include 'db/db_config.php';
	$id = $_GET['id'];
	if($db->delete('program')->where('id_calon_kr='.$id)->count() == 1){
		header('location:tampil_program.php');
	} else {
		header('location:tampil_program.php?error_msg=error_delete');
	}
?>