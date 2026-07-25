<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once 'auth_check.php';
$errors = [];
$facility_id = trim($_POST['facility_id'] ?? $_GET['facility_id'] ?? '');
$facility = null;

if (!empty($facility_id)) {
    $stmt = $pdo->prepare("SELECT * FROM SportsFacilities WHERE facility_id = ?");
    $stmt->execute([$facility_id]);
    $facility = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$facility) {
        $errors['facility_id'] = "Объект с ID $facility_id не найден.";
        $facility_id = '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save'])) {
    $name = trim($_POST['name'] ?? '');
    $facility_type = trim($_POST['facility_type'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $capacity = trim($_POST['capacity'] ?? '');
    $field_type = trim($_POST['field_type'] ?? '');
    $year_built = trim($_POST['year_built'] ?? '');
    $has_track = isset($_POST['has_track']) ? 1 : 0;
    $has_lighting = isset($_POST['has_lighting']) ? 1 : 0;
    $contact_phone = trim($_POST['contact_phone'] ?? '');
    $fac_image = trim($_POST['facilities_image'] ?? '');
    if(empty($name)){ $errors['name'] = 'Заполните название'; }
    if(empty($facility_type)){ $errors['facility_type'] = 'Заполните тип'; }

    if (empty($errors)) {
        $sql = "UPDATE SportsFacilities SET name=?, facility_type=?, address=?, capacity=?, field_type=?, year_built=?, has_track=?, has_lighting=?, contact_phone=?, facilities_image=? WHERE facility_id=?";
        try {
            $pdo->prepare($sql)->execute([$name, $facility_type, $address, $capacity, $field_type, $year_built, $has_track, $has_lighting, $contact_phone, $facility_id, $fac_image]);
            header("Location: sports_places.php?msg=updated");
            exit;
        } catch(PDOException $e) { $errors['db'] = $e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <title>Редактирование спортивного объекта</title>
    <style>.error-box { color: #721c24; background-color: #f8d7da; padding: 10px; margin: 10px auto; width: 50%; border-radius: 4px; } .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; } .btn-primary { background-color: #007bff; color: white; border: none; } .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }</style>
</head>
<body>
<div align="center">
    <h1>Редактирование спортивного объекта</h1>
    <?php if(!empty($errors)): ?><div class="error-box"><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if (empty($facility_id)): ?>
        <form method="POST">
            <table>
                <tr><td>Введите ID объекта:</td><td><input type="number" name="facility_id" required></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Найти</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="facility_id" value="<?=htmlspecialchars($facility_id)?>">
            <input type="hidden" name="action_save" value="1">
            <table>
                <tr><td>Название:</td><td><input type="text" name="name" required value="<?=htmlspecialchars($_POST['name'] ?? $facility['name'] ?? '')?>"></td></tr>
                <tr><td>Тип объекта:</td><td><input type="text" name="facility_type" required value="<?=htmlspecialchars($_POST['facility_type'] ?? $facility['facility_type'] ?? '')?>"></td></tr>
                <tr><td>Адрес:</td><td><input type="text" name="address" value="<?=htmlspecialchars($_POST['address'] ?? $facility['address'] ?? '')?>"></td></tr>
                <tr><td>Вместимость:</td><td><input type="number" name="capacity" value="<?=htmlspecialchars($_POST['capacity'] ?? $facility['capacity'] ?? '')?>"></td></tr>
                <tr><td>Тип покрытия:</td><td><input type="text" name="field_type" value="<?=htmlspecialchars($_POST['field_type'] ?? $facility['field_type'] ?? '')?>"></td></tr>
                <tr><td>Год постройки:</td><td><input type="number" name="year_built" value="<?=htmlspecialchars($_POST['year_built'] ?? $facility['year_built'] ?? '')?>"></td></tr>
                <tr><td>Наличие дорожек:</td><td><input type="checkbox" name="has_track" value="1" <?=($_POST['has_track'] ?? $facility['has_track'] ?? 0) ? 'checked' : ''?>></td></tr>
                <tr><td>Наличие освещения:</td><td><input type="checkbox" name="has_lighting" value="1" <?=($_POST['has_lighting'] ?? $facility['has_lighting'] ?? 0) ? 'checked' : ''?>></td></tr>
                <tr><td>Телефон:</td><td><input type="tel" name="contact_phone" value="<?=htmlspecialchars($_POST['contact_phone'] ?? $facility['contact_phone'] ?? '')?>"></td></tr>
                <tr><td>Фото:</td><td><input type="file" name="facilities_image" value="<?=htmlspecialchars($_POST['facilities_image'] ?? $facility['facilities_image'] ?? '')?>"></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Сохранить</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php endif; ?>
</div>
</body>
</html>


