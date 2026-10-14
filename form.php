<?php
// form.php - the form shared by add.php and edit.php.
// The page that includes it sets $button_name and $button_label first, so the same form shows
// "Submit" on the Add page and "Save Changes" on the Edit page (see the last button of the form).
//
// How to read the boxes below:
//  - name="student_name" is the label PHP uses to find the value: $_POST['student_name'].
//  - value="..." is what is shown inside the box. It holds what was typed before (Add page) or the
//    saved value (Edit page), so a warning does not empty the form.
//  - htmlspecialchars() makes that text safe inside value="...": a typed " or < cannot break the page.
//  - maxlength="100" stops typing after 100 characters (the same limit as the database column).
//  - method="post" sends the values to this same page when the button is pressed (there is no action=).
?>
<form method="post">
    Student name :
    <input type="text" name="student_name" maxlength="100" value="<?php echo htmlspecialchars($student_name); ?>"><br><br>
    Matric number :
    <input type="text" name="matric_no" maxlength="20" value="<?php echo htmlspecialchars($matric_no); ?>"><br><br>
    Subject name :
    <input type="text" name="subject_name" maxlength="100" value="<?php echo htmlspecialchars($subject_name); ?>"><br><br>
    Subject code :
    <input type="text" name="subject_code" maxlength="20" value="<?php echo htmlspecialchars($subject_code); ?>"><br><br>
    Test 1 (20%) :
    <input type="text" name="test1" value="<?php echo htmlspecialchars($test1); ?>"> out of 20<br><br>
    Test 2 (30%) :
    <input type="text" name="test2" value="<?php echo htmlspecialchars($test2); ?>"> out of 30<br><br>
    Final examination (50%) :
    <input type="text" name="final_exam" value="<?php echo htmlspecialchars($final_exam); ?>"> out of 50<br><br>
    <input type="submit" name="<?php echo $button_name; ?>" value="<?php echo $button_label; ?>">
</form>
