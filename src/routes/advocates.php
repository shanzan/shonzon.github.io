<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App([

    'settings' => [
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false
    ],

]);
$container = $app->getContainer();



//insert into databases by post methods
$app->post('/api/advocates_registrations',function (Request $request, Response $response){


    //get parameters from the input fields
    $username=$request->getParam('name');
    $useremail=$request->getParam('email');
    $userphone=$request->getParam('phone');
    $user_pass=$request->getParam('password');
    $user_type=$request->getParam('type');
    $user_update= date("Y-m-d");
    $date_created= date("Y-m-d");

    //create an array for store results
    $arrayresponse=array();

    try{
        //create database operation object
        $dboperations=new DBOperations();

        //create user by  call functions
        $result=$dboperations->createUser($username, $useremail, $userphone,$user_pass,$user_type,$user_update,$date_created);

        if($result==1){
            $arrayresponse['error'] = false;
            $arrayresponse['message'] = "User registered successfully";
        }else if ($result==2){
            $arrayresponse['error'] = true;
            $arrayresponse['message'] = "Some error occurred please try again";
        }else if($result==0){
            $arrayresponse['error'] = true;
            $arrayresponse['message'] = "Your email or phone number already exists please use another email or phone";
        }else{
            $arrayresponse['error'] = true;
            $arrayresponse['message'] = "There is an Unknown error";
        }

        //get the message to the response body in a json format
        $response->getBody()->write(json_encode($arrayresponse));
        return $response;

    } catch (PDOException $e){
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});

//fetch user by usename or password
$app->post('/api/user_login',function (Request $request, Response $response){

    $useremail=$request->getParam('email');
    $userpassword=$request->getParam('password');
    $usertype=$request->getParam('usertype');

    $arrayresponse=array();
    try{
        //create database operation object
        $db = new DBOperations();

        if ($usertype=="subuser"){
            //check user exits  or not call functions
            if ($db->subUserLogin($useremail,$userpassword) ){

                //get user information by username /password
                $user=$db->getSubuserbyemail($useremail);
                $arrayresponse['error'] = false;
                $arrayresponse['id']=$user['sub_user_id'];
                $arrayresponse['name']=$user['sub_user_name'];
                $arrayresponse['email']=$user['sub_user_email'];
                $arrayresponse['number']=$user['sub_user_phone'];
                $arrayresponse['usertypeid']=$user['user_id'];
                $arrayresponse['message'] = "Login Successfull";

            }else{
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "Invalid Sub User Email Or Password";
            }
        }else{
            //check user exits  or not call functions
            if ($db->userLogin($useremail,$userpassword) ){
                //get user information by username /password
                $user=$db->getUserbyemail($useremail);
                $arrayresponse['error'] = false;
                $arrayresponse['id']=$user['user_id'];
                $arrayresponse['name']=$user['user_name'];
                $arrayresponse['email']=$user['user_email'];
                $arrayresponse['email']=$user['user_email'];
                $arrayresponse['number']=$user['user_phone'];
                $arrayresponse['message'] = "Login Successfull";

            }else{
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "Invalid User Email Or Password";
            }
        }

        $response->getBody()->write(json_encode($arrayresponse));
        return $response;

    } catch (PDOException $e){
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});
//update user password
$app->post('/api/user_passwordchange',function (Request $request, Response $response){


    //get parameters from the input fields
    $usermail=$request->getParam('useremail');
    $user_old=$request->getParam('oldpassword');
    $user_pword=$request->getParam('password');

    //create an array for store results
    $arrayresponse=array();

    try{
        //create database operation object
        $dboperations=new DBOperations();

        if ($dboperations->userLogin($usermail,$user_old)){
            //update user by  call functions
            $result=$dboperations->userPasswordChange($user_pword,$usermail);
            if($result==1){
                $arrayresponse['error'] = false;
                $arrayresponse['message'] = "Password change successfully";
            }else if ($result==2){
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "Some error occurred please try again";
            }else{
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "There is an Unknown error";
            }
        }else{
            $arrayresponse['error'] = true;
            $arrayresponse['message'] = "Password did not match";
        }
        //get the message to the response body in a json format
        $response->getBody()->write(json_encode($arrayresponse));
        return $response;

    } catch (PDOException $e){
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});
//update sub user password
$app->post('/api/sub_user_passwordchange',function (Request $request, Response $response){


    //get parameters from the input fields
    $usermail=$request->getParam('useremail');
    $user_old=$request->getParam('oldpassword');
    $user_pword=$request->getParam('password');

    //create an array for store results
    $arrayresponse=array();

    try{
        //create database operation object
        $dboperations=new DBOperations();

        if ($dboperations->subUserLogin($usermail,$user_old)){
            //update user by  call functions
            $result=$dboperations->subuserPasswordChange($user_pword,$usermail);
            if($result==1){
                $arrayresponse['error'] = false;
                $arrayresponse['message'] = "Password change successfully";
            }else if ($result==2){
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "Some error occurred please try again";
            }else{
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "There is an Unknown error";
            }
        }else{
            $arrayresponse['error'] = true;
            $arrayresponse['message'] = "Password did not match";
        }

        //get the message to the response body in a json format
        $response->getBody()->write(json_encode($arrayresponse));
        return $response;

    } catch (PDOException $e){
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});
//forget password to fetch password
$app->post('/api/forget_password',function (Request $request, Response $response){

    $useremail=$request->getParam('email');
    $usertype=$request->getParam('usertype');

    $arrayresponse=array();
    try{
        //create database operation object
        $db = new DBOperations();

        if ($usertype=="subuser"){
            //check user exits  or not call functions
            if ($db->Forget_sub_userExixts($useremail) ){
                //get user information by username /password
                $user=$db->getSubuserbyemail($useremail);
                $arrayresponse['error'] = false;
                $arrayresponse['name']=$user['sub_user_name'];
                $arrayresponse['email']=$user['sub_user_email'];
                $arrayresponse['number']=$user['sub_user_phone'];
                $arrayresponse['type']="subuser";
                $arrayresponse['message'] = "A varification code sent to your Phone";

            }else{
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "Invalid Sub User Email Or Password";
            }
        }else{
            //check user exits  or not call functions
            if ($db->Forget_userExixts($useremail) ){
                //get user information by username /password
                $user=$db->getUserbyemail($useremail);
                $arrayresponse['error'] = false;
                $arrayresponse['name']=$user['user_name'];
                $arrayresponse['email']=$user['user_email'];
                $arrayresponse['number']=$user['user_phone'];
                $arrayresponse['type']="user";
                $arrayresponse['message'] = "A varification code sent to your Phone";

            }else{
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "Invalid User Email Or Password";
            }
        }

        $response->getBody()->write(json_encode($arrayresponse));
        return $response;

    } catch (PDOException $e){
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});
//get all cases by id
$app->post('/api/find_subUsers',function (Request $request, Response $response,array $args){

    $userid=$request->getParam('id');
    $getcase=array();
    try{
        //get db object
        $db=new DBOperations();
        //get case by functions
        $getcase=$db->getAllSubUsers($userid);
        $response->getBody()->write(json_encode($getcase));
        return $response;
    }catch (PDOException $e){
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});

//delet sub users by post methods
$app->post('/api/delete_sub_users',function (Request $request, Response $response){
    $user_id=$request->getParam('id');
    //create an array for store results
    $arrayresponse=array();
    try{
        //create database operation object
        $dboperations=new DBOperations();
        //create case by  call functions
        $result=$dboperations->DeletesubUser($user_id);
        if($result==1){
            $arrayresponse['error'] = false;
            $arrayresponse['message'] = "Sub User Delete successfully";
        }else if ($result==2){
            $arrayresponse['error'] = true;
            $arrayresponse['message'] = "Server Error";
        }else{
            $arrayresponse['error'] = true;
            $arrayresponse['message'] = "There is an Unknown error";
        }

        //get the message to the response body in a json format
        $response->getBody()->write(json_encode($arrayresponse));
        return $response;

    } catch (PDOException $e){
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});


//update user password
$app->post('/api/recover_password',function (Request $request, Response $response){


    //get parameters from the input fields
    $usermail=$request->getParam('useremail');
    $user_pword=$request->getParam('password');
    $usertype=$request->getParam('type');

    //create an array for store results
    $arrayresponse=array();

    try{
        //create database operation object
        $dboperations=new DBOperations();

        if ($usertype=="subuser"){
            //update user by  call functions
            $result=$dboperations->subuserPasswordChange($user_pword,$usermail);
            if($result==1){
                $arrayresponse['error'] = false;
                $arrayresponse['message'] = "Password change successfully";
            }else if ($result==2){
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "Some error occurred please try again";
            }else{
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "There is an Unknown error";
            }
        }else{
            //update user by  call functions
            $result=$dboperations->userPasswordChange($user_pword,$usermail);
            if($result==1){
                $arrayresponse['error'] = false;
                $arrayresponse['message'] = "Password change successfully";
            }else if ($result==2){
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "Some error occurred please try again";
            }else{
                $arrayresponse['error'] = true;
                $arrayresponse['message'] = "There is an Unknown error";
            }
        }
        //get the message to the response body in a json format
        $response->getBody()->write(json_encode($arrayresponse));
        return $response;

    } catch (PDOException $e){
        $response->getBody()->write($e->getMessage());
        return $response;
    }
});

$app->run();