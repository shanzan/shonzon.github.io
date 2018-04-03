<?php
//create database operation class
class CaseOperations
{

    //create a connection variable
    private $con;

    //create constructor
    function __construct()
    {
        try {

            //create database object
            $db = new DB();
            //call the connect function to connect DB
            $this->con = $db->connect();

        } catch (PDOException $e) {
            $response = $e->getMessage();
            return $response;
        }
    }

    /*CRUD -> C -> CREATE A case IN DATABASE allcase table */
    public function createCase($casenumber,$casetype,$complainantname,$complainantphone,
                      $complainantaddress,$opponentname,$opponentphone,$opponentaddress,$previousdate,$nextdate,
                       $courtname,$courttype,$courtgenre,$referedbyad,$commentby,$userid,$casecreated,$caseupdated){


            $sql = "INSERT INTO allcases(case_number,case_type,complainant_name,complainant_phone,
                      complainant_address,opponent_name,opponent_phone,opponent_address,previous_date,next_date,
                       court_name,court_type,court_genre,refered_by,comment,user_id,case_created,case_updated)
                  VALUES (:casenumber,:casetype,:complainantname,:complainantphone,
                      :complainantaddress,:opponentname,:opponentphone,:opponentaddress,:previousdate,:nextdate,
                       :courtname,:courttype,:courtgenre,:referedbyad,:commentby,:userid,:casecreated,:caseupdated)";

        //prepare query
        $statement=$this->con->prepare($sql);

        //bind paramete with the input fields
        $statement->bindParam(':casenumber',$casenumber);
        $statement->bindParam(':casetype',$casetype);
        $statement->bindParam(':complainantname',$complainantname);
        $statement->bindParam(':complainantphone',$complainantphone);
        $statement->bindParam(':complainantaddress',$complainantaddress);
        $statement->bindParam(':opponentname',$opponentname);
        $statement->bindParam(':opponentphone',$opponentphone);
        $statement->bindParam(':opponentaddress',$opponentaddress);
        $statement->bindParam(':previousdate',$previousdate);
        $statement->bindParam(':nextdate',$nextdate);
        $statement->bindParam(':courtname',$courtname);
        $statement->bindParam(':courttype',$courttype);
        $statement->bindParam(':courtgenre',$courtgenre);
        $statement->bindParam(':referedbyad',$referedbyad);
        $statement->bindParam(':commentby',$commentby);
        $statement->bindParam(':userid',$userid);
        $statement->bindParam(':casecreated',$casecreated);
        $statement->bindParam(':caseupdated',$caseupdated);

        //query execute or not check
        if ($statement->execute()) {
            return 1;
        } else {
            return 2;
        }
    }

    //fetch all case data by id
    public function getAllCaseData($userid){
        $sql="SELECT * FROM allcases WHERE user_id='$userid'";
        $stmt=$this->con->query($sql);
        $cases=$stmt->fetchAll(PDO::FETCH_OBJ);
        return $cases;
    }
    /*CRUD -> U -> UPDATE A case IN DATABASE allcase table */
    public function UpdateCase($casetype,$complainantphone,
                               $complainantaddress,$opponentphone,$opponentaddress,$previousdate,$nextdate,
                               $courtname,$courttype,$courtgenre,$commentby,$caseupdated,$caseid){


        $sql = "UPDATE allcases SET case_type=:casetype,complainant_phone=:complainantphone,
                      complainant_address=:complainantaddress,opponent_phone=:opponentphone,opponent_address=:opponentaddress,
                      previous_date=:previousdate,next_date=:nextdate,
                       court_name=:courtname,court_type=:courttype,court_genre=:courtgenre,comment=:commentby,case_updated=:caseupdated WHERE 
                       case_id=:caseid";

        //prepare query
        $statement=$this->con->prepare($sql);

        //bind paramete with the input fields
        $statement->bindParam(':casetype',$casetype);
        $statement->bindParam(':complainantphone',$complainantphone);
        $statement->bindParam(':complainantaddress',$complainantaddress);
        $statement->bindParam(':opponentphone',$opponentphone);
        $statement->bindParam(':opponentaddress',$opponentaddress);
        $statement->bindParam(':previousdate',$previousdate);
        $statement->bindParam(':nextdate',$nextdate);
        $statement->bindParam(':courtname',$courtname);
        $statement->bindParam(':courttype',$courttype);
        $statement->bindParam(':courtgenre',$courtgenre);
        $statement->bindParam(':commentby',$commentby);
        $statement->bindParam(':caseupdated',$caseupdated);
        $statement->bindParam(':caseid',$caseid);

        //query execute or not check
        if ($statement->execute()) {
            return 1;
        } else {
            return 2;
        }
    }
    /*CRUD -> D -> Delete A case IN DATABASE allcase table */
    public function DeleteCase($caseid){


        $sql = "DELETE FROM allcases WHERE case_id=:caseid";

        //prepare query
        $statement=$this->con->prepare($sql);

        $statement->bindParam(':caseid',$caseid);

        //query execute or not check
        if ($statement->execute()) {
            return 1;
        } else {
            return 2;
        }
    }

}
?>