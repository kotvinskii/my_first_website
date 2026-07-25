<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$errors = [];
require_once 'auth_check.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $fac_type = trim($_POST['facility_type'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $capacity = trim($_POST['capacity'] ?? '');
    $field_type = trim($_POST['field_type'] ?? '');
    $year_built = trim($_POST['year_built'] ?? '');
    $has_track = trim($_POST['has_track'] ?? '');
    $has_lighting = trim($_POST['has_lighting'] ?? '');
    $contact_phone = trim($_POST['contact_phone'] ?? '');
    $fac_img = trim($_POST['facilities_image'] ?? '');

    // Валидация
    if (empty($name)) { $errors['name'] = 'Заполните название объекта'; }
    if (empty($fac_type)) { $errors['facility_type'] = 'Заполните тип объекта'; }
    if (empty($address)) { $errors['address'] = 'Заполните поле адрес'; }
    if (empty($capacity)) { $errors['capacity'] = 'Заполните поле вместимость'; }
    if (empty($field_type)) { $errors['field_type'] = 'Заполните тип поля'; }
    if (empty($year_built)) { $errors['year_built'] = 'Заполните год постройки'; }
    if (empty($has_track)) { $errors['has_track'] = 'Заполните поле has track'; }
    if (empty($has_lighting)) { $errors['has_lighting'] = 'Заполните поле освещения'; }
    if (empty($contact_phone)) { $errors['contact_phone'] = 'Заполните телефон'; }

    if (empty($errors)) {
        // Проверка существования записи (исправлена таблица с Sports на SportsFacilities)
        $stmt = $pdo->prepare("SELECT * FROM SportsFacilities WHERE name = ?");
        $stmt->execute([$name]);
        
        if ($stmt->fetch()) {
            $errors['name'] = 'Объект с таким названием уже существует';
        } else {
            $sql = "INSERT INTO SportsFacilities (name, facility_type, address, capacity, field_type, year_built, has_track, has_lighting, contact_phone, facilities_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $fac_type, $address, $capacity, $field_type, $year_built, $has_track, $has_lighting, $contact_phone, $fac_img]);
            
            header("Location: sports_places.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма добавления объекта</title>
    <link rel='stylesheet' href="styles_competitions.css">
    <style>
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
    <h1>Информация о видах объекта</h1>
    <div align="center">
        <h2>Форма добавления видов объекта</h2>
        
        <!-- Вывод общих ошибок -->
        <?php if(!empty($errors)): ?>
            <div style="color: red;">
                <?php foreach($errors as $error) { echo "<p>$error</p>"; } ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <table>
                <tr>
                    <td align="right">Название объекта</td>
                    <td><input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Тип объекта</td>
                    <td><input type="text" name="facility_type" required value="<?= htmlspecialchars($_POST['facility_type'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Адрес</td>
                    <td><input type="text" name="address" required value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Вместимость</td>
                    <td><input type="number" min="0" step="100" name="capacity" required value="<?= htmlspecialchars($_POST['capacity'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Тип поля</td>
                    <td><input type="text" name="field_type" required value="<?= htmlspecialchars($_POST['field_type'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Год постройки</td>
                    <td><input type="number" min="1900" max="2026" name="year_built" required value="<?= htmlspecialchars($_POST['year_built'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Has track</td>
                    <td><input type="number" min="0" max="1" step="1" name="has_track" required value="<?= htmlspecialchars($_POST['has_track'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Есть ли освещение (0 - нет, 1 - да)</td>
                    <td><input type="number" min="0" max="1" step="1" name="has_lighting" required value="<?= htmlspecialchars($_POST['has_lighting'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Телефон для контакта</td>
                    <td><input type="tel" name="contact_phone" required value="<?= htmlspecialchars($_POST['contact_phone'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Изображение объекта</td>
                    <td><input type="file" name="facilities_image" value="<?= htmlspecialchars($_POST['facilities_image'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit">Добавить объект</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
