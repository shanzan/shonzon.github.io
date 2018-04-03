<?php
//create database operation class
class DBOperations{

    //create a connection variable
    private $con;

    //create constructor
    function __construct(){
        try{

            //create database object
            $db = new DB();
            //call the connect function to connect DB
            $this->con=$db->connect();

        } catch (PDOException $e){
            $response=$e->getMessage();
            return $response;
        }
    }

    /*CRUD -> C -> CREATE A USER IN DATABASE allusers table */

    public function createUser($username, $useremail,$userphone,$user_pass,$usertype,$userupdated,$datecreated){

        //password encryption
        $userpassword= md5($user_pass);

        if ($usertype=="0") {
            //check the number of rows is greater than 0 or not for user
            if ($this->is_userExixts($useremail,$userphone)){
                return 0;
            }
            $sql = "INSERT INTO allusers(	user_name, user_email,user_phone,user_password,user_type,user_updated,date_created)
                  VALUES (:username,:useremail,:userphone,:userpassword,:usertype,:userupdated,:datecreated)";
        }else {
            //check the number of rows is greater than 0 or not foe subuser
            if ($this->sub_userExixts($useremail,$userphone)){
                return 0;
            }
            $sql="INSERT INTO allsubusers(	sub_user_name, sub_user_email,sub_user_phone,sub_user_password,user_id,sub_date_updated,sub_date_created)
                  VALUES (:username,:useremail,:userphone,:userpassword,:usertype,:userupdated,:datecreated)";
        }

            //prepare query
            $statement=$this->con->prepare($sql);

            //bind paramete with the input fields
            $statement->bindParam(':username',$username);
            $statement->bindParam(':useremail',$useremail);
            $statement->bindParam(':userphone',$userphone);
            $statement->bindParam(':userpassword',$userpassword);
            $statement->bindParam(':usertype',$usertype);
            $statement->bindParam(':userupdated',$userupdated);
            $statement->bindParam(':datecreated',$datecreated);

            //query execute or not check
            if ($statement->execute()) {
                return 1;
            } else {
                return 2;
            }
    }

    //check the user email and phone no is existed or not is exits or not
    private function is_userExixts($email,$phone){
        $sql="SELECT * From allusers WHERE user_email='$email' OR user_phone='$phone'";
        $stm1=$this->con->prepare($sql);
        $stm1->execute();

        //return the number of rows
        return $stm1->rowCount()>0;

    }

    //check the sub user email and phone no is existed or not is exits or not
    private function sub_userExixts($email,$phone){
        $sql="SELECT * From allsubusers WHERE sub_user_email='$email' OR sub_user_phone='$phone'";
        $stm1=$this->con->prepare($sql);
        $stm1->execute();
        //return the number of rows
        return $stm1->rowCount()>0;
    }

    //user login functions
    public function userLogin($useremail,$password){
        $pass=md5($password);
        $sql="SELECT * From allusers WHERE user_email='$useremail' and user_password='$pass'";
        $stm1=$this->con->prepare($sql);
        $stm1->execute();
        return $stm1->rowCount()>0;
    }

    //public function get user by email
    public function getUserbyemail($email){
        $sql="SELECT * From allusers WHERE user_email='$email'";
        $stmt=$this->con->query($sql);
        $users=$stmt->fetch();
        return $users;
    }

    //user login functions
    public function subUserLogin($useremail,$password){
        $pass=md5($password);
        $sql="SELECT * From allsubusers WHERE sub_user_email='$useremail' and sub_user_password='$pass'";
        $stm1=$this->con->prepare($sql);
        $stm1->execute();
        return $stm1->rowCount()>0;
    }

    //public function get user by email
    public function getSubuserbyemail($email){
        $sql="SELECT * From allsubusers WHERE sub_user_email='$email'";
        $stmt=$this->con->query($sql);
        $users=$stmt->fetch();
        return $users;
    }
    //user password change
    public function userPasswordChange($pass,$useremail){
        $userpassword= md5($pass);
        $sql="UPDATE allusers SET user_password=:userpassword WHERE user_email=:useremail";

        //prepare query
        $statement=$this->con->prepare($sql);

        //bind paramete with the input fields
        $statement->bindParam(':userpassword',$userpassword);
        $statement->bindParam(':useremail',$useremail);

        if ($statement->execute()) {
            return 1;
        } else {
            return 2;
        }

    }
    //sub user password change
    public function subuserPasswordChange($pass,$useremail){
        $userpassword= md5($pass);
        $sql="UPDATE allsubusers SET sub_user_password=:userpassword WHERE sub_user_email=:useremail";

        //prepare query
        $statement=$this->con->prepare($sql);

        //bind paramete with the input fields
        $statement->bindParam(':userpassword',$userpassword);
        $statement->bindParam(':useremail',$useremail);

        if ($statement->execute()) {
            return 1;
        } else {
            return 2;
        }

    }

    //forget sub user exists
    public function Forget_sub_userExixts($email){
        $sql="SELECT * From allsubusers WHERE sub_user_email='$email'";
        $stm1=$this->con->prepare($sql);
        $stm1->execute();
        //return the number of rows
        return $stm1->rowCount()>0;
    }

    //forget sub exists
    public function Forget_userExixts($email,$phone){
        $sql="SELECT * From allusers WHERE user_email='$email'";
        $stm1=$this->con->prepare($sql);
        $stm1->execute();

        //return the number of rows
        return $stm1->rowCount()>0;

    }

    //get user subuser exists or not
    public function userSubuserE($userid){
        $sql="SELECT * From allsubusers WHERE user_id='$userid'";
        $stmt=$this->con->query($sql);
        $users=$stmt->fetch();
        return $users;
    }
    //public function get sub  user by userid
    public function getAllSubUsers($userid){
        $sql="SELECT * From allsubusers WHERE user_id='$userid'";
        $stmt=$this->con->query($sql);
        $users=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $users;
    }

    public function DeletesubUser($subuserid){
        $sql = "DELETE FROM allsubusers WHERE sub_user_id=:subuserid";
        //prepare query
        $statement=$this->con->prepare($sql);
        $statement->bindParam(':subuserid',$subuserid);
        //query execute or not check
        if ($statement->execute()) {
            return 1;
        } else {
            return 2;
        }
    }

}
?>




<?php
//extra sql..............................................................
//            $sql="INSERT INTO allusers(user_name,user_email,user_phone,user_password,user_type,user_subuser_num,user_updated,date_created)
//                  VALUES ('$username','$useremail','$userphone','$password','$user_type','$user_subuser','$user_update','$date_created')";

?>
