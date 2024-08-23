<?php
include 'db.php';

$sql = "SELECT id, title FROM surveys";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Daftar Surveys</title>
</head>

<body>
    <div class="container">
        <h2>Daftar Survey</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<div class='list-group'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='list-group-item d-flex justify-content-between align-items-center'>";
                echo "<span>" . htmlspecialchars($row['title']) . "</span>";
                echo "<div>";
                echo "<a href='fill_survey.php?id=" . $row["id"] . "' class='btn btn-primary me-2'>Isi Survey</a>";  // Tombol untuk mengisi survey
                echo "<a href='view_responses.php?id=" . $row["id"] . "' class='btn btn-secondary'>Lihat Jawaban Survey</a>";  // Tombol untuk melihat respons
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='alert alert-warning'>No surveys found.</div>";
        }
        ?>
    </div>
</body>

</html>