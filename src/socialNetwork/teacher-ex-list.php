<?php 
  session_start();

  if(isset($_SESSION['admin'])) {
    include 'comments-list-admin.php';
  } else {
    include 'comments-list-no-admin.php';
  }