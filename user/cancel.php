<?php
include('../include/header.php');
$id=$_GET['id']??null;
$user_id=$_SESSION['id'];
$stmt=$pdo->prepare('UPDATE `clinic_slots` SET  status=1,user_id=null  where user_id=? and id=? ');
$stmt->execute([$user_id,$id]);
header("Location:dashboard.php");
exit;