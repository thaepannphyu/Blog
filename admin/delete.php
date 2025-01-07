<?php
   

include(__DIR__ . '/../app/Blog.php');
use app\Blog;


if($_GET){
  $blog= new Blog;
  $blog->delete( $_GET['id']);
}

?>

