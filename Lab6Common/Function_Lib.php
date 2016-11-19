<?php
function validateName($name)
{
    $name = trim($name);
    if(empty($name))
    {
        return "Name cannot be blank!";
    }
    else
    {
        return "";    
    }
}
function validateStudentId($id)
{
    $id = trim($id);
    if(empty($id))
    {
        return "Student ID cannot be blank!";
    }
    else
    {
        return "";
    }
}

function validatePhone($phone)
{
    if (trim($phone) != "") {

        $phoneRegExp = "/[2-9][0-9]{2}-[2-9][0-9]{2}-[0-9]{4}/";
        if (preg_match($phoneRegExp, $phone) != 1) {

            $invalidPhoneNumError = "Phone number must be in the form of nnn-nnn-nnnn";
            return $invalidPhoneNumError;
        }
        return "";
    } else {
        $phoneError = "Phone number cannot be blank";
        return $phoneError;
    }
    return "";
}

function validatePassword($pass)
{
    if (trim($pass) != "") {
        $passRegEx = "/(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])/";
        
        if (preg_match($passRegEx, $pass) != 1) {

            $invalidPassError = "Password is at least 6 characters long, contains at least one upper case, one lowercase and one digit";
            return $invalidPassError;
        }
        return "";
    } else {
        $passError = "Password cannot be blank";
        return $passError;
    }
    return "";
        
}

function validatePasswordAgain($passAgain)
{
     if (trim($passAgain) != "") {
        if (validatePassword($pass) != $passAgain) {

            $invalidPassAgainError = "Password doesn't match";
            return $invalidPassAfainError;
        }
        return "";
    } else {
        $passAgainError = "Password cannot be blank";
        return $passAgainError;
    }
    return "";
}
   
?>