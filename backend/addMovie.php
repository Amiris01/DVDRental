<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $jsonFile = './dvd.json';
    $movies = json_decode(file_get_contents($jsonFile), true);

    $dvdId = isset($_POST['dvdId']) ? (int)$_POST['dvdId'] : null;
    $title = $_POST['title'] ?? '';
    $desc = $_POST['desc'] ?? '';
    $release_year = isset($_POST['release_year']) ? (int)substr($_POST['release_year'], 0, 4) : null;
    $lang = $_POST['lang'] ?? '';
    $rate = isset($_POST['rate']) ? (float)$_POST['rate'] : null;
    $rating = isset($_POST['rating']) ? (float)$_POST['rating'] / 10 : null;

    $img = '';
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $targetDir = "../public/images/";
        $fileName = basename($_FILES["img"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $targetFilePath)) {
                $img = './public/images/' . $fileName;
            } else {
                http_response_code(500);
                echo json_encode(array("status" => "error", "message" => "Failed to upload image."));
                exit();
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Invalid file type."));
            exit();
        }
    }

    $newMovie = array(
        "dvdId" => $dvdId,
        "title" => $title,
        "desc" => $desc,
        "release_year" => $release_year,
        "lang" => $lang,
        "rental_rate" => $rate,
        "rating" => $rating,
        "img" => $img,
        "rental" => null
    );

    $movies[] = $newMovie;

    if (file_put_contents($jsonFile, json_encode($movies, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
        echo json_encode(array("status" => "success", "message" => "Movie added successfully."));
    } else {
        http_response_code(500);
        echo json_encode(array("status" => "error", "message" => "Failed to save movie."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}
?>
