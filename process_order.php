<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['cart']) || empty($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid order data']);
    exit;
}


$conn = new mysqli(
    "localhost",
    "u629988973_Honey",
    "Oman2020oman*",
    "u629988973_Honey"
);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit;
}

$conn->begin_transaction();

try {
    $checkStmt  = $conn->prepare(
        "SELECT stock FROM products WHERE name = ? FOR UPDATE"
    );
    $updateStmt = $conn->prepare(
        "UPDATE products SET stock = stock - ? WHERE name = ?"
    );

    foreach ($data['cart'] as $name => $item) {
        $qty = (int)$item['qty'];
        if ($qty <= 0) {
            throw new Exception("Invalid quantity for $name");
        }

     
        $checkStmt->bind_param("s", $name);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Product '$name' not found");
        }

        $row = $result->fetch_assoc();
        if ($row['stock'] < $qty) {
            throw new Exception("Not enough stock for '$name'");
        }

      
        $updateStmt->bind_param("is", $qty, $name);
        $updateStmt->execute();
    }

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
