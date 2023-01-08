<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "GET":
        $sql = "SELECT * FROM persons";
        $path = explode('/', $_SERVER['REQUEST_URI']);
        if (isset($path[3]) && is_numeric($path[3])) {
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($result);
        break;
    case "POST":
        $person = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO persons (id, name, email, avatar ) VALUES (null, :name, :email, :avatar)";
        $stmt = $conn->prepare($sql);
        $created = date('Y-m-d H:i:s');
        $stmt->bindParam(':name', $person['name']);
        $stmt->bindParam(':email', $person['email']);
        $stmt->bindParam(':avatar', $person['avatar']);

        if ($stmt->execute()) {
            echo $response = ['status' => 1, 'message' => 'Person created successfully.'];
        } else {
            echo $response = ['status' => 0, 'message' => 'Person could not be created.'];
        }

        echo json_encode($response);
        break;

    case "PUT":
        $person = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE persons SET name = :name, email = :email, avatar = :avatar WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $updated = date('Y-m-d H:i:s');
        $stmt->bindParam(':name', $person['name']);
        $stmt->bindParam(':email', $person['email']);
        $stmt->bindParam(':avatar', $person['avatar']);
        $stmt->bindParam(':id', $person['id']);

        if ($stmt->execute()) {
            echo $response = ['status' => 1, 'message' => 'Person updated successfully.'];
        } else {
            echo $response = ['status' => 0, 'message' => 'Person could not be updated.'];
        }
        echo json_encode($response);
        break;

    case "DELETE":
        $path = explode('/', $_SERVER['REQUEST_URI']);
        $id = $path[3];
        $sql = "DELETE FROM persons WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo $response = ['status' => 1, 'message' => 'Person deleted successfully.'];
        } else {
            echo $response = ['status' => 0, 'message' => 'Person could not be deleted.'];
        }
        echo json_encode($response);
        break;
}
