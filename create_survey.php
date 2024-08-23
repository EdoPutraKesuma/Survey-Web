<?php
include 'db.php';

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
        header('Location: list_surveys.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Buat Survey</title>
</head>

<body>
    <div class="container">
        <h2>Buat Survey Baru</h2>
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
</body>

</html>