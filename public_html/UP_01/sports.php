<?php
require_once 'connect.php';
require_once 'auth_check.php';
$msg = "";
$sql = "SELECT * FROM Sports";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Виды спорта</title>
    <link rel='stylesheet' href="styles_competitions.css">
</head>
<body>
    <h1>Информация о видах спорта</h1>
    <a href="add_sports.php"><button>Добавить спорт</button></a>
    <a href="edit_sports.php"><button>Редактировать спорт</button></a>
    <a href="delete_sports.php"><button>Удалить спорт</button></a>
    <a href="search_sports.php"><button>Найти спорт</button></a>
    <?php if (count($objects) > 0): ?>
    
        <?php foreach ($objects as $object): ?>
    
            <div class="object">
    
                <p><strong>ID спорта:</strong>
                    <?= htmlspecialchars($object['sport_id']) ?>
                </p>
    
                <p><strong>Название спорта:</strong>
                    <?= htmlspecialchars($object['sport_name']) ?>
                </p>
    
                <p><strong>Категория спорта:</strong>
                    <?= htmlspecialchars($object['sport_category']) ?>
                </p>
    
                <p><strong>Описание:</strong>
                    <?= htmlspecialchars($object['description']) ?>
                </p>
    
                <p><strong>Олимпийский спорт:</strong>
                    <?= htmlspecialchars($object['olympic_sport']) ?>
                </p>
                
                <p><strong>Фото:</strong>
                    <?= htmlspecialchars($object['sport_image'] ?? "") ?>
                </p>
    
            </div>
    
        <?php endforeach; ?>
    
    <?php else: ?>
    
        <p>Нет информации о видах спорта.</p>
    
    <?php endif; ?>

</body>
</html>