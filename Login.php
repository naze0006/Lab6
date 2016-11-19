<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>Signing Up</title>
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
    $pass = trim($_POST["password"]);
   
   // $student = new Student();
    $student = $dao->getStudentByIdAndPassword($stdId, $pass);                            
    
    $studentIdValidateError = validateStudentId($stdId);
    $passValidateError = validatePassword($pass); 
            
    $errorlist = [];
            
    if (strlen($nameValidateError) > 0) {
        array_push($errorlist, $nameValidateError);
    }
    
    if (strlen($passValidateError) > 0) {
        array_push($errorlist, $passValidateError);
    }
    
       
    
    
$validateStudents = $dao->getStudentByIdAndPassword($studentId, $password);
if(!$validateStudents)
{
   
    if (count($errorlist) <= 0){

        $_SESSION["student"]=$student;
        //$_SESSION["loggedin"] = true;
        
        header("Location: CourseSelection.php");
        exit();
                
    } 
}

}
if(isset($_POST["btnClear"]))
        {
            $_SESSION["id"] = false;
            $stdId = "";
            $pass = "";
            unset($_SESSION["id"]); 
            unset($_SESSION["student"]);
        }

?>

        <div class="container-fluid">
            <div class="row vertical-margin">
                <div class="col-md-12">
                    <h2>Sign In</h2>
                </div>          
            </div>
            <div class="row vertical-margin">
                <div class="col-md-12">
                    <p>you need to sign up if you are a new user</p>
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
                
                
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="password" >Password: </label>  
                    <div class="col-sm-4">
                        <input class="form-control col-sm-4" type="password" id="password" name="password" value="<?php print $pass ?>"><span style="color:red"><?php print $passValidateError ?></span>  
                    </div> 

                </div>
                
                
                <br/>
              
                <div class="col-sm-6">
                    <input class="btn btn-primary" type = "submit" name="submit" value = "Submit" class="button" />
                    <button class="btn btn-primary" type="reset" name="btnClear" value="Reset" class="button">Clear</button>
                </div>

            </form>
        
        
    </body>
</html>