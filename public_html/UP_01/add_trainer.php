<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
require_once 'auth_check.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $last_name = trim($_POST['last_name'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $mid_name = trim($_POST['middle_name'] ?? '');
    $qual = trim($_POST['qualification'] ?? '');
    $exp_years = trim($_POST['experience_years'] ?? '');
    $achievements = trim($_POST['achievements'] ?? '');
    $club_id = trim($_POST['club_id'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tr_img = trim($_POST['trainer_photo'] ?? ''); // исправлено на trainer_photo

    if(empty($qual)){ $errors['qualification'] = 'Заполните поле Квалификация'; }
    if(empty($exp_years)){ $errors['experience_years'] = 'Заполните поле Опыт работы'; }
    if(empty($achievements)){ $errors['achievements'] = 'Заполните поле Достижения'; }
    if(empty($club_id)){ $errors['club_id'] = 'Заполните поле ID клуба'; }
    if(empty($first_name)) { $errors['first_name'] = 'Заполните поле Имя'; }
    if(empty($last_name)) { $errors['last_name'] = 'Заполните поле Фамилия'; }
    if(empty($mid_name)) { $errors['middle_name'] = 'Заполните поле Отчество'; }
    if(empty($email)) { $errors['email'] = 'Заполните поле Email'; }
    if(empty($phone)) { $errors['phone'] = 'Заполните поле Телефон'; } 
    
    if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        $errors['email'] = 'Неверный формат email'; 
    }

    if(empty($errors)) {
        // Проверка существования пользователя
        $stmt = $pdo->prepare("SELECT * FROM Trainers WHERE email = ?");
        $stmt->execute([$email]); 
        $stmt_2 = $pdo->prepare("SELECT fidRole FROM Trainers WHERE fidUser = ?");
        $user_2 = $stmt_2->fetch(PDO::FETCH_ASSOC);
        if ($stmt->fetch()) {
            $errors['email'] = 'Пользователь с таким email уже существует';
        } else {
            $sql = "INSERT INTO Trainers (last_name, first_name, middle_name, qualification, experience_years, achievements, club_id, phone, email, trainer_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$last_name, $first_name, $mid_name, $qual, $exp_years, $achievements, $club_id, $phone, $email, $tr_img]);
            header("Location: trainers.php");
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
    <title>Форма добавления тренера</title>
    <link rel='stylesheet' href="styles_competitions.css">
    <style>
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
    <h1>Информация о тренерах</h1>
    <div align="center">
        <h2>Форма добавления тренера</h2>
        
        <!-- Вывод общих ошибок (по желанию) -->
        <?php if(!empty($errors)): ?>
            <div style="color: red;">
                <?php foreach($errors as $error) { echo "<p>$error</p>"; } ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <table>
                <tr>
                    <td align="right">Фамилия</td>
                    <td><input type="text" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Имя</td>
                    <td><input type="text" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Отчество</td>
                    <td><input type="text" name="middle_name" required value="<?= htmlspecialchars($_POST['middle_name'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Квалификация</td>
                    <td><input type="text" name="qualification" required value="<?= htmlspecialchars($_POST['qualification'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Опыт работы</td>
                    <td><input type="number" name="experience_years" required value="<?= htmlspecialchars($_POST['experience_years'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Достижения</td>
                    <td><input type="text" name="achievements" required value="<?= htmlspecialchars($_POST['achievements'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">ID клуба</td>
                    <td><input type="number" name="club_id" min="1" required value="<?= htmlspecialchars($_POST['club_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Телефон</td>
                    <td><input type="tel" name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Email</td>
                    <td><input type="text" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Фото тренера</td>
                    <td><input type="file" name="trainer_photo" value="<?= htmlspecialchars($_POST['trainer_photo'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit">Добавить тренера</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
