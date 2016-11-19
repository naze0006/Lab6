<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>Registration</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
    </head>
    
    <body>
<?php

include("./Lab6Common/Header.php");
include_once "./Lab6Common/EntityClass_Lib.php";
include_once "./Lab6Common/DataAccessClass_Lib.php";
include "./Lab6Common/Function_Lib.php";
include "./Lab6Common/Constants.php";
            
session_start();
extract($_POST);

$dao = new DataAccessObject(INI_FILE_PATH);
           
if (isset($_POST["submit"]))
{
            
    $stdId = trim($_POST["id"]);
    $stdName = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $pass = trim($_POST["password"]);
    $passAgain = trim($_POST["passwordAgain"]);

    $studentIdValidateError = validateStudentId($stdId);
    $nameValidateError = validateName($stdName);
    $phoneValidateError = validatePhone($phone);
    $passValidateError = validatePassword($pass); 
    $passAgainValidateError = validatePasswordAgain($passAgain);
    $studentExistValidateError = $dao->studentExists($stdId);
            
    $errorlist = [];
            
    if (strlen($nameValidateError) > 0) {
        array_push($errorlist, $nameValidateError);
    }
    if (strlen($studentIdValidateError) > 0) {
        array_push($errorlist, $studentIdValidateError);
    }
    if (strlen($phoneValidateError) > 0) {
        array_push($errorlist, $phoneValidateError);
    }
    if (strlen($passValidateError) > 0) {
        array_push($errorlist, $passValidateError);
    }
    if (strlen($passAgainValidateError) > 0) {
        array_push($errorlist, $passAgainValidateError);
    }
       
    
    
$exists = $dao->studentExists($stdId);
if(!$exists)
{
   
    if (count($errorlist) <= 0){
                
        $_SESSION["name"] = $stdName;
        $_SESSION["id"] = $stdId;
        $_SESSION["phone"] = $phone;
        $_SESSION["password"] = $pass;
        $_SESSION["loggedin"] = true;
        $dao->saveStudent($stdId, $stdName, $phone, $pass);
        
        $student = $dao->getStudentByIdAndPassword($stdId, $password);
        $_SESSION["student"] = $student;
        
        header("Location: CourseSelection.php");
        exit();
                
    } 
}
else
{
    array_push($errorlist, $studentExistValidateError);// needs to be improved
}

}
if(isset($_POST["btnClear"]))
        {
            $_SESSION["name"] = false;
            $_SESSION["id"] = false;
            $_SESSION["phone"];
            $_SESSION["password"];
            $stdName = "";
            $stdId = "";
            $phone = "";
            $pass = "";

            
            unset($_SESSION["name"]);
            unset($_SESSION["id"]);
            unset($_SESSION["phone"]);
            unset($_SESSION["password"]);
            
        }
            

?>
        <div class="container-fluid">
            <div class="row vertical-margin">
                <div class="col-md-12">
                    <h2>Sign Up</h2>
                </div>          
            </div>
            <div class="row vertical-margin">
                <div class="col-md-12">
                    <p>All fields are required</p>
                </div>          
            </div>
            
            <br/>
            <form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="id">Student ID:</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="id" name="id" 
                               value="<?php print $stdId ?>"/><span style="color:red"><?php print $studentIdValidateError ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="name">Name:</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php print $stdName ?>"/><span style="color:red"><?php print $nameValidateError ?></span>
                    </div>
                </div>
                
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="phone">Phone Number: </label>
                    <div class="col-sm-4">
                        <input class="form-control col-sm-4" type="text" id="phone" name="phone" 
                               value="<?php print $phone ?>"/><span style="color:red"><?php print $phoneValidateError ?></span>
                    </div>

                </div>
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="password" >Password: </label>  
                    <div class="col-sm-4">
                        <input class="form-control col-sm-4" type="password" id="password" name="password" value="<?php print $pass ?>"><span style="color:red"><?php print $passValidateError ?></span>  
                    </div> 

                </div>
                
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="passwordAgain" >Password Again: </label>  
                    <div class="col-sm-4">
                        <input class="form-control col-sm-4" type="password" id="passwordAgain" name="passwordAgain" value="<?php print $passAgain ?>"><span style="color:red"><?php print $passAgainValidateError ?></span>  
                    </div> 

                </div>
                <br/>
              
                <div class="col-sm-6">
                    <input class="btn btn-primary" type = "submit" name="submit" value = "Submit" class="button" />
                    <button class="btn btn-primary" type="reset" name="btnClear" value="Reset" class="button">Clear</button>
                </div>

            </form>
            
            
            
    </body>
<?php include("./Lab6Common/Footer.php"); ?>