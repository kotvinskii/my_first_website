<?php
// Включаем отображение ошибок, чтобы не поймать пустой белый экран (ошибку 500)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Подключаем базу данных
require_once 'connect.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Спортивные организации</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f6f9; color: #333; }
        h1, h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #34495e; color: #fff; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .container { max-width: 1200px; margin: 0 auto; }
    </style>
</head>
<body>
<div class="container">
    <h1>Информационная система спортивных организаций города</h1>
    
    <!-- СЕКЦИЯ 1: СПОРТСМЕНЫ, ИХ КЛУБЫ И ТРЕНЕРЫ -->
    <h2>1. Список спортсменов и их тренеров по видам спорта</h2>
    <table>
        <thead>
            <tr>
                <th>Спортсмен</th>
                <th>Разряд</th>
                <th>Спортивный клуб</th>
                <th>Вид спорта</th>
                <th>Тренер</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Сложный запрос с JOIN для связи спортсменов, клубов, видов спорта и тренеров
            $sqlAthletes = "SELECT 
                                CONCAT(a.last_name, ' ', a.first_name) AS athlete_name,
                                a.rank,
                                c.club_name,
                                s.sport_name,
                                CONCAT(t.last_name, ' ', t.first_name) AS trainer_name
                            FROM Athletes a
                            LEFT JOIN SportsClubs c ON a.club_id = c.club_id
                            LEFT JOIN AthleteTrainers at r ON a.athlete_id = at r.athlete_id
                            LEFT JOIN Trainers t ON at r.trainer_id = t.trainer_id
                            LEFT JOIN Sports s ON at r.sport_id = s.sport_id";
            
            $stmt = $pdo->query($sqlAthletes);
            while ($row = $stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['athlete_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['rank'] ?? 'Нет') . "</td>";
                echo "<td>" . htmlspecialchars($row['club_name'] ?? 'Без клуба') . "</td>";
                echo "<td>" . htmlspecialchars($row['sport_name'] ?? 'Не указан') . "</td>";
                echo "<td>" . htmlspecialchars($row['trainer_name'] ?? 'Самостоятельно') . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- СЕКЦИЯ 2: СПОРТИВНЫЕ СООРУЖЕНИЯ И ИХ СПЕЦИФИЧЕСКИЕ СВОЙСТВА -->
    <h2>2. Спортивные сооружения города</h2>
    <table>
        <thead>
            <tr>
                <th>Название</th>
                <th>Тип</th>
                <th>Адрес</th>
                <th>Вместимость (Стадионы)</th>
                <th>Покрытие (Корты)</th>
                <th>Освещение</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sqlFacilities = "SELECT name, facility_type, address, capacity, field_type, has_lighting FROM SportsFacilities";
            $stmtFac = $pdo->query($sqlFacilities);
            while ($fac = $stmtFac->fetch()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($fac['name']) . "</td>";
                echo "<td>" . htmlspecialchars($fac['facility_type']) . "</td>";
                echo "<td>" . htmlspecialchars($fac['address']) . "</td>";
                // Специфичный атрибут для стадионов
                echo "<td>" . ($fac['capacity'] ? htmlspecialchars($fac['capacity']) . ' чел.' : '—') . "</td>";
                // Специфичный атрибут для кортов
                echo "<td>" . ($fac['field_type'] ? htmlspecialchars($fac['field_type']) : '—') . "</td>";
                echo "<td>" . ($fac['has_lighting'] ? 'Есть' : 'Нет') . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
