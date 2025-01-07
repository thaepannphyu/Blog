<?php
namespace app;
include(__DIR__ . '/DB.php');
include(__DIR__ . '/Modal.php');
include(__DIR__ . '/Rules.php');
use app\DB;
use app\Modal;
use app\Rules;


class Blog extends Modal{
    public $table="blog";
    private $sql;
    protected $pdo;
    private $err=[];
    public function __construct(){
        $db=new DB;
        $this->pdo=$db->getConnection();
    }
    public function index()
    {
        $query = $this->getSearchQuery();
        $limit=3;
        // Fetch paginated data
        $data = $this->getPaginatedData($query, $limit);
        return $data;
    }
    public function store($request){
        $data= $this->validate($request);
        if($data){
            $title=$data["title"];
            $content=$data["content"];
            $image=time().$data["image"]['name'];
            $folder=__DIR__."/../upload/blog/".$image;
            if(move_uploaded_file( $data["image"]['tmp_name'],$folder)) {
                $sql = "INSERT INTO $this->table (title,content, image) VALUES (:title, :content, :image)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':content', $content);
                $stmt->bindParam(':image', $image);
                $stmt->execute();
                session_start();
                $_SESSION["alert"]['msg']="Blog is created successfully";
                $_SESSION["alert"]['type']="success";
                ob_start();
                header("Location: ./");
                ob_end_flush();
                exit; 
                } 
        }
    }
    public function update($request,$id){
        $data= $this->validate($request);
        $post=$this->find($id);
        if($data && $post){
            $id=$post["id"];
            $tempfile=time().$data["image"]['name'];
            $title=isset($data["title"]) && !empty($data["title"])?   $data["title"]:  $post["title"];
            $content=isset($data["content"])&& !empty($data["content"])?   $data["content"]:  $post["content"];
            $image=isset($data["image"]['size'])&&$data["image"]['size']>0? $tempfile :$post["image"];
            $sql = "UPDATE $this->table SET title=:title, content=:content, image=:image WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $folder=__DIR__."/../upload/blog/".$image;
            $success=move_uploaded_file( $data["image"]['tmp_name'],$folder);
            if (!$success) {
                $_SESSION["alert"]['msg'] = "There was an error uploading your file.";
                $_SESSION["alert"]['type'] = "error";
            }
            $_SESSION["alert"]['msg']="Blog is updated successfully";
            $_SESSION["alert"]['type']="success";
            
            ob_start();
            header("Location: ./home.php");
            ob_end_flush();
            exit;
        }

        $_SESSION["alert"]['msg'] = "Failed to update the blog post.";
        $_SESSION["alert"]['type'] = "error";
        header("Location: ./home.php");
        exit;
    }
    public function delete($id){
        $post=$this->find($id);
        if($post){
            $sql = "DELETE FROM $this->table WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            if ($stmt->execute()) {
                session_start(); 
                $_SESSION["alert"]['msg']="Row with ID $id deleted successfully.";
                $_SESSION["alert"]['type']="success";
                header('Location:./');
            } else {
                 header('Location: ./');
            }
        }
    }
    public function validate($data){
        $rule=new Rules;
        if(isset($data["submit"])&&$data["submit"]=="create"){
            $rule->required($data["title"])?$this->err['title'] = "Blog title is required":[];
            $rule->required($data["content"])?$this->err['content']="Blog content is required":[];
            if( $rule->required($data["image"])){
            $this->err['image']="Blog image is required";
            }else{
                $rule->fileSize($data["image"]['size'],5)?$this->err['image']="Blog image size  is too large":[];
                $allowedFile=['image/png','image/jpg','image/jpeg'];
                $rule->fileType($data["image"]['type'], $allowedFile)?$this->err['image']="Blog image type is not valid":[];
            }
        }
        if (empty($this->err)) {
             return $data;
        }
        $_SESSION['errors'] = $this->err;
       
        return false;
    }
}
?>

<!-- public function index(){
        $sql = "SELECT  * FROM {$this->table}";
        $query=isset($_GET["search"])?$_GET["search"]:'';
        $page=isset($_GET["page"])?$_GET["page"]:1;
        $total=$this->getAllCount();
        $limit=2;
        if(!empty($query)){
            $sql.= " WHERE title LIKE :query OR content LIKE :query";
            $count_sql = "SELECT  COUNt(*) as total FROM {$this->table} WHERE title LIKE :query OR content LIKE :query";
            $ct_stmt = $this->pdo->prepare($count_sql);
            $ct_stmt->bindValue(':query', '%' . $query . '%', \PDO::PARAM_STR);
            $ct_stmt->execute();
            $data =  $ct_stmt->fetch(\PDO::FETCH_ASSOC);
            $total=ceil($data['total']/$limit);
        }
        if( $page>ceil($total/$limit)){
            $page=ceil($total/$limit);
            $_GET["page"]=$page;
            header("Location: ?page=$page" . (!empty($query) ? "&search=" . urlencode($query) : ""));
            exit;
        }
        $offset=($page-1)*$limit;
        $sql .=" LIMIT :limit OFFSET :offset ";
        $stmt = $this->pdo->prepare($sql);
        if (!empty($query)) {
            $stmt->bindValue(':query', '%' . $query . '%', \PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $_SESSION['current_page']=$page;
        $_SESSION['total_page'] = ceil($total/$limit);
        return $data;
    } -->