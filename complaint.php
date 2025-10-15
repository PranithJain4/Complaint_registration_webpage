<?php
session_start();
include("db.php");
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "student") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>File Complaint • BNMIT</title>
  <link rel="stylesheet" href="style0.css">
</head>
<body>
  <header class="app-header">
    <img src="BNMITlogo.jpeg" class="logo" alt="BNMIT">
    <div class="brand">
      <span class="title">B. N. M. Institute of Technology</span>
      <span class="subtitle">Complaint Portal</span>
    </div>
    <div class="page-title">Student • File Complaint</div>
  </header>

  <main class="container">
    <div class="card" style="max-width:640px; margin: 0 auto;">
      <div class="icon"><img src="complaint.png" alt="Complaint Icon"></div>

      <label for="sname">Name</label>
      <input id="sname" type="text" placeholder="Your Name">

      <label for="subj">Subject</label>
      <input id="subj" type="text" placeholder="E.g., Projector not working">

      <label for="desc">Description</label>
      <textarea id="desc" placeholder="Please describe the issue clearly..."></textarea>

      <button class="btn btn-orange btn-full" id="submitBtn">Submit Complaint</button>

      <p class="small" style="margin-top:12px;">
        After submission, you’ll get a Complaint ID. Use it on the <a href="status.html">Status page</a>.
      </p>
    </div>
  </main>


  <script>
  function randStudentCode(){
    // Fake display code like BNM25AIML7
    const groups = ['AIML','CSE','ECE','EEE'];
    const roll = Math.floor(Math.random()*9)+1;
    return `BNM25${groups[Math.floor(Math.random()*groups.length)]}${roll}`;
  }

  document.getElementById('submitBtn').addEventListener('click', () => {
    const name = document.getElementById('sname').value.trim();
    const subject = document.getElementById('subj').value.trim();
    const description = document.getElementById('desc').value.trim();

    if(!name || !subject || !description){
      alert('Please fill all fields.');
      return;
    }

    const complaint = {
      id: 'C' + Date.now(),
      name, subject, description,
      status: 'Pending',
      studentCode: randStudentCode(),
      createdAt: new Date().toISOString()
    };

    const list = JSON.parse(localStorage.getItem('complaints') || '[]');
    list.push(complaint);
    localStorage.setItem('complaints', JSON.stringify(list));

    alert(`Complaint submitted!\nYour Complaint ID: ${complaint.id}`);
    // Pre-fill ID for status page
    localStorage.setItem('lastComplaintId', complaint.id);
    // Clear form
    document.getElementById('sname').value='';
    document.getElementById('subj').value='';
    document.getElementById('desc').value='';
  });
  </script>
</body>
</html>
