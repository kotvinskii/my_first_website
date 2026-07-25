<?php
require_once 'connect.php';
require_once 'auth_check.php';
$msg = "";
$sql = "SELECT * FROM Athletes";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Спортсмены</title>
    <link rel='stylesheet' href="styles_competitions.css">
</head>
<body>
    <h1>Информация о спортсменах</h1>
    <a href="add_sportsmans.php"><button>Добавить спортсмен</button></a>
    <a href="edit_sportsmans.php"><button>Редактировать спортсмен</button></a>
    <a href="delete_sportsmans.php"><button>Удалить спортсмен</button></a>
    <a href="search_sportsmans.php"><button>Найти спортсмена</button></a>
    <?php if (count($objects) > 0): ?>
    
        <?php foreach ($objects as $object): ?>
    
            <div class="object">
    
                <p><strong>ID спортсмена:</strong>
                    <?= htmlspecialchars($object['athlete_id']) ?>
                </p>
    
                <p><strong>Фамилия спортсмена:</strong>
                    <?= htmlspecialchars($object['last_name']) ?>
                </p>
    
                <p><strong>Имя спортсмена:</strong>
                    <?= htmlspecialchars($object['first_name']) ?>
                </p>
    
                <p><strong>Отчество спортсмена:</strong>
                    <?= htmlspecialchars($object['middle_name']) ?>
                </p>
    
                <p><strong>Ранг:</strong>
                    <?= htmlspecialchars($object['rank']) ?>
                </p>
    
                <p><strong>Дата рождения:</strong>
                    <?= htmlspecialchars($object['birth_date']) ?>
                </p>
    
                <p><strong>Достижения:</strong>
                    <?= htmlspecialchars($object['achievements']) ?>
                </p>
                
                <p><strong>ID клуба:</strong>
                    <?= htmlspecialchars($object['club_id']) ?>
                </p>
                
                <p><strong>Телефон:</strong>
                    <?= htmlspecialchars($object['phone']) ?>
                </p>
                
                <p><strong>Email:</strong>
                    <?= htmlspecialchars($object['email']) ?>
                </p>
                
                <<p><strong>Фото:</strong>
                    <?= htmlspecialchars($object['athlet_image'] ?? "") ?>
                </p> ?>" alt="Фото спортсмена">
    
            </div>
    
        <?php endforeach; ?>
    
    <?php else: ?>
    
        <p>Нет информации о спортсменах.</p>
    
    <?php endif; ?>

</body>
</html>
