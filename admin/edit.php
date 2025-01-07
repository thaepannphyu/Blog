<?php 
ob_start();
session_start();
if( !isset($_SESSION["user_id"])){
 header("Location: ./login.php");
}
include(__DIR__ . '/header.php');
include(__DIR__ . '/../app/Blog.php');
use app\Blog;


  $blogobj= new Blog;
  $blog= $blogobj->find($_GET["id"]);
  if($_POST ){
    if(isset($_FILES["image"])){ $_POST['image']=$_FILES["image"];}
    $content=isset($_POST['content'])?$_POST['content']:null;
    $image=isset($_POST['image'])?$_POST['image']:null;
    $title=isset($_POST['title'])?$_POST['title']:null;
    $blogobj->update($_POST,$_GET["id"]);
  }
// print_r( $blog);
 ob_end_flush();
?>
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="card">
      <h2 class=" mx-auto mt-3">Edit Blog</h2>
      <form class="card-body" action="" enctype="multipart/form-data" method="POST">
        <div class="form-group">
          <label for="exampleInputEmail1">Title</label>
          <input type="text" name="title" value="<?=$blog["title"]?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter title">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Content</label>
          <textarea name="content" class="form-control" id="exampleFormControlTextarea1" rows="3"><?=$blog["content"]?></textarea>
        </div>
        <div class="form-group">
          <label for="exampleFormControlFile1">Image</label>
          <input type="file" name="image"   class="form-control-file" id="exampleFormControlFile1">
          <img src="../upload/blog/<?=$blog["image"]?>" style="width:50%;margin-top:5px" alt="">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
      </form>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<?php include(__DIR__ . '/footer.php');?>