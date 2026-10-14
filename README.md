# Student Grading System (classroom version)

CRM 1963 Web Programming with PHP, Final Assessment.

A small PHP and MySQL project in the same style as the course notes: plain `mysqli`, one file per page, prepared statements, and a session for the login. The design is plain HTML on purpose.

This is the finished project of the **Student Grading System Teaching Guide** (Steps 1 to 15). Every PHP file here is exactly what the guide has the students type, so it can be used as the answer key.

## Set up

1. **Copy the folder.** Put `student-grading` in your `~/Herd` folder, then open `http://student-grading.test`. (XAMPP: put it in `htdocs` and open `http://localhost/student-grading/`.)
2. **Create the database.** Run `database/student_grading.sql` once. In phpMyAdmin that is the **SQL** tab, paste the file, click **Go**. From a terminal: `mysql -u root -p < database/student_grading.sql`.
   Optional: run `database/sample_data.sql` afterwards to get five demo students, one for each grade A to E.
3. **Give the app your MySQL password.** Open `db.php`. Line 8 connects as `root` with an empty password, which is the XAMPP default. If your MySQL has a password, type it between the empty quotes. This is the only place that knows it.
4. **Log in.** Username `admin`, password `admin123`.

To start again from nothing, run `DROP DATABASE student_grading;` and then run `database/student_grading.sql` again.

## Marks and grades

Marks are keyed in out of their weight: Test 1 out of 20, Test 2 out of 30, Final Examination out of 50. The total is the plain sum, out of 100.

| Total marks | Grade |
| --- | --- |
| 80 or more | A |
| 65 up to 80 | B |
| 50 up to 65 | C |
| 40 up to 50 | D |
| under 40 | E |

A total of 79.5 is B because it is below 80. The total and the grade are worked out by the program and saved in the database.

## What each file does

| File | Job |
| --- | --- |
| `login.php` | Checks the username and password against the `users` table and starts the session |
| `logout.php` | Ends the session |
| `index.php` | The Records page: all results, Search, and the View, Edit and Delete buttons |
| `add.php` | The form to key in a result (Submit) |
| `view.php` | One result in full |
| `edit.php` | Change a saved result |
| `delete.php` | Delete a result (only accepts a form, never a link) |
| `db.php` | The database connection, included by every page that needs it |
| `auth.php` | Sends a visitor who is not logged in back to the login page |
| `functions.php` | `getGrade()` and `validateResult()`, used by Add and Edit |
| `form.php` | The form shared by Add and Edit |
| `header.php`, `footer.php` | The top and bottom of every page |
| `database/` | The SQL files |

## Good to know

- The database is called `student_grading`, so it never clashes with the `grading_system` database of the other project.
- Everything typed by a user goes through prepared statements (into the database) or `htmlspecialchars()` (onto the page).
- Passwords are stored as hashes made by `password_hash()`. To add another user, make a hash the same way as in Step 4 of the guide.
- Left out on purpose: a sign-up page, a limit on failed logins, and page numbers for long lists. There is one results table; the three-table design with a separate stylesheet is in `grading-system/`.
