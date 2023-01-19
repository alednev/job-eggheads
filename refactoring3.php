<?php

$mysqli = new mysqli('localhost', 'root', '', 'eggheads');

$catId  = 1;
$result = [];

$questionsQ = $mysqli->query('SELECT * FROM orders WHERE city_id=' . $catId);

while ($question = $questionsQ->fetch_assoc()) {
    $userQ = $mysqli->query('SELECT name, phone FROM users WHERE id=' . $question['user_id']);
    $user  = $userQ->fetch_assoc();

    $result[] = ['question' => $question, 'user' => $user];
    $userQ->free();

}

$questionsQ->free();

echo '<pre>';
print_r($result);
echo '</pre>';