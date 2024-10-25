<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $jsonFile = './customer.json';
    $customers = json_decode(file_get_contents($jsonFile), true);

    $cusId = isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : null;
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $active = isset($_POST['active']) ? (bool)$_POST['active'] : null;
    $created_date = $_POST['created_date'] ?? '';
    $contact_num = $_POST['contact_num'] ?? '';

    $newCustomer = array(
        "customer_id" => $cusId,
        "name" => $name,
        "email" => $email,
        "address" => $address,
        "active" => $active,
        "create_date" => $created_date,
        "contact_num" => $contact_num
    );

    $customers[] = $newCustomer;

    if (file_put_contents($jsonFile, json_encode($customers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
        echo json_encode(array("status" => "success", "message" => "Customer added successfully."));
    } else {
        http_response_code(500);
        echo json_encode(array("status" => "error", "message" => "Failed to save customer."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}
?>
