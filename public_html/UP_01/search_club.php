<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
$club_id = trim($_POST['club_id'] ?? $_GET['club_id'] ?? '');
$club = null;

require_once 'auth_check.php';

// Шаг 1: Проверяем существование записи, если ID передан
if (!empty($club_id)) {
    $stmt = $pdo->prepare("SELECT * FROM SportsClubs WHERE club_id = ?");
    $stmt->execute([$club_id]);
    $club = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$club) {
        $errors['club_id'] = "Спортивный клуб с ID $club_id не найден.";
        $club_id = ''; // Сбрасываем ID, чтобы вернуть пользователя на шаг ввода
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск спортивного клуба</title>
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
        <h1>Найти спортивные клубы</h1>

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

        <!-- ЭТАП 1: Форма ввода ID для поиска -->
        <?php if (empty($club_id)): ?>
            <h2>Форма нахождения спортивного клуба</h2>
            <form method="POST">
                <table style="border: none;">
                    <tr style="border: none;">
                        <td style="border: none;" align="right">Введите ID клуба для поиска:</td>
                        <td style="border: none;"><input type="number" name="club_id" min="1" required></td>
                    </tr>
                    <tr style="border: none;">
                        <td colspan="2" align="center" style="border: none;">
                            <br>
                            <button type="submit" class="btn btn-danger">Найти запись</button>
                            <a href="sports_clubs.php" class="btn btn-secondary">Отмена</a>
                        </td>
                    </tr>
                </table>
            </form>
        <?php else: ?>
            <h3>Информация о клубе №<?= htmlspecialchars($club_id) ?></h3>
            <table>
                <tr><td class="label">ID Клуба:</td><td><?= htmlspecialchars($club['club_id']) ?></td></tr>
                <tr><td class="label">Название клуба:</td><td><?= htmlspecialchars($club['club_name']) ?></td></tr>
                <tr><td class="label">Город:</td><td><?= htmlspecialchars($club['city']) ?></td></tr>
                <tr><td class="label">Год основания:</td><td><?= htmlspecialchars($club['founded_year']) ?></td></tr>
                <tr><td class="label">Президент/Руководитель:</td><td><?= htmlspecialchars($club['president']) ?></td></tr>
                <tr><td class="label">Телефон:</td><td><?= htmlspecialchars($club['phone']) ?></td></tr>
                <tr><td class="label">Email:</td><td><?= htmlspecialchars($club['email']) ?></td></tr>
                <tr><td class="image">Фото:</td><td><?= htmlspecialchars($club['sportclub_image']) ?></td></tr>
            </table>
            <a href="?" class="btn btn-secondary">Искать другой клуб</a>
        <?php endif; ?>
    </div>
</body>
</html>
