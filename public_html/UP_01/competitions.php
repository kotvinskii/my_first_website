<?php 
require 'connect.php'; 
require_once 'auth_check.php';
$msg = ''; // Fixed empty assignment
$sql = "SELECT * FROM Competitions_new"; 
$stmt = $pdo->prepare($sql); 
$stmt->execute(); 
$objects = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?> 
<!DOCTYPE html> 
<html lang="ru"> 
<head> 
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Соревнования</title> 
    <link rel="stylesheet" href="styles_competitions.css"> 
</head> 
<body> 
    <h1>Информация о соревнованиях</h1> 
    <a href="add_competitions.php"><button>Добавить соревнования</button></a>
    <a href="edit_competitions.php"><button>Редактировать соревнования</button></a>
    <a href="delete_competitions.php"><button>Удалить соревнования</button></a>
    <a href="search_competitions.php"><button>Найти соревнования</button></a>
    <?php if (count($objects) > 0): ?> 
        <?php foreach ($objects as $object): ?> 
            <div class="object"> 
                <p><strong>Соревнования номер:</strong> <?= htmlspecialchars($object['competition_id'] ?? '') ?> </p> 
                <p><strong>Название соревнований:</strong> <?= htmlspecialchars($object['competition_name'] ?? '') ?> </p> 
                <p><strong>ID спорта:</strong> <?= htmlspecialchars($object['sport_id'] ?? '') ?> </p> 
                <p><strong>ID объекта:</strong> <?= htmlspecialchars($object['facility_id'] ?? '') ?> </p> 
                <p><strong>ID организатора:</strong> <?= htmlspecialchars($object['organizer_id'] ?? '') ?> </p> 
                <p><strong>Дата соревнований:</strong> <?= htmlspecialchars($object['competition_date'] ?? '') ?> </p> 
                <p><strong>Уровень соревнований:</strong> <?= htmlspecialchars($object['competition_level'] ?? '') ?> </p> 
                <p><strong>Количество участников:</strong> <?= htmlspecialchars($object['participants_count'] ?? '') ?> </p> 
                <p><strong>Описание:</strong> <?= htmlspecialchars($object['description'] ?? '') ?> </p> 
            </div> 
        <?php endforeach; ?> 
    <?php else: ?> 
        <p>Соревнования не найдены.</p> 
    <?php endif; ?> 
</body> 
</html>
