<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "deu_ybs_2020_andac";

// MySQL bağlantısı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
// Bağlantı kontrol et
if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}

?>












