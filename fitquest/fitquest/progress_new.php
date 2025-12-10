<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
require_login();

// Default values so we don't get undefined notices
$log_date = '';
$mood = '';
$focus = '';
$weight = '';
$volume = '';
$cardio = '';
$rpe = '';
$notes = '';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $log_date = $_POST['log_date'] ?? '';
    $mood     = $_POST['mood'] ?? '';
    $focus    = $_POST['focus'] ?? '';
    $weight   = trim($_POST['weight'] ?? '');
    $volume   = trim($_POST['volume'] ?? '');
    $cardio   = trim($_POST['cardio'] ?? '');
    $rpe      = trim($_POST['rpe'] ?? '');
    $notes    = trim($_POST['notes'] ?? '');

    if (!$log_date) {
        $errors[] = 'Please choose a date.';
    }

    $validMood = ['Great','Okay','Tired','Rough'];
    if (!in_array($mood, $validMood, true)) {
        $errors[] = 'Please select a mood.';
    }

    $validFocus = ['Weight','Strength','Cardio','Mixed'];
    if (!in_array($focus, $validFocus, true)) {
        $errors[] = 'Please select a focus.';
    }

    $w = (float)$weight;
    if ($weight === '' || !is_numeric($weight) || $w < 70 || $w > 600) {
        $errors[] = 'Weight must be between 70 and 600 lbs.';
    }

    $v = (int)$volume;
    if ($volume === '' || !is_numeric($volume) || $v < 0 || $v > 150000) {
        $errors[] = 'Strength volume must be between 0 and 150000.';
    }

    $c = (int)$cardio;
    if ($cardio === '' || !is_numeric($cardio) || $c < 0 || $c > 300) {
        $errors[] = 'Cardio minutes must be between 0 and 300.';
    }

    $r = (int)$rpe;
    if ($rpe === '' || !is_numeric($r) || $r < 1 || $r > 10) {
        $errors[] = 'RPE must be between 1 and 10.';
    }

    if (strlen($notes) < 5) {
        $errors[] = 'Notes must be at least 5 characters.';
    }
    if (strpos($notes, '<') !== false || strpos($notes, '>') !== false) {
        $errors[] = 'Notes cannot contain < or >.';
    }

    if (empty($errors)) {
        $uid = current_user_id();

        $stmt = $mysqli->prepare(
            "INSERT INTO progress_logs
             (user_id, log_date, mood, focus, weight_lb, strength_volume, cardio_minutes, rpe, notes)
             VALUES (?,?,?,?,?,?,?,?,?)"
        );

        $stmt->bind_param(
            'isssdiiis',
            $uid, $log_date, $mood, $focus, $w, $v, $c, $r, $notes
        );

        if ($stmt->execute()) {
            $success = 'Progress saved!';

            // Clear form fields after successful save
            $log_date = '';
            $mood = '';
            $focus = '';
            $weight = '';
            $volume = '';
            $cardio = '';
            $rpe = '';
            $notes = '';
        } else {
            $errors[] = 'Error saving progress. Please try again.';
        }

        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Progress Log</h2>
<p>Record weight, training volume, cardio, and a quick note.</p>

<?php if (!empty($errors)): ?>
    <div style="color:#b71c1c; background:#ffe5e5; padding:8px; border-radius:5px; margin-bottom:10px;">
        <strong>Fix these:</strong>
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div style="color:#0b6623; background:#e6ffea; padding:8px; border-radius:5px; margin-bottom:10px;">
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<form method="post" action="progress_new.php" novalidate>
    <label for="log_date">Date *</label>
    <input type="date" id="log_date" name="log_date"
           value="<?php echo htmlspecialchars($log_date); ?>" required>

    <label for="mood">How did it feel? *</label>
    <select id="mood" name="mood" required>
        <option value="">Select...</option>
        <option <?php if($mood==='Great') echo 'selected'; ?>>Great</option>
        <option <?php if($mood==='Okay') echo 'selected'; ?>>Okay</option>
        <option <?php if($mood==='Tired') echo 'selected'; ?>>Tired</option>
        <option <?php if($mood==='Rough') echo 'selected'; ?>>Rough</option>
    </select>

    <label for="focus">Main focus *</label>
    <select id="focus" name="focus" required>
        <option value="">Choose...</option>
        <option <?php if($focus==='Weight') echo 'selected'; ?>>Weight</option>
        <option <?php if($focus==='Strength') echo 'selected'; ?>>Strength</option>
        <option <?php if($focus==='Cardio') echo 'selected'; ?>>Cardio</option>
        <option <?php if($focus==='Mixed') echo 'selected'; ?>>Mixed</option>
    </select>

    <label for="weight">Weight (lbs) *</label>
    <input type="number" id="weight" name="weight" step="0.1"
           value="<?php echo htmlspecialchars($weight); ?>" required>

    <label for="volume">Strength volume (lbs) *</label>
    <input type="number" id="volume" name="volume"
           value="<?php echo htmlspecialchars($volume); ?>" required>
    <small>Estimate sets × reps × weight for the session.</small><br><br>

    <label for="cardio">Cardio (minutes) *</label>
    <input type="number" id="cardio" name="cardio"
           value="<?php echo htmlspecialchars($cardio); ?>" required>

    <label for="rpe">Session RPE (1–10) *</label>
    <input type="number" id="rpe" name="rpe"
           value="<?php echo htmlspecialchars($rpe); ?>" required>

    <label for="notes">Progress notes *</label>
    <textarea id="notes" name="notes" required><?php echo htmlspecialchars($notes); ?></textarea>

    <br>
    <input type="submit" value="Save">
</form>

<?php include 'includes/footer.php'; ?>
