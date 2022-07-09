<?php
session_start();
require_once("../config.php");
//class users inherit from pdo class for database stuff
class users extends PDO{
    const PARAM_host=PARAM_HOST;
    const PARAM_port=PARAM_PORT;
    const PARAM_db_name=PARAM_DB_NAME;
    const PARAM_user=PARAM_DB_USER;
    const PARAM_db_pass=PARAM_DB_PASS;
    const pepper_hash=pepper;
    //construction of class of pdo with params
    public function __construct($options=null)
    {
        parent::__construct('mysql:host='.users::PARAM_host,users::PARAM_user,users::PARAM_db_pass,$options);
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbname=users::PARAM_db_name;
        parent::query("CREATE DATABASE IF NOT EXISTS $dbname");
        parent::query("use $dbname");
    }
    //create users table
    public function create_users_table()
    {
        $this->query("CREATE TABLE IF NOT EXISTS `users` (
            id int AUTO_INCREMENT not null PRIMARY KEY,
            name varchar(255) not null,
            email varchar(30) not null unique,
            password varchar(255) not null,
            gender ENUM('male','female'),
            birth_date datetime,
            photo varchar(255) not null,
            role ENUM ('administrator','employee') not null,
            created_at date DEFAULT CURRENT_DATE(),
            updated_at date DEFAULT CURRENT_DATE()
        )");
    }
    //create new user
    public function create_user(){
        // password hashing
        $pwd_peppered=hash_hmac("sha256", htmlspecialchars($_POST["password"]), $this::pepper_hash);
        $pwd_hashed = password_hash($pwd_peppered, PASSWORD_ARGON2ID);
        // create new user
        $query=$this->prepare("INSERT INTO `users`(`name`, `email`, `password`, `photo`,`gender`,`birth_date`, `role`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?,?,?)");
        $photo="avatar-male.jpg";
        if($_POST["gender"]=="female"){
            $photo="avatar-female.jpg";
        }
        //set time zone
        date_default_timezone_set('Africa/Cairo');
        // 
        $query->execute(
            [htmlspecialchars($_POST["name"])
            ,htmlspecialchars($_POST["email"])
            ,htmlspecialchars($pwd_hashed)
            ,htmlspecialchars($photo)
            ,$_POST["gender"]
            ,$_POST["birth_date"]
            ,htmlspecialchars($_POST["user_type"])
            ,htmlspecialchars(date("Y-m-d h:i:sa"))
            ,htmlspecialchars(date("Y-m-d h:i:sa"))
        ]);
        $result=$this->query("select max(id) as max from users");
        $user_id=$result->fetchAll()[0]["max"];
        if(!empty($_FILES["user_image"]["name"])){
            $name=time()."_".$user_id."_".basename($_FILES["user_image"]["name"]);
            $path=ROOT_PATH."public/images/user/".$name;
            // $path;
            if(move_uploaded_file($_FILES["user_image"]["tmp_name"],$path)){
                $query=$this->query("UPDATE `users` SET `photo`='$name' where id='$user_id'");
                $_SESSION["auth"]=[
                    "id"=>$user_id,
                    "name"=>$_POST["name"],
                    "email"=>$_POST["email"],
                    "photo"=>$name,
                    "gender"=>$_POST["gender"],
                    "birth_date"=>$_POST["birth_date"],
                    "role"=>$_POST["user_type"]
                ];
                header("location:".Base_URL."views/");
            }
        }else
        {
            $_SESSION["auth"]=[
                "id"=>$user_id,
                "name"=>$_POST["name"],
                "email"=>$_POST["email"],
                "photo"=>$photo,
                "gender"=>$_POST["gender"],
                "birth_date"=>$_POST["birth_date"],
                "role"=>$_POST["user_type"]
            ];
            header("location:".Base_URL."views/");

        }

    }
    // if the user is exists
    public function user_exists($user){
        $result=$this->query("select * from users where email='$user'");
        if($result->rowCount() >0){
            return false;
        }
        return true;
    }
    // validate user email
    public static function validateEmail($email) {
        //regex for email formula
        $regex = "/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,4})$/";
        return preg_match($regex, $email) ? true:false;
    }
    // verify the user login have email and save the session of Authentication for user verify role and actions in the site 
    public function user_verification($request){
        $result=$this->prepare("select * from users where email=?");
        $result->execute([$request["user"]]);
        // password_verify();
        if($result->rowCount()>0)
        {
                $user=$result->fetch();
                $pwd_peppered = hash_hmac("sha256", $request["password"], $this::pepper_hash);
                /// if the user is exists the session of the user Authentication is created and save user info
                if(password_verify($pwd_peppered,$user["password"]))
                {
                    $_SESSION["auth"]=[
                        "id"=>$user["id"],
                        "name"=>$user["name"],
                        "email"=>$user["email"],
                        "photo"=>$user["photo"],
                        "birth_date"=>$user["birth_date"],
                        "gender"=>$user["gender"],
                        "role"=>$user["role"]];
                    header("location:".Base_URL."views/");
                }else
                {
                    //the user  error in email password
                    $error_message="no matching any db records";
                    $_SESSION["error"]=["page"=>"login","target"=>"validation_login","message"=>$error_message];
                    header("location:".Base_URL."views/user/login.php");
                    $errors=1;
                }
        }
        else{
            //the user  doesn't exists or
            $error_message="no matching any db records";
            $_SESSION["error"]=["page"=>"login","target"=>"validation_login","message"=>$error_message];
            header("location:".Base_URL."views/user/login.php");
            $errors=1;
        }
    }
    // update user info
    public function update_user_profile($cridentials){
        $passflag="off";
        $hashed_password="";
        //detect if user want change password
        if($cridentials["passchange"]=="on")
        {
            // double cheching password and save hashed values
            if($_POST["pass"]===$_POST["retypepass"])
            {
                $passflag="on";
                $pwd_peppered=hash_hmac("sha256", $_POST["pass"], $this::pepper_hash);
                $hashed_password = password_hash($pwd_peppered, PASSWORD_ARGON2ID);
                
            }
            else{
                $errors=1;
                $_SESSION["error"]=["page"=>"company","target"=>"company","message"=>"error in image uploading"];
                header("location:/company/views/");
            }
        }
        //updated value of user account
        $name=$cridentials["name"];
        $photo=$cridentials["photo"];
        $gender=$cridentials["gender"];
        $birth_date=$cridentials["birth_date"];
        $errors=0;
        //UPLOAD NEW IMAGE AND DELETE OLD ONE
        if(!empty($photo["name"]))
        {
            //photo save with time and user id file name
            $photo_name=time()."_".$_SESSION["auth"]["id"]."_".basename($cridentials["photo"]["name"]);
            $path=ROOT_PATH."public/images/user/".$photo_name;
            if(!move_uploaded_file($cridentials["photo"]["tmp_name"],$path))
            {
                $errors=1;
                $_SESSION["error"]=["page"=>"company","target"=>"company","message"=>"error in image uploading"];
                header("location:/company/views/");
            }
            if($errors==0)
            {
                $result=$this->query("select * from `users` where id = ".$_SESSION['auth']['id']);
                $record=$result->fetch();
                $file_name=ROOT_PATH."public/images/user/".$record["photo"];
                //chech the photo isn't the default photo for user
                if($record["photo"]!="avatar-female.jpg"&&$record["photo"]!="avatar-male.jpg")
                {
                    unlink($file_name);
                }
                //check user if update password with values
                if($passflag=="on")
                {
                    $query=$this->prepare("UPDATE `users` SET photo = ? , name = ? , password = ? , gender = ? , birth_date = ? where id = ?");
                    $stat=$query->execute([$photo_name,$name,$hashed_password,$gender,$birth_date,$_SESSION['auth']['id']]);
                }else{
                    $query=$this->prepare("UPDATE `users` SET photo = ? , name = ? , gender = ? , birth_date = ? where id = ?");
                    $stat=$query->execute([$photo_name,$name,$gender,$birth_date,$_SESSION['auth']['id']]);
                }
                // save the session with new values
                if($stat){
                    $_SESSION['auth']["name"]=$name;
                    $_SESSION['auth']["photo"]=$photo_name;
                    $_SESSION['auth']["gender"]=$gender;
                    $_SESSION['auth']["birth_date"]=$birth_date;
                    $_SESSION["succss"]=["page"=>"company","target"=>"category","message"=>"profile updated successfully"];
                    header("location:/company/views/");
                }else{
                    //detect the upload file error
                    $_SESSION["error"]=["page"=>"company","target"=>"company","message"=>"error in image uploading"];
                    header("location:/company/views/");
                }
            }
        }
        //if user doesn't upload new image
        else{
            //update with new password
            if($passflag=="on"){
                $query=$this->prepare("UPDATE `users` SET name = ? , password = ? , gender = ? , birth_date = ? where id = ?");
                $stat=$query->execute([$name,$hashed_password,$gender,$birth_date,$_SESSION['auth']['id']]);
            }else{
                $query=$this->prepare("UPDATE `users` SET name = ? , gender = ? , birth_date = ? where id = ?");
                $stat=$query->execute([$name,$gender,$birth_date,$_SESSION['auth']['id']]);
            }
            //when updated success the user session new values
            if($stat){

                $_SESSION['auth']["name"]=$name;
                $_SESSION['auth']["gender"]=$gender;
                $_SESSION['auth']["birth_date"]=$birth_date;
                $_SESSION["succss"]=["page"=>"company","target"=>"category","message"=>"profile updated successfully"];
                header("location:/company/views/");
            }else{
                $_SESSION["error"]=["page"=>"company","target"=>"company","message"=>"error in image uploading"];
                header("location:/company/views/");
            }
        }
        
    }
}
//handle post requests
if ($_SERVER['REQUEST_METHOD']=="POST"){
    // route of update-user-profile
    if(isset($_POST["routefor"]) && $_POST["routefor"] === "update-user-profile"){
        $user = new users();
        $name=htmlspecialchars($_POST["name"]);
        $photo=$_FILES["image-file-user"];
        $passswitch="off";
        if(isset($_POST["passswitcher"]))
        {
            $passswitch=htmlspecialchars($_POST["passswitcher"]);
        }
        $cridentials=[
            "name"=>$name,
            "photo"=>$photo,
            "passchange"=>$passswitch,
            "gender"=>htmlspecialchars($_POST["gender"]),
            "birth_date"=>htmlspecialchars($_POST["birth_date"])
        ];
        $user->update_user_profile($cridentials);
    }
    // route of user sign up and user validation
    if(isset($_POST["routefor"])&&$_POST["routefor"]=="signup"){
        $user = new users();
        $user->create_users_table();
        $errors=0;
        if(isset($_SESSION["error"]))
            unset($_SESSION["error"]);
        if($_POST["name"]===""){
            $_SESSION["error"]=["page"=>"user","target"=>"name","message"=>"name must have value"];
            header("location:".Base_URL."views/user/signup.php");
            $errors=1;
        }
        if($_POST["email"]!=""){
            $valid=users::validateEmail($_POST["email"]);
            if(!$valid){
                $_SESSION["error"]=["page"=>"user","target"=>"email","message"=>"email is not valid"];
                header("location:".Base_URL."views/user/signup.php");
                $errors=1;
            }
            else{
                if(!$user->user_exists($_POST["email"])){
                    $errors=1;
                    $_SESSION["error"]=["page"=>"user","target"=>"email","message"=>"email is taken choose another one"];
                    header("location:".Base_URL."views/user/signup.php");
                }
            }
        }else{
                $_SESSION["error"]=["page"=>"user","target"=>"email","message"=>"email must have value"];
                header("location:".Base_URL."views/user/signup.php");
                $errors=1;
    
        }
        if($_POST["password"]!=""){
            if(strlen($_POST["password"])>8){
    
            }else{
                $_SESSION["error"]=["page"=>"user","target"=>"password","message"=>"length less than 8 chars"];
                header("location:".Base_URL."views/user/signup.php");
                $errors=1;
    
            }
        }else{
                $_SESSION["error"]=["page"=>"user","target"=>"password","message"=>" passwordmust have value"];
                header("location:".Base_URL."views/user/signup.php");
                $errors=1;
    
        }
        if($_POST["birth_date"]==="")
        {
            $_SESSION["error"]=["page"=>"user","target"=>"birth_date","message"=>"birth date must have value"];
            header("location:".Base_URL."views/user/signup.php");
            $errors=1;
        }
        if($_POST["gender"]==="")
        {
            $_SESSION["error"]=["page"=>"user","target"=>"gender","message"=>"gender must have value"];
            header("location:".Base_URL."views/user/signup.php");
            $errors=1;
        }
        if($_POST["user_type"]==="")
        {
            $_SESSION["error"]=["page"=>"user","target"=>"user_type","message"=>"user type must have value"];
            header("location:".Base_URL."views/user/signup.php");
            $errors=1;
        }
        if($errors==0){
            $user->create_user();
        }
    }
}
// handle get requests
else if ($_SERVER['REQUEST_METHOD']=="GET"){
    //delete all error sessions
    if(isset($_SESSION["error"]))
        unset($_SESSION["error"]);
        // validation sign in data and redirect to the main view
        if(isset($_GET["routefor"]) && $_GET["routefor"]=="signin"){
        $user = new users();
        $user->create_users_table();
        if(strlen(htmlspecialchars($_GET["email"]))!=0&&strlen(htmlspecialchars($_GET["password"]))!=0){
            $request=["user"=>htmlspecialchars($_GET["email"]),"password"=>htmlspecialchars($_GET["password"])];
            $user->user_verification($request);
        }
        else{
            //validate all missing values and return error
            $error_message="";
            if(strlen(htmlspecialchars($_GET["email"]))==0){
                $error_message.="email musn't be empty";
            }
            if(strlen(htmlspecialchars($_GET["password"]))==0){
                $error_message.="password musn't be empty";
            }
            // set error session and redirect to login page
            $_SESSION["error"]=["page"=>"login","target"=>"validation_login","message"=>$error_message];
            header("location:".Base_URL."views/user/login.php");
            $errors=1;
        }
    }
    // log out function and delete all sessions of user redirect to sign in page
    else if(isset($_GET["routefor"]) && $_GET["routefor"] == "logout"){
        session_unset();
        session_destroy();
        $helper = array_keys($_SESSION);
        foreach ($helper as $key){
            unset($_SESSION[$key]);
        }
        header("location:".Base_URL."views/user/login.php");
    }
}