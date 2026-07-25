<?php
require_once 'connect.php';
require_once 'auth_check.php';
$msg = "";
$sql = "SELECT * FROM Results";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты</title>
    <link rel='stylesheet' href="styles_competitions.css">
</head>
<body>
    <h1>Информация о результатах соревнований</h1>
    <a href="add_results.php"><button>Добавить результат</button></a>
    <a href="edit_results.php"><button>Редактировать результат</button></a>
    <a href="delete_results.php"><button>Удалить результат</button></a>
    <a href="search_results.php"><button>Найти результат</button></a>
    <?php if (count($objects) > 0): ?>
    
        <?php foreach ($objects as $object): ?>
    
            <div class="object">
    
                <p><strong>Результат номер:</strong>
                    <?= htmlspecialchars($object['result_id']) ?>
                </p>
    
                <p><strong>Название соревнований:</strong>
                    <?= htmlspecialchars($object['competition_id']) ?>
                </p>
    
                <p><strong>ID спортсмена:</strong>
                    <?= htmlspecialchars($object['athlete_id']) ?>
                </p>
    
                <p><strong>Место:</strong>
                    <?= htmlspecialchars($object['place']) ?>
                </p>
    
                <p><strong>Значение результата:</strong>
                    <?= htmlspecialchars($object['result_value']) ?>
                </p>
    
                <p><strong>Формат игры:</strong>
                    <?= htmlspecialchars($object['result_unit']) ?>
                </p>
    
                <p><strong>Медаль:</strong>
                    <?= htmlspecialchars($object['medal']) ?>
                </p>
                
                <p><strong>Статус записи:</strong>
                    <?= htmlspecialchars($object['record_status']) ?>
                </p>
    
            </div>
    
        <?php endforeach; ?>
    
    <?php else: ?>
    
        <p>Нет информации о результатах соревнованиях.</p>
    
    <?php endif; ?>
</body>
</html>