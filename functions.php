<?php
// functions.php - code that more than one page needs. Use it with:  include 'functions.php';
// A function is a named block of code. A page calls it by name and gets an answer back,
// so the same rules are written once and used by both add.php and edit.php.

// Gives the grade letter for a total between 0 and 100.
// $total in the brackets is a "parameter": the value the page hands to the function.
// return sends the answer back and ends the function at once.
// The tests run from the top and the first one that is true wins, so each test only needs
// the lowest mark of its band: 80 and above is A, otherwise 65 and above is B, and so on.
// Whatever is left (under 40) is E.
function getGrade($total) {
    if ($total >= 80) {
        return "A";
    } else if ($total >= 65) {
        return "B";
    } else if ($total >= 50) {
        return "C";
    } else if ($total >= 40) {
        return "D";
    } else {
        return "E";
    }
}

// Checks the seven values from the form.
// Gives back "" when everything is fine, or the warning to show. The first problem found ends the function.
// The seven names in the brackets are the values the page hands over.
function validateResult($student_name, $matric_no, $subject_name, $subject_code, $test1, $test2, $final_exam) {
    // == "" means the box was left empty. || means OR, so any one empty box gives this warning.
    // return ends the function here, so the checks below it are skipped.
    if ($student_name == "" || $matric_no == "" || $subject_name == "" || $subject_code == "") {
        return "Please fill in the student name, matric number, subject name and subject code.";
    }
    // strlen() counts the characters in a text. The database columns only hold 100 and 20 characters.
    if (strlen($student_name) > 100 || strlen($subject_name) > 100 || strlen($matric_no) > 20 || strlen($subject_code) > 20) {
        return "Names can be 100 characters at most. Matric number and subject code can be 20 at most.";
    }
    if ($test1 == "" || $test2 == "" || $final_exam == "") {
        return "Please enter all three marks.";
    }
    // is_numeric() is true for text that is a number, such as 15 or 22.5. The ! means NOT.
    // This check comes before the range checks, so letters such as abc are refused here
    // instead of being used in a sum.
    if (!is_numeric($test1) || !is_numeric($test2) || !is_numeric($final_exam)) {
        return "Marks must be numbers. Try again.";
    }
    // Each mark must be between 0 and the most that test can give: 20, 30 and 50.
    if ($test1 < 0 || $test1 > 20) {
        return "Test 1 must be between 0 and 20.";
    }
    if ($test2 < 0 || $test2 > 30) {
        return "Test 2 must be between 0 and 30.";
    }
    if ($final_exam < 0 || $final_exam > 50) {
        return "Final examination must be between 0 and 50.";
    }
    // Every check passed, so there is no warning to show.
    return "";
}
