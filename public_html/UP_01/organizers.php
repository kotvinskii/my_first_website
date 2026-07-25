<?php
require_once 'connect.php';
require_once 'auth_check.php';
$msg = "";
$sql = "SELECT * FROM Organizers";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Организаторы</title>
    <link rel='stylesheet' href="styles_competitions.css">
</head>
<body>
    <h1>Информация об организаторах</h1>
    <a href="add_organizers.php"><button>Добавить организатора</button></a>
    <a href="edit_organizers.php"><button>Редактировать организатора</button></a>
    <a href="delete_organizers.php"><button>Удалить организатора</button></a>
    <a href="search_organizers.php"><button>Найти организатора</button></a>
    <?php if (count($objects) > 0): ?>
    
        <?php foreach ($objects as $object): ?>
    
            <div class="object">
    
                <p><strong>Организатор номер:</strong>
                    <?= htmlspecialchars($object['organizer_id']) ?>
                </p>
    
                <p><strong>Название:</strong>
                    <?= htmlspecialchars($object['name']) ?>
                </p>
    
                <p><strong>Контакт организатор:</strong>
                    <?= htmlspecialchars($object['contact_person']) ?>
                </p>
                
                <p><strong>Телефон:</strong>
                    <?= htmlspecialchars($object['phone']) ?>
                </p>
                
                <p><strong>Email:</strong>
                    <?= htmlspecialchars($object['email']) ?>
                </p>
                
                <p><strong>Адрес:</strong>
                    <?= htmlspecialchars($object['address']) ?>
                </p>
    
            </div>
    
        <?php endforeach; ?>
    
    <?php else: ?>
    
        <p>Нет информации об организаторах.</p>
    
    <?php endif; ?>

</body>
</html>
