-- ============================================================================
-- OPTIONAL: the five demo students from Step 9 of the Teaching Guide, one for
-- every grade A to E.  Run it AFTER student_grading.sql.  Skip it if you want
-- to key the records in through the form instead.
-- Total marks and grade follow the same rules as the program:
--   total = Test 1 + Test 2 + Final;  80+ A, 65+ B, 50+ C, 40+ D, otherwise E.
-- ============================================================================

USE student_grading;

INSERT INTO results
    (student_name, matric_no, subject_name, subject_code, test1, test2, final_exam, total_marks, grade)
VALUES
    ('Aisyah Binti Rahman',       'DCRM2301', 'Web Programming With PHP', 'CRM 1963', 18,  27,   45,   90, 'A'),
    ('Daniel Lee Wei Jie',        'DCRM2302', 'Web Programming With PHP', 'CRM 1963', 15,  22.5, 33.5, 71, 'B'),
    ('Kumaravel A/L Subramaniam', 'DCRM2303', 'Web Programming With PHP', 'CRM 1963', 12,  18,   28,   58, 'C'),
    ('Nur Farhana Binti Ismail',  'DCRM2304', 'Web Programming With PHP', 'CRM 1963',  9,  14,   20,   43, 'D'),
    ('Tan Mei Ling',              'DCRM2305', 'Web Programming With PHP', 'CRM 1963',  5,   9,   15,   29, 'E');
