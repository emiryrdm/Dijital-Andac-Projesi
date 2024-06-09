<?php
// Veritabanı bağlantısı
include "db.php";
session_start();

// Form verilerini al
$yazi = $_POST['yazi'];
$ogrenci_no = $_POST['ogrenci_no'];
$yazan_ogrenci_no = $_SESSION['ogrenci_no']; // Oturumu açık olan öğrenci

// Yeni metin ekle
$sql_add_text = "INSERT INTO metin (yazi, yazan_ogrenci_no) VALUES (?, ?)";
$stmt_add_text = $conn->prepare($sql_add_text);
$stmt_add_text->bind_param("ss", $yazi, $yazan_ogrenci_no);
$stmt_add_text->execute();
$metin_id = $stmt_add_text->insert_id; // Eklenen metnin ID'sini al
$stmt_add_text->close();

// ogrenci_metin tablosuna ilişki ekle
$sql_add_relation = "INSERT INTO ogrenci_metin (ogrenci_no, metin_id) VALUES (?, ?)";
$stmt_add_relation = $conn->prepare($sql_add_relation);
$stmt_add_relation->bind_param("ii", $ogrenci_no, $metin_id);
$stmt_add_relation->execute();
$stmt_add_relation->close();

$conn->close();

// Profil sayfasına yönlendir
header("Location: profil_goster.php?ogrenci_no=" . $ogrenci_no);
exit();
?>
