<?php
require_once 'connect.php';
require_once 'auth_check.php';
$msg = "";
$sql = "SELECT * FROM SportsClubs";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Спорт. клубы</title>
    <link rel='stylesheet' href="styles_competitions.css">
</head>
<body>
    <h1>Информация о клубах</h1>
    <a href="add_sport_club.php"><button>Добавить клуб</button></a>
    <a href="edit_sport_clubs.php"><button>Редактировать клуб</button></a>
    <a href="delete_sport_clubs.php"><button>Удалить клуб</button></a>
    <a href="search_club.php"><button>Найти клуб</button></a>
    <?php if (count($objects) > 0): ?>
    
        <?php foreach ($objects as $object): ?>
    
            <div class="object">
    
                <p><strong>Номер клуба:</strong>
                    <?= htmlspecialchars($object['club_id']) ?>
                </p>
    
                <p><strong>Название клуба:</strong>
                    <?= htmlspecialchars($object['club_name']) ?>
                </p>
    
                <p><strong>Город:</strong>
                    <?= htmlspecialchars($object['city']) ?>
                </p>
    
                <p><strong>Год основания:</strong>
                    <?= htmlspecialchars($object['founded_year']) ?>
                </p>
                
                <p><strong>Телефон:</strong>
                    <?= htmlspecialchars($object['phone']) ?>
                </p>
                
                <p><strong>Email:</strong>
                    <?= htmlspecialchars($object['email']) ?>
                </p>
                
                <p><strong>Логотип спорт.клубов:</strong>
                    <?php if (!empty($object['sportclub_image'])): ?>
                        <img src="<?= htmlspecialchars($object['sportclub_image']) ?>" alt="Логотип" width="150">
                    <?php else: ?>
                        Нет изображения
                    <?php endif; ?>
                
            </div>
    
        <?php endforeach; ?>
    
    <?php else: ?>
    
        <p>Нет информации о клубах.</p>
    
    <?php endif; ?>

</body>
</html>