<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>Course Registration</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta http-equiv="x-ua-compatible" content="ie=edge"/>

    </head>
    <body>
        <?php
        include_once "./Lab6Common/EntityClass_Lib.php";
        include_once "./Lab6Common/DataAccessClass_Lib.php";
        include "./Lab6Common/Function_Lib.php";
        include "./Lab6Common/Constants.php";

        session_start();
        if (!isset($_SESSION['student'])) {
            $_SESSION['rurl'] = "CourseSelection.php";
            header("Location : Login.php");
            exit();
        }

        extract($_POST);
        $errorMsg = '';
        $student = $_SESSION["student"];

        $dao = new DataAccessObject(INI_FILE_PATH);
        $registrations = $dao->getStudentRegistrations($student);
        //$registrations = $student->getCurrentRegist$rations();
        
        if (isset($submit)) {
            $courseOffers = array();
            foreach ($courses as $course) {
                $checkboxName = "checkbox_" . $course->getCourseCode();
                if (isset(${$checkboxName})) {
                    $courseOffers = $course;
                }

                $dao->deleteRegistrations($courseOffers, $student);
            }
        }
            ?>  

            <div class="container-fluid">
                <div class="row vertical-margin">
                    <h2>Current Registrations</h2>
                    <div class="col-md-12">
                        <p>Hello <span style='font-weight: bolder'><?php print $student->getName(); ?></span>! (not you? change user<a href="Login.php"> here</a>), the followings are your current registrations</p>
                    </div>          
                </div>
            </div>
            <div class="row vertical-margin">
                <div class="col-md-12">
                    <span class="error"><?php print $errorMag; ?></span>
                </div> 
            </div>
         <form class="form-horizontal" method="post" id="current-registrations-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="row vertical-margin">
                <div class="col-md-12">      
                    
                    <table class='table table-bordered'><tr><th>Year</th>
                    <?php
                    //print ("<table class='table table-bordered'><tr><th>Year</th>");
                    print (" <th>Year</th>      
                            <th>Term</th>
                            <th>Course Code</th>
                            <th>Course Title</th>
                            <th>Course Hours</th>
                            <th>Select</th></tr> ");
                    foreach ($registrations as $courseOffer) {
                        $print = print '<td' . $courseOffer->getSemester()->getSemesterCode() . '</td>
                    <td>' . $courseOffer->getCourse()->getCourseCode() . '</td>
                    <td>' . $courseOffer->getCourse()->getTitle() . '</td>
                    <td>' . $courseOffer->getCourse()->getWeeklyHours() . '</td>
                    <tr><td><input type="checkbox"';
                        print 'name="deletecheckbox_' . $coursecode . '"></td></tr>';
                    }

                    print("</table>");
                    ?>
                </div>
                <div class="row vertical-margin">
                    <div class="col-sm-6">
                        <input class="btn btn-primary" type = "submit" name="submit" value = "Delete Selected" class="button" />
                        <button class="btn btn-primary" type="reset" name="btnClear" value="Reset" class="button">Clear</button>
                    </div>
                
                    </form>
            </div>
        </body>



    </html>

    <?php include("./Lab6Common/Footer.php"); ?>