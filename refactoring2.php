<?php

$mysqli = new mysqli('localhost', 'root', '', 'eggheads');

// preparing
$catId  = 1;
$result = [];

// getting all data (questions and users)
$allDataQ = $mysqli->prepare('SELECT o.*, u.name as u_name, u.phone as u_phone FROM orders AS o INNER JOIN users as u ON u.id = o.user_id WHERE o.city_id = ?');
$allDataQ->bind_param('i', $catId);
$allDataQ->execute();

// working with data
$allData = $allDataQ->get_result()->fetch_all(MYSQLI_ASSOC);
$allDataQ->free_result();

foreach ($allData as $index => $dataItem) {
    // subtract user from data
    $user = [
        'name'  => $dataItem['u_name'],
        'phone' => $dataItem['u_phone'],
    ];

    // unset unnecessary fields
    unset($dataItem['u_name'], $dataItem['u_phone']);

    // combining result
    $result[] = [
        'question' => $dataItem,
        'user'     => $user,
    ];
}

echo '<pre>';
print_r($result);
echo '</pre>';