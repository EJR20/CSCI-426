<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
require_login();

$uid = current_user_id();

// total logs, avg weight, avg rpe
$stmt = $mysqli->prepare(
    "SELECT COUNT(*), AVG(weight_lb), AVG(rpe)
     FROM progress_logs
     WHERE user_id = ?"
);
$stmt->bind_param('i', $uid);
$stmt->execute();
$stmt->bind_result($log_count, $avg_weight, $avg_rpe);
$stmt->fetch();
$stmt->close();

// most common focus
$stmt = $mysqli->prepare(
    "SELECT focus, COUNT(*) AS c
     FROM progress_logs
     WHERE user_id = ?
     GROUP BY focus
     ORDER BY c DESC
     LIMIT 1"
);
$stmt->bind_param('i', $uid);
$stmt->execute();
$stmt->bind_result($top_focus, $top_focus_count);
$stmt->fetch();
$stmt->close();

// total cardio minutes
$stmt = $mysqli->prepare(
    "SELECT SUM(cardio_minutes)
     FROM progress_logs
     WHERE user_id = ?"
);
$stmt->bind_param('i', $uid);
$stmt->execute();
$stmt->bind_result($total_cardio);
$stmt->fetch();
$stmt->close();
?>

<?php include 'includes/header.php'; ?>

<h2>Progress Analytics</h2>
<p>Quick overview of your training history.</p>

<ul>
    <li>
        <strong>Total Progress Logs:</strong>
        <?php echo (int)$log_count; ?>
    </li>
    <li>
        <strong>Average Logged Weight:</strong>
        <?php echo $avg_weight ? number_format($avg_weight, 1).' lbs' : 'Not enough data yet'; ?>
    </li>
    <li>
        <strong>Average Session RPE:</strong>
        <?php echo $avg_rpe ? number_format($avg_rpe, 1) : 'Not enough data yet'; ?>
    </li>
    <li>
        <strong>Most Common Focus:</strong>
        <?php echo $top_focus ? htmlspecialchars($top_focus) : 'Not enough data yet'; ?>
    </li>
    <li>
        <strong>Total Cardio Minutes Logged:</strong>
        <?php echo $total_cardio !== null ? (int)$total_cardio.' minutes' : '0 minutes'; ?>
    </li>
</ul>

<?php include 'includes/footer.php'; ?>
