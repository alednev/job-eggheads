<?php

$mysqli = new mysqli('localhost', 'root', '', 'eggheads');

// preparing
$catId  = 1;
$result = [];

// getting all questions
$questionsQ = $mysqli->prepare('SELECT * FROM orders WHERE city_id = ?');
$questionsQ->bind_param('i', $catId);
$questionsQ->execute();

// working with questions
$questions = $questionsQ->get_result()->fetch_all(MYSQLI_ASSOC);
$questionsQ->free_result();

// getting all question Ids in one array
$questionsIds    = array_map(fn($item) => $item['user_id'], $questions);
$questionsIdsCnt = count($questionsIds);

if ($questionsIdsCnt > 0) {
    // preparing for users
    $placeholders = implode(',', array_fill(0, $questionsIdsCnt, '?'));
    $bindStr      = str_repeat('i', $questionsIdsCnt);

    // getting users
    $userQ = $mysqli->prepare("SELECT id, name, phone FROM users WHERE id IN ($placeholders)");
    $userQ->bind_param($bindStr, ...$questionsIds);
    $userQ->execute();

    // working with users
    $users = $userQ->get_result()->fetch_all(MYSQLI_ASSOC);
    $userQ->free();

    // forking with users and questions
    foreach ($questions as $index => $question) {
        $result[] = [
            'question' => $question,
            'user' => $users[array_search($question['user_id'], array_column($users, 'id'))],
        ];
    }
}

echo '<pre>';
print_r($result);
echo '</pre>';