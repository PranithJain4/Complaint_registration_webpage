<?php
// Connect DB
include("db.php");
$conn = new mysqli("localhost", "root", "", "complaint_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$statusData = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cid = $_POST['cid'];

    // Fetch complaint by ID
    $sql = "SELECT * FROM complaints WHERE complaint_id = '$cid'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $statusData = $result->fetch_assoc();
    } else {
        $error = "No complaint found for that ID.";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Complaint Status • BNMIT</title>
  <link rel="stylesheet" href="style0.css">
</head>
<body>
  <header class="app-header">
    <img src="BNMITlogo.jpeg" class="logo" alt="BNMIT">
    <div class="brand">
      <span class="title">B. N. M. Institute of Technology</span>
      <span class="subtitle">Complaint Status</span>
    </div>
    <div class="page-title">Status</div>
  </header>

  <main class="container">
    <div class="card" style="max-width:520px; margin: 0 auto;">
      <form method="POST" action="">
        <label for="cid">Enter Complaint ID</label>
        <input id="cid" type="text" name="cid" placeholder="e.g., C1724153870123" required>
        <button class="btn btn-full" type="submit">Check Status</button>
      </form>

      <div id="result" style="margin-top:14px;">
        <?php if($error){ ?>
          <p style="color:#dc2626;"><?php echo $error; ?></p>
        <?php } elseif($statusData){ ?>
          <div class="card">
            <div><strong>Subject:</strong> <?php echo $statusData['subject']; ?></div>
            <div style="margin-top:6px;"><strong>Status:</strong>
              <span class="status-chip <?php echo strtolower($statusData['status'])==='resolved'?'resolved':'pending'; ?>">
                <?php echo $statusData['status']; ?>
              </span>
            </div>
            <div class="small" style="margin-top:6px;"><strong>ID:</strong> <?php echo $statusData['complaint_id']; ?></div>
            <div class="small"><strong>Student:</strong> <?php echo $statusData['student_name']; ?> • <?php echo $statusData['student_code']; ?></div>
            <p style="margin-top:10px;"><?php echo $statusData['description']; ?></p>
          </div>
        <?php } ?>
      </div>
    </div>
  </main>
</body>
</html>
