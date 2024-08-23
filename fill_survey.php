<?php
include 'db.php';

// Ambil ID survey dari URL
$survey_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil informasi survey dan pertanyaannya
$survey_info = $conn->query("SELECT title FROM surveys WHERE id = $survey_id")->fetch_assoc();
$questions = $conn->query("SELECT id, question_text FROM questions WHERE survey_id = $survey_id");

// Handle jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['answers'], $_POST['name'])) {
    // Memasukkan user baru ke dalam tabel 'users'
    $name = $conn->real_escape_string($_POST['name']);
    $user_sql = "INSERT INTO users (name) VALUES ('$name')";
    if ($conn->query($user_sql)) {
        $user_id = $conn->insert_id;  // Dapatkan user_id dari user yang baru dimasukkan

        // Menyimpan jawaban ke database
        $errors = false;
        foreach ($_POST['answers'] as $question_id => $answer) {
            $question_id = intval($question_id);
            $answer = $conn->real_escape_string($answer);
            $insert_sql = "INSERT INTO responses (survey_id, user_id, question_id, answer) VALUES ('$survey_id', '$user_id', '$question_id', '$answer')";
            if (!$conn->query($insert_sql)) {
                $errors = true;
                echo "SQL Error: " . $conn->error . "<br>";
            }
        }

        // Pemberitahuan jika berhasil atau jika ada error
        if (!$errors) {
            echo "<script>alert('Responses submitted successfully.'); window.location.href='index.php';</script>"; // Redirect ke index.php
        } else {
            echo "<script>alert('Failed to submit responses. See errors above for details.');</script>";
        }
    } else {
        echo "Error inserting user: " . $conn->error;
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
    <title>Isi Survey</title>
</head>

<body>
    <div class="container">
        <h2><?= htmlspecialchars($survey_info['title']) ?></h2>
        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nama:</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <?php while ($question = $questions->fetch_assoc()): ?>
                <div class="mb-3">
                    <label for="answer_<?= $question['id'] ?>" class="form-label"><?= htmlspecialchars($question['question_text']) ?></label>
                    <input type="text" class="form-control" name="answers[<?= $question['id'] ?>]" id="answer_<?= $question['id'] ?>" required>
                </div>
            <?php endwhile; ?>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>

</html>