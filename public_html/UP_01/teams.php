<?php
include 'connect.php'; // подключаем файл с подключением к базе данных

// Запрос для получения информации о Фортах
$sql = "SELECT team_id, team_name, sport_id, club_id, coach_id FROM Teams";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width", initial-scale=1.0>
    <meta name='description' content="Registration and Login System">
    <title>Команды</title>
</head>
<body>
    <h1>Информация о командах</h1>
    <a href="add_teams.php"><button>Добавить команду</button></a>
    <a href="edit_teams.php"><button>Редактировать команду</button></a>
    <a href="delete_teams.php"><button>Удалить команду</button></a>
    <a href="search_teams.php"><button>Найти команду</button></a>
    <?php if (count($objects) > 0): ?>
    
        <?php foreach ($objects as $object): ?>
    
            <div class="object">
                <h2>Команда номер: <?= htmlspecialchars($object['team_id']) ?></h2>
    
                <p><strong>Имя команды:</strong>
                    <?= htmlspecialchars($object['team_name']) ?>
                </p>
    
                <p><strong>ID спорта:</strong>
                    <?= htmlspecialchars($object['sport_id']) ?>
                </p>
    
                <p><strong>ID клуба:</strong>
                    <?= htmlspecialchars($object['club_id']) ?>
                </p>
    
                <p><strong>ID тренера:</strong>
                    <?= htmlspecialchars($object['coach_id']) ?>
                </p>
    
            </div>
    
        <?php endforeach; ?>
    
    <?php else: ?>
    
        <p>Нет команд.</p>
    
    <?php endif; ?>

</body>
</html>