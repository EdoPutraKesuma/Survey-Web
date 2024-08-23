<?php
include 'db.php';

// Proses pembuatan survey baru
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $sql = "INSERT INTO surveys (title, description) VALUES ('$title', '$description')";

    if ($conn->query($sql) === TRUE) {
        $survey_id = $conn->insert_id;
        for ($i = 1; $i <= 5; $i++) {
            $question = $conn->real_escape_string($_POST["question$i"]);
            $sql = "INSERT INTO questions (survey_id, question_text) VALUES ('$survey_id', '$question')";
            $conn->query($sql);
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Mengambil daftar survey yang ada termasuk deskripsi
$surveys = $conn->query("SELECT id, title, description FROM surveys");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Survey Portal</title>
</head>

<body>
    <div class="container mt-5">
        <div class="form-section">
            <h1>Buat Survey</h1>
            <form method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Survey:</label>
                    <input type="text" class="form-control" name="title" id="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Survey:</label>
                    <textarea class="form-control" name="description" id="description" required></textarea>
                </div>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="mb-3">
                        <label for="question<?= $i ?>" class="form-label">Pertanyaan <?= $i ?>:</label>
                        <input type="text" class="form-control" name="question<?= $i ?>" id="question<?= $i ?>" required>
                    </div>
                <?php endfor; ?>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="survey-list-section">
            <h2>Daftar Survey</h2>
            <?php
            if ($surveys->num_rows > 0) {
                echo "<ul class='list-group'>";
                while ($row = $surveys->fetch_assoc()) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                    echo "<div><strong>" . htmlspecialchars($row['title']) . "</strong><br>";
                    echo "<small>" . htmlspecialchars($row['description']) . "</small></div>";
                    echo "<div>";
                    echo "<a href='fill_survey.php?id=" . $row["id"] . "' class='btn btn-primary me-2'>Isi Survey</a>";
                    echo "<a href='view_responses.php?id=" . $row["id"] . "' class='btn btn-secondary'>Lihat Jawaban Survey</a>";
                    echo "</div>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<div class='alert alert-warning'>No surveys found.</div>";
            }
            ?>
        </div>
    </div>
</body>

</html>