<?php 
ob_start();
session_start();
if( !isset($_SESSION["user_id"])){
 header("Location: ./login.php");
}
include(__DIR__ . '/header.php');
include(__DIR__ . '/../app/Blog.php');
use app\Blog;

$blog=new Blog;

if($_POST && isset($_FILES["image"])){
  $_POST['image']=$_FILES["image"];
  $content=isset($_POST['content'])?$_POST['content']:null;
  $image=isset($_POST['image'])?$_POST['image']:null;
  $title=isset($_POST['title'])?$_POST['title']:null;
  $blog->store( $_POST);
}
if(isset($_SESSION['errors'])){
    $err_content=isset($_SESSION['errors']['content'])?$_SESSION['errors']['content']:null;
    $err_image=isset($_SESSION['errors']['image'])?$_SESSION['errors']['image']:null;
    $err_title=isset($_SESSION['errors']['title'])?$_SESSION['errors']['title']:null;
    $_SESSION['errors']=[];
}


ob_end_flush();
?>
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="card">
        <h2 class=" mx-auto mt-3">Create Blog</h2>
      <form class="card-body" action="" enctype="multipart/form-data" method="POST">
        <div class="form-group">
          <label for="exampleInputEmail1">Title</label>
          <input type="text" name="title" value="<?= isset($title)?$title:''?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter title">
           
          <?php if( isset($err_title)){?>
            <div class="bg-danger mt-2 pl-2" role="alert">
                <?= $err_title?>
            </div>
          <?php }?>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Content</label>
          <textarea name="content" class="form-control" id="exampleFormControlTextarea1" rows="3">
            <?php echo isset($content)?$content:""; ?>
          </textarea>
          <?php if( isset($err_content)){?>
            <div class="bg-danger mt-2 pl-2" role="alert">
                <?= $err_content?>
            </div>
          <?php }?>
        </div>
        <div class="form-group">
          <label for="exampleFormControlFile1">Image</label>
          <input type="file" name="image" class="form-control-file" id="exampleFormControlFile1">
          <?php if( isset($err_image)){?>
            <div class="bg-danger mt-2 pl-2" role="alert">
                <?= $err_image?>
            </div>
          <?php }?>
        </div>
        <input type="submit" name="submit" value="create" class="btn btn-primary">
      </form>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<?php include(__DIR__ . '/footer.php');?>