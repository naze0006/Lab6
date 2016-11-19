<?php

include_once 'EntityClass_Lib.php';

class DataAccessObject {

    private $pdo;

    function __construct($iniFile) {
        $dbConnection = parse_ini_file($iniFile);
        extract($dbConnection);
        $this->pdo = new PDO($dsn, $user, $password);
    }

    function __destruct() {
        $this->pdo = null;
    }

    public function getSemesters() {
        $semesters = array();
        $sql = "SELECT SemesterCode, Term, Year FROM Semester";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        foreach ($stmt as $row) {
            $semester = new Semester($row['SemesterCode'], intval($row['Year']), $row['Term']);
            $semesters[] = $semester;
        }

        //usort($semesters, "Semester::compareSemester");
        return $semesters;
    }

    public function getCourseBySemester($semester) {
        $courses = array();
        $sql = "SELECT Course.CourseCode Code, Title, WeeklyHours"
                . " FROM Course INNER JOIN CourseOffer ON Course.CourseCode = CourseOffer.CourseCode" . " WHERE CourseOffer.SemesterCode = :semesterCode";
        $stmt = $this->pdo->prepare($sql);
        $code = $semester->getSemesterCode();
        $stmt->execute(['semesterCode' => $code]);

        foreach ($stmt as $row) {
            $course = new Course($row['Code'], $row['Title'], $row['WeeklyHours']);
            $courses[] = $course;
        }
        return $courses;
    }

    
    public function getStudentById($studentId)
    {
        $student = null;
        $sql = "SELECT StudentId, Name, Phone FROM Student WHERE StudentID = :studentId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['studentId' => $studenId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $student = new Student($row['StudentId'], $row['Name'], $row['Phone']);
            $student->getCurrentRegistrations($this->getStudentRegistrations($student));
        }
        return $student;
        
    }


    public function getStudentByIdAndPassword($studenId, $password) {
        $student = null;
        $sql = "SELECT StudentId, Name, Phone FROM Student WHERE StudentId = :studentId AND Password = :password";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['studentId' => $studenId, 'password' => $password]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $student = new Student($row['StudentId'], $row['Name'], $row['Phone']);
            $student->getCurrentRegistrations($this->getStudentRegistrations($student));
        }
        return $student;
    }

    public function getStudentRegistrations($student) {
        $registrations = array();
        $sql="SELECT Course.CourseCode, Course.Title, Course.WeeklyHours, Semester.SemesterCode, Semester.Year, Semester.Term  
            From Course INNER Join Registration on Course.CourseCode=Registration.CourseCode INNER Join Semester on Semester.SemesterCode=Registration.SemesterCode where Registration.StudentId=:studentID";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['studentID' => $student->getStudentId()]);

        foreach ($stmt as $row) {
            $course = new Course($row['CourseCode'], $row['Title'], $row['WeeklyHours']);
            $semester = new Semester($row['SemesterCode '], $row['Year'], $row['Term']);
            $courseOffer = new CourseOffer($course, $semester);
            $registrations[] = $courseOffer;
        }
        return $registrations;
    }

    public function saveStudent($studentId, $name, $phone, $password) {
        $sql = "INSERT INTO Student VALUES( :studentId, :name, :phone, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['studentId' => $studentId, 'name' => $name, 'phone' => $phone, 'password' => $password]);
    }
    
    public function saveRegistration($studentId, $courseCode, $semesterCode) {
        $sql = "INSERT INTO Registration VALUES( :studentId, :courseCode, :semesterCode)";
        $stmt = $this->pdo->prepare($sql);
        //foreach ($courseOffers as $courseOffer) {
        $stmt->execute(['studentId' => $studentId, 'courseCode' => $courseCode, 'semesterCode' => $semesterCode]);
        //}
    }
    
    public function saveRegistrations($courseOffers, $student)
    {
        $sql = "INSERT INTO Registration VALUES ( :stidentId, :courseCode, :semesterCode)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($courseOffers as $courseOffer) {
            $stmt->execute(['studentId' => $student->getStudentId(), 'courseCode' => $courseOffer->getCourse(), 'semesterCode' => $courseOffer->getSemester()]);
        }
    }

    public function deleteRegistrations($courseOffers, $student) {
        $sql = "DELETE FROM Registration WHERE StudentId=:studentId, CourseCode=:courseCode, $semesterCode=:semesterCode";
        $stmt = $this->pdo->prepare($sql);
        foreach ($courseOffers as $courseOffer) {
            $stmt->execute(['studentId' => $student->getStudentId(), 'courseCode' => $courseOffer->getCourse()->getCourseCode()]);
        }
    }

    public function studentExists($studentId) {
        $sql = "SELECT COUNT(StudentId) AS num FROM Student WHERE StudentId = :studentId";
        $stmt = $this->pdo->prepare($sql);

        //Bind the provided username to our prepared statement.
        $stmt->bindValue(':studentId', $studentId);

        //Execute.
        $stmt->execute();

        //Fetch the row.
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['num'] > 0) {
            die("The Student ID has already exist!");
        }
    }

}

?>