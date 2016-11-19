<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>Course Registration</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    </head>

    <body>

        <?php
        include "./Lab6Common/Header.php";
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

        //$semester = $_POST['semester'];
        $errorMsg = '';
        $student = $_SESSION["student"];
        //$hoursWarning = FALSE;


        $dao = new DataAccessObject(INI_FILE_PATH);
        $semesters = $dao->getSemesters();

        $studRegistrations = $dao->getStudentRegistrations($student);

        $student->setCurrentRegistrations($studRegistrations);
        //$curRegis = array();
        $curRegis = $student->getCurrentRegistrations();

        if (!isset($_SESSION["selectedSemester"])) {
            $selectedSemester = $semesters[0];
            $_SESSION["selectedSemester"] = $selectedSemester;
        } else {
            $selectedSemester = $_SESSION["selectedSemester"];
        }

        if ($semesterChangedFlag) {
            foreach ($semesters as $semester) {
                $currentSemesterCode = $semester->getSemesterCode();
                if ($currentSemesterCode == $sltSemesterCode) {
                    $selectedSemester = $semester;
                }
            }
            $_SESSION["selectedSemester"] = $selectedSemester;
        }

        $courses = $dao->getCourseBySemester($selectedSemester);
        $_SESSION["courses"] = $courses;

        if (isset($btnSubmit)) {
            $selections = array();
            
            foreach ($courses as $course) {
                $checkboxName = "checkbox_" . $course->getCourseCode();
                if (isset(${$checkboxName})) {
                    $selections[] = $course;
                }
                
            }

            if (sizeof($selections) > 0)
            {
                foreach ($selections as $course) {
                    $hours = $hours + $course->getWeeklyHours();
                }
            }
            else 
            {
                $errorMag = "You have to select at least one course";
            }
            $totalHours = $hours + ($student->getTotalWeeklyHoursForSemester($selectedSemester));
            if ($totalHours <= 16) {

                foreach ($selections as $course)
                {
                    $dao->saveRegistration($student->getStudentId(), $course->getCourseCode(), $selectedSemester->getSemesterCode());
                    //$course->getCourseCode()
                }
            } else {
                $errorMag = "Your selection exceeded max weekly hours";
            }
        }
        ?>

        <div class="container-fluid">

            <div class="row vertical-margin">
                <div class="col-md-12">

                    <p>Welcome <span style='font-weight: bolder'><?php print $student->getName(); ?></span>! (not you? change user<a href=""></a>)</p>
                    <p>You have registered <span style="font-weight: bolder"> <?php print $student->getTotalWeeklyHoursForSemester($selectedSemester); ?></span> hours for the selected course</p>
                    <p>You can register <span style="font-weight: bolder"><?php print (MAX_WEEKLY_HOURS - $student->getTotalWeeklyHoursForSemester($selectedSemester)); ?></span> more hours of course(s) for the semester</p>
                    <p>Please note that the course you have registered will not be displayed in the list below </p>
                </div>          
            </div>
            <form class="form-horizontal" method="post" id="course-selection-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="row vertical-margin">
                    <div class="col-md-3 text-center col-md-offset-9">
                        <select name="sltSemesterCode" class="form-control" onchange="onSemesterChanged()">

<?php
foreach ($semesters as $semester) {
    $semesterCode = $semester->getSemesterCode();
    $sem = $semester->getYear() . ' ' . $semester->getTerm();
    if ($semester == $selectedSemester) {

        print "<option value='$semesterCode' selected>$sem</option>";
    } else {
        print "<option value='$semesterCode'>$sem</option>";
    }
}
?>
                        </select>
                        <input type="hidden" id="semesterChangedFlag" name="semesterChangedFlag" value="" />
                    </div>
                </div>
                <div class="row vertical-margin">
                    <div class="col-md-12">
                        <span class="error"><?php print $errorMag; ?></span>
                    </div> 
                </div>
                <div class="row vertical-margin">
                    <div class="col-md-12">
                        <table class="table-bordered col-md-12">
                            <tr>
                                <th>Code</th>
                                <th>Course Title</th>
                                <th>Hours</th>
                                <th>Select</th>
                            </tr>
<?php
foreach ($courses as $course) {
    $coursecode = $course->getCourseCode();
    $coursetitle = $course->getTitle();
    $courseweeklyhours = &$course->getWeeklyHours();
    print '<tr>
                                <td> ' . $coursecode . '</td>
                                <td>' . $coursetitle . '</td>
                                <td>' . $courseweeklyhours . '</td>
                                <td><input type="checkbox" name="checkbox_' . $coursecode . '"></td>
                            </tr>';
}
?>
                        </table>
                    </div>
                </div>    
                <div class="col-sm-6">
                    <input class="btn btn-primary" type = "submit" name="btnSubmit" value = "Submit" class="button" />
                    <button class="btn btn-primary" type="reset" name="btnClear" value="Reset" class="button">Clear</button>
                </div>
            </form>
        </div>
    </body>
</html>
<?php include("./Lab6Common/Footer.php"); ?>