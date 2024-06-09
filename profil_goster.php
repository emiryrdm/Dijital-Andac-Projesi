<?php
// Veritabanı bağlantısı
include "db.php";
session_start();

// Gelen öğrenci numarasını al
$ogrenci_no = $_GET['ogrenci_no'];
$no = $_SESSION['ogrenci_no'];

// Öğrenci profili sorgusu
$sql_profile = "SELECT ad, soyad, profil_fotografi FROM ogrenci WHERE ogrenci_no = ?";
$stmt_profile = $conn->prepare($sql_profile);
$stmt_profile->bind_param("s", $ogrenci_no);
$stmt_profile->execute();
$stmt_profile->bind_result($ad, $soyad, $profil_fotografi);
$stmt_profile->fetch();
$stmt_profile->close();

// Öğrenci metinleri sorgusu
$sql_texts = "SELECT 
                 ogrenci.ad AS yazan_ad, 
                 ogrenci.soyad AS yazan_soyad, 
                 metin.yazi 
              FROM 
                 metin 
                 JOIN ogrenci_metin ON metin.metin_id = ogrenci_metin.metin_id 
                 JOIN ogrenci ON metin.yazan_ogrenci_no = ogrenci.ogrenci_no 
              WHERE 
                 ogrenci_metin.ogrenci_no = ?";
$stmt_texts = $conn->prepare($sql_texts);
$stmt_texts->bind_param("i", $ogrenci_no);
$stmt_texts->execute();

$result_texts = $stmt_texts->get_result();
$texts = [];
while ($row = $result_texts->fetch_assoc()) {
    $texts[] = $row;
}
$stmt_texts->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Göster</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
        }
        h2, h3 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }
        .card-img-top {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            max-height: 300px;
            object-fit: cover;
        }
        .card-body {
            padding: 20px;
            background: linear-gradient(to right, #e0c3fc 0%, #8ec5fc 100%);
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }
        .card-title {
            font-size: 1.75em;
            margin-bottom: 10px;
            color: #007bff;
        }
        .card-text {
            font-size: 1.1em;
            color: #343a40;
        }
        .text-center {
            text-align: center;
        }
        .mt-4 {
            margin-top: 1.5rem;
        }
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background-color: #6a11cb;
            border-color: #6a11cb;
        }
        .btn-primary:hover {
            background-color: #8e54e9;
            border-color: #8e54e9;
        }
        .form-control {
            border-radius: 10px;
        }
        label {
            color: #343a40;
        }
    </style>
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-6">
      <h2 class="text-center mb-4">Öğrenci Profili</h2>
      <div class="card">
        <?php if (!empty($profil_fotografi)) : ?>
          <img src="<?php echo "http://localhost/2024_YBS_ANDAC/ogrenci_profil_fotograflari/".$ogrenci_no; ?>" class="card-img-top" alt="Profil Fotoğrafı">
        <?php endif; ?>
        <div class="card-body">
          <h5 class="card-title"><?php echo $ad . " " . $soyad; ?></h5>
        </div>
      </div>
    </div>
  </div>
  
  <?php if (!empty($texts)): ?>
    <div class="mt-4">
      
      <?php foreach ($texts as $text): ?>
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title"><?php echo $text['yazan_ad'] . " " . $text['yazan_soyad']; ?></h5>
            <p class="card-text"><?php echo $text['yazi']; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-center mt-4">Bu öğrenci için yazılmış metin bulunmamaktadır.</p>
  <?php endif; ?>
  
  <!-- New Text Entry Form -->
  <div class="row justify-content-center mt-4">
    <div class="col-6">
      <h3 class="text-center mb-4">Yeni Metin Ekle</h3>
      <form action="add_text.php" method="post">
        <div class="form-group">
          <label for="yazi">Metin:</label>
          <textarea class="form-control" id="yazi" name="yazi" rows="4" required></textarea>
        </div>
        <input type="hidden" name="ogrenci_no" value="<?php echo $ogrenci_no; ?>">
        <button type="submit" class="btn btn-primary">Ekle</button>
      </form>
    </div>
  </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
