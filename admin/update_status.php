<?php
include 'conn.php';

if (isset($_POST['id'])) {
    $query_id = intval($_POST['id']);
    $sql = "UPDATE contact_query SET query_status = 1 WHERE query_id = $query_id";
    $result = mysqli_query($conn, $sql);

    echo json_encode(['success' => $result ? true : false]);
}
?>
