<?php
require_once 'connect.php';
require_once 'auth_check.php';
$msg = "";
$sql = "SELECT * FROM TeamCompetitions";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Командные соревнования</title>
    <link rel='stylesheet' href="styles_competitions.css">
</head>
<body>
    <h1>Информация о результатах командных соревнований</h1>
    <a href="add_teamcompetitions.php"><button>Добавить командные соревнования</button></a>
    <a href="edit_teamcompetitions.php"><button>Редактировать командные соревнования</button></a>
    <a href="delete_teamcompetitions.php"><button>Удалить командные соревнования</button></a>
    <a href="search_teamcompetitions.php"><button>Найти командные соревнования</button></a>
    <?php if (count($objects) > 0): ?>
    
        <?php foreach ($objects as $object): ?>
    
            <div class="object">
    
                <p><strong>Соревнования номер:</strong>
                    <?= htmlspecialchars($object['team_competition_id']) ?>
                </p>
    
                <p><strong>ID соревнований:</strong>
                    <?= htmlspecialchars($object['competition_id']) ?>
                </p>
    
                <p><strong>ID команд:</strong>
                    <?= htmlspecialchars($object['team_id']) ?>
                </p>
    
                <p><strong>Место:</strong>
                    <?= htmlspecialchars($object['place']) ?>
                </p>
    
                <p><strong>результат соревнований:</strong>
                    <?= htmlspecialchars($object['result']) ?>
                </p>
    
            </div>
    
        <?php endforeach; ?>
    
    <?php else: ?>
    
        <p>Нет информации о командных соревнованиях.</p>
    
    <?php endif; ?>
</body>
</html>