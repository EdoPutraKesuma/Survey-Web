<?php
include 'db.php';

$survey_id = $_GET['id'] ?? 0;
$user_id = $_GET['user_id'] ?? 0;

// Mengambil nama pengguna
$user_info = $conn->query("SELECT name FROM users WHERE id = '$user_id'")->fetch_assoc();
$individual_responses = $conn->query("SELECT questions.question_text, responses.answer FROM responses JOIN questions ON responses.question_id = questions.id WHERE responses.survey_id = $survey_id AND responses.user_id = $user_id");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Jawaban Survey</title>
</head>

<body>
    <div class="container">
        <h2>Jawaban Survey dari <?= htmlspecialchars($user_info['name']) ?></h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Pertanyaan</th>
                    <th>Jawaban</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($response = $individual_responses->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($response['question_text']) ?></td>
                        <td><?= htmlspecialchars($response['answer']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
            <a href="view_responses.php?id=<?= $survey_id ?>" class="btn btn-primary">Kembali ke List Jawaban Survey</a>
        </div>
    </div>
</body>

</html>