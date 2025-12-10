<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
require_login();

$uid = current_user_id();
$focusFilter = $_GET['focus'] ?? '';

$sql = "SELECT log_id, log_date, mood, focus, weight_lb, rpe
        FROM progress_logs
        WHERE user_id = ?";
$params = [$uid];
$types  = 'i';

$validFocus = ['Weight','Strength','Cardio','Mixed'];
if ($focusFilter !== '' && in_array($focusFilter, $validFocus, true)) {
    $sql .= " AND focus = ?";
    $params[] = $focusFilter;
    $types   .= 's';
}

$sql .= " ORDER BY log_date DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'includes/header.php'; ?>

<h2>Progress History</h2>

<form method="get" action="progress_list.php" style="margin-bottom:10px;">
    <label for="focus">Filter by focus:</label>
    <select name="focus" id="focus">
        <option value="">All</option>
        <option <?php if($focusFilter==='Weight') echo 'selected'; ?>>Weight</option>
        <option <?php if($focusFilter==='Strength') echo 'selected'; ?>>Strength</option>
        <option <?php if($focusFilter==='Cardio') echo 'selected'; ?>>Cardio</option>
        <option <?php if($focusFilter==='Mixed') echo 'selected'; ?>>Mixed</option>
    </select>
    <input type="submit" value="Apply">
</form>

<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>Date</th>
        <th>Mood</th>
        <th>Focus</th>
        <th>Weight (lbs)</th>
        <th>RPE</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['log_date']); ?></td>
            <td><?php echo htmlspecialchars($row['mood']); ?></td>
            <td><?php echo htmlspecialchars($row['focus']); ?></td>
            <td><?php echo htmlspecialchars($row['weight_lb']); ?></td>
            <td><?php echo htmlspecialchars($row['rpe']); ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
