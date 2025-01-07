<?php
namespace app;
include(__DIR__ . '/DB.php');
include(__DIR__ . '/Rules.php');
use app\DB;
use app\Rules;


class User{
    private $table="user";
    private $sql;
    private $pdo;
    private $err=[];
    public function __construct(){
        $db=new DB;
        $this->pdo=$db->getConnection();
    }
    public function login($email,$password){
        $sql = "SELECT * FROM $this->table WHERE email = :email ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user= $stmt->fetch(\PDO::FETCH_ASSOC);
       
        if(isset( $user) && isset($user['password']) ){
            if($password==$user['password']){
            $_SESSION['user_id']=$user["id"];
            $_SESSION['logged_in']=time();
             header("Location: ./index.php");
            }
        }
        $_SESSION["login_alert"]['loin_msg']="Wrong Credential";
        $_SESSION["login_alert"]['type']="login_error";
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
                $allowedFile=['image/png','image/jpg'];
                $rule->fileType($data["image"]['type'], $allowedFile)?$this->err['image']="Blog image type is not valid":[];
            }
        }
        if (empty($this->err)) {
             return $data;
        }
        $_SESSION['errors'] = $this->err;
       
        return false;
    }
    public function find($id){
        $sql="SELECT * from $this->table WHERE id=".$id;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data?$data:false;
    }
}
?>