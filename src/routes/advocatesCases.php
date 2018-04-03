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
$app->post('/api/case_registrations',function (Request $request, Response $response){


    //get parameters from the input fields
    //all  the parameter get by request from the web and those are the parameter name
    $case_number=$request->getParam('number');
    $case_type=$request->getParam('type');
    $complainant_name=$request->getParam('c_name');
    $complainant_phone=$request->getParam('c_phone');
    $complainant_address=$request->getParam('c_address');
    $opponent_name=$request->getParam('o_name');
    $opponent_phone=$request->getParam('o_phone');
    $opponent_address=$request->getParam('o_address');
    $previous_date=$request->getParam('pre_date');
    $next_date=$request->getParam('next_date');
    $court_name=$request->getParam('court_name');
    $court_type=$request->getParam('court_type');
    $court_genre=$request->getParam('court_genre');
    $refered_by=$request->getParam('referedby');
    $comment=$request->getParam('comment');
    $user_id=$request->getParam('userid');
    $case_created=date("Y-m-d");
    $case_updated=date("Y-m-d");

    //create an array for store results
    $arrayresponse=array();

    try{
        //create database operation object
        $dboperations=new CaseOperations();

        //create case by  call functions
        $result=$dboperations->createCase($case_number,$case_type,$complainant_name,$complainant_phone,
            $complainant_address,$opponent_name,$opponent_phone,$opponent_address,$previous_date,$next_date,
            $court_name,$court_type,$court_genre,$refered_by,$comment,$user_id,$case_created,$case_updated);

        if($result==1){
            $arrayresponse['error'] = false;
            $arrayresponse['message'] = "Case Added successfully";
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
//get all cases by id
$app->post('/api/all_cases_show',function (Request $request, Response $response,array $args){

    $userid=$request->getParam('id');
    $getcase=array();
        try{
            //get db object
            $db=new CaseOperations();
            //get case by functions
                $getcase=$db->getAllCaseData($userid);
                $response->getBody()->write(json_encode($getcase));
                return $response;
        }catch (PDOException $e){
            $response->getBody()->write($e->getMessage());
            return $response;
        }
});

//update databases by post methods
$app->post('/api/case_update',function (Request $request, Response $response){


    //all  the parameter get by request from the web and those are the parameter name
    $case_type=$request->getParam('type');
    $complainant_phone=$request->getParam('c_phone');
    $complainant_address=$request->getParam('c_address');
    $opponent_phone=$request->getParam('o_phone');
    $opponent_address=$request->getParam('o_address');
    $previous_date=$request->getParam('pre_date');
    $next_date=$request->getParam('next_date');
    $court_name=$request->getParam('court_name');
    $court_type=$request->getParam('court_type');
    $court_genre=$request->getParam('court_genre');
    $comment=$request->getParam('comment');
    $caseid_id=$request->getParam('caseid');
    $case_updated=date("Y-m-d");

    //create an array for store results
    $arrayresponse=array();

    try{
        //create database operation object
        $dboperations=new CaseOperations();

        //create case by  call functions
        $result=$dboperations->UpdateCase($case_type,$complainant_phone,
            $complainant_address,$opponent_phone,$opponent_address,$previous_date,$next_date,
            $court_name,$court_type,$court_genre,$comment,$case_updated,$caseid_id);

        if($result==1){
            $arrayresponse['error'] = false;
            $arrayresponse['message'] = "Case Update successfully";
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
//delete case by post methods
$app->post('/api/case_delete',function (Request $request, Response $response){


    $caseid_id=$request->getParam('caseid');

    //create an array for store results
    $arrayresponse=array();

    try{
        //create database operation object
        $dboperations=new CaseOperations();

        //create case by  call functions
        $result=$dboperations->DeleteCase($caseid_id);
        if($result==1){
            $arrayresponse['error'] = false;
            $arrayresponse['message'] = "Case Delete successfully";
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

$app->run();
?>