<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
$org_id = trim($_POST['organizer_id'] ?? $_GET['organizer_id'] ?? '');
$org = null;
require_once 'auth_check.php';
// Шаг 1: Проверяем существование записи, если ID передан
if (!empty($org_id)) {
    $stmt = $pdo->prepare("SELECT * FROM Organizers WHERE organizer_id = ?");
    $stmt->execute([$org_id]);
    $competition = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$competition) {
        $errors['organizer_id'] = "Организатор с ID $org_id не найдено.";
        $competition_id = ''; // Сбрасываем ID, чтобы вернуть пользователя на шаг ввода
    }
}

// Шаг 2: Обработка подтвержденного удаления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_delete'])) {
    if (!empty($org_id) && $competition) {
        try {
            $stmt = $pdo->prepare("DELETE FROM Organizers WHERE organizer_id = ?");
            $stmt->execute([$org_id]);
            
            // Перенаправляем на главную с флагом успешного удаления
            header("Location: organizers.php?msg=deleted");
            exit;
        } catch(PDOException $e) {
            // Ошибка может возникнуть, например, если запись связана внешним ключом с таблицей Results
            $errors['db'] = 'Ошибка удаления: возможно, эта запись используется в результатах или других таблицах. ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Удаление организаторов</title>
    <link rel='stylesheet' href="styles_competitions.css">
    <style>
        .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px auto; width: 50%; border-radius: 4px; text-align: left; }
        .warning-box { color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba; padding: 15px; margin: 10px auto; width: 50%; border-radius: 4px; text-align: left; }
        table { margin: 20px auto; border-collapse: collapse; }
        td { padding: 8px; border: 1px solid #ddd; }
        td.label { font-weight: bold; background-color: #f9f9f9; text-align: right; width: 40%; }
        .btn { padding: 8px 16px; text-decoration: none; border-radius: 4px; cursor: pointer; border: 1px solid transparent; font-size: 14px; display: inline-block; }
        .btn-danger { background-color: #dc3545; color: white; border-color: #dc3545; }
        .btn-secondary { background-color: #6c757d; color: white; border-color: #6c757d; margin-left: 10px; }
    </style>
</head>
<body>
    <div align="center">
        <h1>Управление организаторами</h1>
        
        <!-- Вывод списка ошибок -->
        <?php if(!empty($errors)): ?>
            <div class="error-box">
                <strong>Ошибка:</strong>
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- ЭТАП 1: Форма ввода ID для удаления -->
        <?php if (empty($org_id)): ?>
            <h2>Форма удаления организаторов</h2>
            <form method="POST">
                <table style="border: none;">
                    <tr style="border: none;"><td style="border: none;" align="right">Введите ID организаторов для удаления:</td>
                        <td style="border: none;"><input type="number" name="organizer_id" min="1" required></td>
                    </tr>
                    <tr style="border: none;">
                        <td colspan="2" align="center" style="border: none;">
                            <br>
                            <button type="submit" class="btn btn-danger">Найти запись</button>
                            <a href="organizers.php" class="btn btn-secondary">Отмена</a>
                        </td>
                    </tr>
                </table>
            </form>

        <!-- ЭТАП 2: Подтверждение удаления найденной записи -->
        <?php else: ?>
            <div class="warning-box">
                <h3 style="margin-top:0; color: #721c24;">⚠️ Предупреждение!</h3>
                <p>Вы действительно хотите безвозвратно удалить следующую запись из базы данных?</p>
            </div>

            <h3>Информация о организаторах №<?= htmlspecialchars($competition_id) ?></h3>
            
            <table>
                <tr><td class="label">ID организатора:</td><td><?= htmlspecialchars($competition['organizer_id']) ?></td></tr>
                <tr><td class="label">Название:</td><td><?= htmlspecialchars($competition['name']) ?></td></tr>
                <tr><td class="label">Контакт:</td><td><?= htmlspecialchars($competition['contact_person']) ?></td></tr>
                <tr><td class="label">Телефон:</td><td><?= htmlspecialchars($competition['phone']) ?></td></tr>
                <tr><td class="label">Email:</td><td><?= htmlspecialchars($competition['email']) ?></td></tr>
                <tr><td class="label">Address:</td><td><?= htmlspecialchars($competition['address']) ?></td></tr>
            </table>

            <form method="POST">
                <!-- Передаем ID и скрытый триггер удаления -->
                <input type="hidden" name="organizer_id" value="<?= htmlspecialchars($org_id) ?>">
                <input type="hidden" name="action_delete" value="1">
                
                <br>
                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы абсолютно уверены? Это действие нельзя отменить.');">Да, удалить запись</button>
                <a href="organizers.php" class="btn btn-secondary">Отмена</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>