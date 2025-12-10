<?php
// Creates a new workout plan

require_once 'config/db.php';  // adjust if needed
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); 
    exit;
}

$user_id = $_SESSION['user_id'];

$valid_difficulties = ['Beginner', 'Intermediate', 'Advanced'];
$valid_targets      = ['Full Body', 'Upper Body', 'Lower Body', 'Core'];

$name          = '';
$difficulty    = '';
$target_muscle = '';
$errors        = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = trim($_POST['name'] ?? '');
    $difficulty    = $_POST['difficulty'] ?? '';
    $target_muscle = $_POST['target_muscle'] ?? '';

    // Basic validation
    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if (!in_array($difficulty, $valid_difficulties, true)) {
        $errors[] = 'Please choose a valid difficulty.';
    }

    if (!in_array($target_muscle, $valid_targets, true)) {
        $errors[] = 'Please choose a valid target muscle group.';
    }

    if (empty($errors)) {
        $stmt = $mysqli->prepare(
            "INSERT INTO workout_plans (user_id, name, difficulty, target_muscle)
             VALUES (?, ?, ?, ?)"
        );
        if (!$stmt) {
            die("Insert failed: " . $mysqli->error);
        }

        $stmt->bind_param('isss', $user_id, $name, $difficulty, $target_muscle);
        $stmt->execute();
        $stmt->close();

        header('Location: workouts.php');
        exit;
    }
}
?>
<?php include 'includes/header.php'; ?>

<h1>Create Workout Plan</h1>

<p><a href="workouts.php">&larr; Back to My Workout Plans</a></p>

<?php if (!empty($errors)): ?>
    <div style="color: red;">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">
    <div>
        <label>
            Name:
            <input type="text" name="name"
                   value="<?php echo htmlspecialchars($name); ?>">
        </label>
    </div>

    <div>
        <label>
            Difficulty:
            <select name="difficulty">
                <option value="">Select difficulty</option>
                <?php foreach ($valid_difficulties as $d): ?>
                    <option value="<?php echo $d; ?>"
                        <?php if ($difficulty === $d) echo 'selected'; ?>>
                        <?php echo $d; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div>
        <label>
            Target Muscle:
            <select name="target_muscle">
                <option value="">Select target</option>
                <?php foreach ($valid_targets as $t): ?>
                    <option value="<?php echo $t; ?>"
                        <?php if ($target_muscle === $t) echo 'selected'; ?>>
                        <?php echo $t; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <button type="submit">Save Workout Plan</button>
</form>

<?php include 'includes/footer.php'; ?>
