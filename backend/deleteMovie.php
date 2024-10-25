<?php
$jsonFile = './dvd.json';
$imageDirectory = '../public/images/';

$input = json_decode(file_get_contents('php://input'), true);
$dvdId = $input['dvdId'];

$data = json_decode(file_get_contents($jsonFile), true);

$movieToDelete = null;
foreach ($data as $movie) {
    if ($movie['dvdId'] == $dvdId) {
        $movieToDelete = $movie;
        break;
    }
}

if ($movieToDelete) {
    $imagePath = $imageDirectory . basename($movieToDelete['img']);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $data = array_filter($data, function ($movie) use ($dvdId) {
        return $movie['dvdId'] != $dvdId;
    });

    $data = array_values($data);

    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Movie not found']);
}
?>
