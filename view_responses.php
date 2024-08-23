<?php
include 'db.php';

$survey_id = $_GET['id'] ?? 0;
// Ambil informasi tentang survey
$survey_info = $conn->query("SELECT title FROM surveys WHERE id = '$survey_id'")->fetch_assoc();

// Query untuk mendapatkan nama pengguna dari tabel users berdasarkan respons yang ada
$responses = $conn->query("SELECT DISTINCT users.name, responses.user_id FROM responses JOIN users ON responses.user_id = users.id WHERE responses.survey_id = $survey_id");

// Debugging: Cek apakah ada data yang ditemukan
if ($responses->num_rows > 0) {
    echo "<p>Jumlah Responden: {$responses->num_rows} respon</p>";
} else {
    echo "<p>No responses found for this survey.</p>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Daftar Jawaban Survey</title>
</head>

<body>
    <div class="container">
        <h2>List Jawaban : <?= htmlspecialchars($survey_info['title']) ?></h2>
        <ul class="list-group">
            <?php while ($response = $responses->fetch_assoc()): ?>
                <li class="list-group-item">
                    <a href="view_individual.php?id=<?= $survey_id ?>&user_id=<?= $response['user_id'] ?>">Lihat Jawaban dari <?= htmlspecialchars($response['name']) ?></a>
                </li>
            <?php endwhile; ?>
        </ul>
        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a> <!-- Tombol Back ke Home -->
        </div>
    </div>
</body>

</html>