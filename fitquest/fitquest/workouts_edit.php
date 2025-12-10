<?php
// Edits an existing workout plan

require_once 'config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: Index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$valid_difficulties = ['Beginner', 'Intermediate', 'Advanced'];
$valid_targets = ['Full Body', 'Upper Body', 'Lower Body', 'Core'];

$errors = [];

// Get workout_id
$workout_id = isset($_GET['workout_id']) ? (int) $_GET['workout_id'] : 0;
if ($workout_id <= 0) {
    die('Invalid workout id.');
}

// Load existing plan
$stmt = $mysqli->prepare(
    "SELECT name, difficulty, target_muscle
     FROM workout_plans
     WHERE workout_id = ? AND user_id = ?"
);
if (!$stmt) {
    die("Query failed: " . $mysqli->error);
}
$stmt->bind_param('ii', $workout_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Not found or doesn't belong to this user
    $stmt->close();
    die('Workout plan not found.');
}

$row = $result->fetch_assoc();
$stmt->close();

// Initialize Values
$name          = $row['name'];
$difficulty    = $row['difficulty'];
$target_muscle = $row['target_muscle'];

// Form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = trim($_POST['name'] ?? '');
    $difficulty    = $_POST['difficulty'] ?? '';
    $target_muscle = $_POST['target_muscle'] ?? '';

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
            "UPDATE workout_plans
             SET name = ?, difficulty = ?, target_muscle = ?
             WHERE workout_id = ? AND user_id = ?"
        );
        if (!$stmt) {
            die("Update failed: " . $mysqli->error);
        }

        $stmt->bind_param(
            'sssii',
            $name,
            $difficulty,
            $target_muscle,
            $workout_id,
            $user_id
        );
        $stmt->execute();
        $stmt->close();

        header('Location: workouts.php');
        exit;
    }
}
?>
<?php include 'includes/header.php'; ?>

<h1>Edit Workout Plan</h1>

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
                <?php foreach ($valid_targets as $t): ?>
                    <option value="<?php echo $t; ?>"
                        <?php if ($target_muscle === $t) echo 'selected'; ?>>
                        <?php echo $t; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <button type="submit">Update Workout Plan</button>
</form>

<?php include 'includes/footer.php'; ?>
