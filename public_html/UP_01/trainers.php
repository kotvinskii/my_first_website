<?php
session_start();
require_once 'connect.php';
require_once 'auth_check.php';

$msg = "";
$sql = "SELECT * FROM Trainers";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тренеры</title>
    <link rel='stylesheet' href="styles_competitions.css">
</head>
<body>
<h1>Информация о тренерах</h1>
        <div class='navigation-menu'>
            <a href="add_trainer.php"><button>Добавить тренера</button></a>
            <a href="edit_trainers.php"><button>Изменить тренера</button></a>
            <a href="delete_trainers.php"><button>Удалить тренера</button></a>
            <a href="search_trainers.php"><button>Найти тренера</button></a>
        </div>
    
    <?php if (count($objects) > 0): ?>
        <?php foreach ($objects as $object): ?>
            <div class="object">
                <p><strong>Тренер номер:</strong>
                    <?= htmlspecialchars($object['trainer_id']) ?>
                </p>
    
                <p><strong>Фамилия тренера:</strong>
                    <?= htmlspecialchars($object['last_name']) ?>
                </p>
    
                <p><strong>Имя тренера:</strong>
                    <?= htmlspecialchars($object['first_name']) ?>
                </p>
    
                <p><strong>Отчество тренера:</strong>
                    <?= htmlspecialchars($object['middle_name']) ?>
                </p>
    
                <p><strong>Квалификация:</strong>
                    <?= htmlspecialchars($object['qualification']) ?>
                </p>
    
                <p><strong>Опыт работы:</strong>
                    <?= htmlspecialchars($object['experience_years']) ?>
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
                
                <p><strong>Фото:</strong>
                    <?= htmlspecialchars($object['trainer_image'] ?? '') ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Нет информации о тренерах.</p>
    <?php endif; ?>
        
</body>
</html>

