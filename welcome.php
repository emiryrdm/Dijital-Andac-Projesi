<?php
session_start();

if (!isset($_SESSION['ogrenci_no'])) {
    header("Location: login.html");
    exit();
}

// Veritabanı bağlantısı
include "db.php";

$ogrenci_no = $_SESSION['ogrenci_no'];

// Öğrenci bilgilerini ve profil fotoğrafının dosya adını veritabanından çekme
$sql = "SELECT ad, soyad, profil_fotografi FROM ogrenci WHERE ogrenci_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ogrenci_no);
$stmt->execute();
$stmt->bind_result($ad, $soyad, $profil_fotografi);
$stmt->fetch();
$stmt->close();
$conn->close();

// Profil fotoğrafının dosya yolu
$dosya_yolu = "http://localhost/2024_YBS_ANDAC/ogrenci_profil_fotograflari/";

// Eğer profil fotoğrafı varsa, ekrana yazdır
if (!empty($profil_fotografi)) {
    $profil_fotografi_yolu = $dosya_yolu . $profil_fotografi;
    echo "<div class='user-info'>";
    echo "<img src='$profil_fotografi_yolu' alt='$ad $soyad'>";
    echo "<p>$ad $soyad</p>";
    echo "<button type='button' onclick=\"window.location.href = 'logout.php'\">Çıkış</button>";
    echo "</div>";
} else {
    echo "Profil fotoğrafı bulunamadı.";
}

include "db.php";

$sql2 = "SELECT ad, soyad , ogrenci_no FROM ogrenci";
$result2 = $conn->query($sql2);

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        img {
            height: 100px;
            width: 100px;
        }

        .user-info {
            position: fixed;
            top: 10px;
            right: 10px;
            text-align: right;
        }
        #studentDropdown {
            margin-top: 50px;
        }
       
    </style>
</head>
<body>



</body>



</html>


<div class="container">
  <div class="row justify-content-center">
    <div class="col-6">
      <h2 class="text-center mb-4">Katılımıcı Listesi</h2>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Ad</th>
            <th>Soyad</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            while ($row = $result2->fetch_assoc()) {
              echo "<tr>";
              echo "<td>".$row['ad']."</td>";
              echo "<td>".$row['soyad']."</td>";
              echo "<td><a href='profil_goster.php?ogrenci_no=".$row['ogrenci_no']."'>"."Profil"."</a></td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
            margin: 0;
            padding: 20px;
        }
        img {
            height: 100px;
            width: 100px;
            border-radius: 50%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }
        .user-info {
            position: fixed;
            top: 10px;
            right: 10px;
            text-align: right;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
        .user-info p {
            margin: 0;
            font-weight: bold;
            color: #333;
        }
        .user-info button {
            margin-top: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .user-info button:hover {
            background-color: #0056b3;
        }
        .container {
            margin-top: 70px; /* Tabloyu aşağıya kaydırır */
        }
        .table {
            margin: 0 auto; /* Tabloyu yatayda ortalar */
            border-collapse: collapse;
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }
    </style>