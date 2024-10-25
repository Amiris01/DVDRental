<?php

$data = file_get_contents('dvd.json');
$data = json_decode($data, true);

$input = json_decode(file_get_contents('php://input'), true);
$movieId = $input['movieId'];
$returnDate = $input['returnDate'];
$cusId = $input['cusId'];
$rentDate = time();
$randomId = mt_rand(100000, 999999);

foreach($data as &$movie){
    if ($movie['dvdId'] == $movieId) {
        if (!isset($movie['rental'])) {
            $movie['rental'] = [];
        }
        $movie['rental']['rental_id'] = $randomId;
        $movie['rental']['rental_date'] = $rentDate;
        $movie['rental']['return_date'] = strtotime($returnDate);
        $movie['rental']['customer_id'] = $cusId;
        break;
    }
}

file_put_contents('dvd.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo json_encode(['status' => 'success']);
?>
