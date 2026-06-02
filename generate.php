<?php
// 1. Database connection
$pdo = new PDO('mysql:host=localhost;dbname=Eval_PHP2;charset=utf8', 'root', '');

// 2. Clear old data to start fresh (Order matters because of Foreign Keys!)
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
$pdo->exec("TRUNCATE TABLE student_sports;");
$pdo->exec("TRUNCATE TABLE students;");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

// 3. Define your fixed assets (IDs from your pre-populated tables)
$schoolIds = [1, 2, 3]; // École A, B, C
$sportIds  = [1, 2, 3, 4, 5]; // boxe, judo, football, natation, cyclisme [cite: 45, 46, 47, 48, 49]

// Prepared statements for fast execution
$insertStudent = $pdo->prepare("INSERT INTO students (name, school_id) VALUES (:name, :school_id)");
$insertSport   = $pdo->prepare("INSERT INTO student_sports (student_id, sport_id) VALUES (:student_id, :sport_id)");

// 4. Loop through each school
foreach ($schoolIds as $schoolId) {
    
    // 5. Random number of students for this school (e.g., between 10 and 30) [cite: 59]
    $totalStudents = rand(10, 30);
    
    // 6. Loop to create the students
    for ($i = 1; $i <= $totalStudents; $i++) {
        // Generate a placeholder name like "Élève 1 (École 1)"
        $studentName = "Eleve_" . $i . "_School_" . $schoolId;
        
        // 7. Insert student
        $insertStudent->execute([
            ':name' => $studentName,
            ':school_id' => $schoolId
        ]);
        
        // Grab the ID MySQL just generated for this student
        $currentStudentId = $pdo->lastInsertId();
        
        // 8. Apply the 0-3 Sports Rule [cite: 60]
        $numberOfSports = rand(0, 3); 
        
        if ($numberOfSports > 0) {
            // 9. Shuffle the available sport IDs and pick the required amount
            $shuffledSports = $sportIds;
            shuffle($shuffledSports);
            $selectedSports = array_slice($shuffledSports, 0, $numberOfSports);
            
            // Link the student to the selected sports in the junction table
            foreach ($selectedSports as $sportId) {
                $insertSport->execute([
                    ':student_id' => $currentStudentId,
                    ':sport_id' => $sportId
                ]);
            }
        }
    }
}

// Redirect back to index.php once generation is complete
header('Location: index.php');
exit;