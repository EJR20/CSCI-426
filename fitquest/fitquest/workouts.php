<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
require_login();

$uid = current_user_id();

$sql = "SELECT workout_id, name, difficulty, target_muscle, created_at
        FROM workout_plans
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Query failed: " . $mysqli->error);
}

$stmt->bind_param('i', $uid);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'includes/header.php'; ?>

<h1>My Workout Plans</h1>

<p>
    <!-- This page doesn't exist yet, we'll make it next -->
    <a href="workouts_new.php">Create New Workout Plan</a>
</p>

<?php if ($result->num_rows === 0): ?>
    <p>You don’t have any workout plans yet.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Difficulty</th>
            <th>Target Muscle</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['difficulty']); ?></td>
                <td><?php echo htmlspecialchars($row['target_muscle']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td>
                    <!-- These pages/actions will be implemented later -->
                    <a href="workouts_edit.php?workout_id=<?php echo $row['workout_id']; ?>">Edit</a>
                    |
                    <form action="workouts.php" method="post" style="display:inline;">
                        <input type="hidden" name="workout_id" value="<?php echo $row['workout_id']; ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
