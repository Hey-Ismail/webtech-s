<?php
header('Content-Type: application/json; charset=utf-8');

$students = [
    [
        'name' => 'Ayesha Khan',
        'id' => '2023001',
        'department' => 'Computer Science',
        'cgpa' => '3.87',
    ],
    [
        'name' => 'Rahim Uddin',
        'id' => '2023002',
        'department' => 'Software Engineering',
        'cgpa' => '3.64',
    ],
    [
        'name' => 'Nusrat Jahan',
        'id' => '2023003',
        'department' => 'Information Technology',
        'cgpa' => '3.92',
    ],
];

echo json_encode([
    'students' => $students,
]);
