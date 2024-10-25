<?php

$jsonFile = './dvd.json';
$imageDirectory = '../public/images/';

$input = $_POST;
$dvdId = $input['dvdId'];

if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['img']['tmp_name'];
    $fileName = $_FILES['img']['name'];
    $filePath = $imageDirectory . basename($fileName);

    if (move_uploaded_file($fileTmpPath, $filePath)) {
        $input['img'] = './public/images/' . $fileName;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
        exit;
    }
}

$data = json_decode(file_get_contents($jsonFile), true);

$index = null;
foreach ($data as $i => $movie) {
    if ($movie['dvdId'] == $dvdId) {
        $index = $i;
        break;
    }
}

if ($index !== null) {
    $data[$index] = [
        'dvdId' => (int)$dvdId,
        'title' => $input['title'],
        'desc' => $input['desc'],
        'release_year' => (int) date('Y', strtotime($input['release_year'])),
        'lang' => $input['lang'],
        'rental_rate' => (float) $input['rate'],
        'rating' => (float) $input['rating'] / 10,
        'img' => isset($input['img']) ? $input['img'] : $data[$index]['img'],
        'rental' => isset($data[$index]['rental']) ? $data[$index]['rental'] : null
    ];

    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Movie not found']);
}
?>
