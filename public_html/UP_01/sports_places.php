<?php
require 'connect.php';
require_once 'auth_check.php';
$msg = "";
$sql = "SELECT * FROM SportsFacilities";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Спорт. объекты</title>
    <link rel='stylesheet' href="styles_competitions.css">
</head>
<body>
    <h1>Информация о спортобъектах</h1>
    <a href="add_sportfacilies.php"><button>Добавить объект</button></a>
    <a href="edit_sportfacilies.php"><button>Редактировать объект</button></a>
    <a href="delete_sportfacilies.php"><button>Удалить объект</button></a>
    <a href="search_sportfacility.php"><button>Найти объект</button></a>
    <?php if (count($objects) > 0): ?>
    
        <?php foreach ($objects as $object): ?>
    
            <div class="object">
    
                <p><strong>ID спортобъекта:</strong>
                    <?= htmlspecialchars($object['facility_id']) ?>
                </p>
    
                <p><strong>Название соревнований:</strong>
                    <?= htmlspecialchars($object['name']) ?>
                </p>
    
                <p><strong>Тип объекта:</strong>
                    <?= htmlspecialchars($object['facility_type']) ?>
                </p>
    
                <p><strong>Адрес:</strong>
                    <?= htmlspecialchars($object['address']) ?>
                </p>
    
                <p><strong>Вместимость:</strong>
                    <?= htmlspecialchars($object['capacity']) ?>
                </p>
    
                <p><strong>Тип поля:</strong>
                    <?= htmlspecialchars($object['field_type']) ?>
                </p>
    
                <p><strong>Год постройки:</strong>
                    <?= htmlspecialchars($object['year_built']) ?>
                </p>
                
                <p><strong>Has track:</strong>
                    <?= htmlspecialchars($object['has_track']) ?>
                </p>
                
                <p><strong>Есть ли освещение:</strong>
                    <?= htmlspecialchars($object['has_lighting']) ?>
                </p>
                
                <p><strong>Телефон контакт:</strong>
                    <?= htmlspecialchars($object['contact_phone']) ?>
                </p>
                
                <p><strong>Фото:</strong>
                    <?= htmlspecialchars($object['facilities_imagw'] ?? "") ?>
                </p>
            </div>
    
        <?php endforeach; ?>
    
    <?php else: ?>
    
        <p>Нет информации о спорт.объектах.</p>
    
    <?php endif; ?>

</body>
</html>

