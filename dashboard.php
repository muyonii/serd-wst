<?php
// Protected page: checks session, INSERT (Create), SELECT (Read)
session_start();
include "mydb.php";

// protected page check
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION["username"];
$message = "";

// function used for status color (PHP logic)
function statusClass($status) {
    if ($status == "Pending") {
        return "badge pending";
    } elseif ($status == "Dispatched") {
        return "badge dispatched";
    }
    return "badge resolved";
}

// CREATE: receive form data with $_POST + isset()
if (isset($_POST["add_incident"])) {
    $type = $_POST["incident_type"];
    $location = trim($_POST["location"]);
    $description = trim($_POST["description"]);

    // INSERT query
    $stmt = mysqli_prepare($conn,
        "INSERT INTO incidents (incident_type, location, description, reported_by) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $type, $location, $description, $username);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Incident reported successfully!";
    } else {
        $message = "Error saving incident.";
    }
}

// READ: SELECT query
$result = mysqli_query($conn, "SELECT * FROM incidents ORDER BY id DESC");
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SERD - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h2>SERD.</h2>
        <div>
            Logged in as <strong><?php echo htmlspecialchars($username); ?></strong>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="container">

        <!-- FORM SECTION -->
        <div class="card">
            <h3>Report New Incident</h3>

            <?php if ($message != ""): ?>
                <div class="success"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" action="dashboard.php">
                <label for="incident_type">Incident Type</label>
                <select name="incident_type" id="incident_type" required>
                    <option value="Fire">Fire</option>
                    <option value="Medical">Medical</option>
                    <option value="Police">Police</option>
                </select>

                <label for="location">Location</label>
                <input type="text" name="location" id="location" placeholder="e.g. Brgy. Puerto Rivas Itaas" required>

                <label for="description">Description</label>
                <textarea name="description" id="description" rows="3"></textarea>

                <button type="submit" name="add_incident">Submit Incident</button>
            </form>
        </div>

        <!-- TABLE SECTION -->
        <div class="card">
            <h3>Incident Records (<?php echo $total; ?>)</h3>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Reported By</th>
                    <th>Date</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo htmlspecialchars($row["incident_type"]); ?></td>
                    <td><?php echo htmlspecialchars($row["location"]); ?></td>
                    <td><?php echo htmlspecialchars($row["description"]); ?></td>
                    <td><span class="<?php echo statusClass($row["status"]); ?>"><?php echo $row["status"]; ?></span></td>
                    <td><?php echo htmlspecialchars($row["reported_by"]); ?></td>
                    <td><?php echo $row["created_at"]; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

    </div>
</body>
</html>
