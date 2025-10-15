<?php
session_start();
include("db.php");
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "teacher") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher • Complaints • BNMIT</title>
  <link rel="stylesheet" href="style0.css">
</head>
<body>
  <header class="app-header">
    <img src="BNMITlogo.jpeg" class="logo" alt="BNMIT">
    <div class="brand">
      <span class="title">B. N. M. Institute of Technology</span>
      <span class="subtitle">Complaint Management</span>
    </div>
    <div class="page-title">Teacher Dashboard</div>
  </header>

  <main class="container">
    <!-- Toolbar mimicking your screenshot’s top controls -->
    <div class="toolbar">
      <div class="field" style="flex: 0 0 260px;">
        <label for="filterSubject">Subject</label>
        <select id="filterSubject">
          <option value="">All Subjects</option>
        </select>
      </div>
      <div class="field" style="flex: 0 0 220px;">
        <label for="filterDate">Date</label>
        <input id="filterDate" type="date">
      </div>
      <div style="margin-left:auto">
        <button id="clearFilters" class="btn btn-secondary">Clear Filters</button>
      </div>
    </div>

    <!-- Grid like attendance cards -->
    <div id="grid" class="complaint-grid"></div>
  </main>

  <script>
  // Guard: require login from unified page
  if(!localStorage.getItem('loggedInTeacher')){
    // If you want to relax this during dev, comment the next line
    // window.location.href = 'index.html';
  }

  const grid = document.getElementById('grid');
  const filterSubject = document.getElementById('filterSubject');
  const filterDate = document.getElementById('filterDate');
  const clearFilters = document.getElementById('clearFilters');

  function loadOptions(complaints){
    const subjects = Array.from(new Set(complaints.map(c=>c.subject))).sort();
    subjects.forEach(s=>{
      const opt = document.createElement('option');
      opt.value = s; opt.textContent = s;
      filterSubject.appendChild(opt);
    });
  }

  function passDateFilter(c){
    const d = filterDate.value;
    if(!d) return true;
    // Compare only YYYY-MM-DD
    const only = (iso)=>iso.substring(0,10);
    return only(c.createdAt) === d;
  }

  function render(){
    const list = JSON.parse(localStorage.getItem('complaints') || '[]');
    grid.innerHTML = '';

    if(grid.dataset.initialized!=='1'){ loadOptions(list); grid.dataset.initialized='1'; }

    const items = list.filter(c=>{
      const subjOk = !filterSubject.value || c.subject === filterSubject.value;
      const dateOk = passDateFilter(c);
      return subjOk && dateOk;
    });

    if(items.length === 0){
      grid.innerHTML = '<div class="card">No complaints match the filters.</div>';
      return;
    }

    items.forEach((c, idx)=>{
      const card = document.createElement('div');
      card.className = 'complaint-card';

      const statusChipClass = c.status.toLowerCase()==='resolved' ? 'resolved' : 'pending';

      card.innerHTML = `
        <div class="card-top">
          <div class="avatar">👤</div>
          <div>
            <div class="name">${c.name}</div>
            <div class="code">${c.studentCode || ''}</div>
            <div class="subject"><strong>Subject:</strong> ${c.subject}</div>
          </div>
          <div class="status-chip ${statusChipClass}" title="Current status">${c.status}</div>
        </div>

        <div class="card-actions">
          <button class="btn btn-green" data-action="resolve">Resolve</button>
          <button class="btn btn-red" data-action="pending">Pending</button>
          <button class="btn btn-ghost" data-action="details">Details</button>
          <span class="small" style="margin-left:auto;">ID: ${c.id}</span>
        </div>

        <div class="details" id="d-${c.id}">
          ${c.description}
          <div class="small" style="margin-top:6px;">Created: ${new Date(c.createdAt).toLocaleString()}</div>
        </div>
      `;

      // Wire buttons
      card.querySelector('[data-action="details"]').addEventListener('click', ()=>{
        const panel = card.querySelector(`#d-${c.id}`);
        panel.classList.toggle('show');
      });

      card.querySelector('[data-action="resolve"]').addEventListener('click', ()=>{
        updateStatus(c.id, 'Resolved');  // like "Present"
      });

      card.querySelector('[data-action="pending"]').addEventListener('click', ()=>{
        updateStatus(c.id, 'Pending');   // like "Absent"
      });

      grid.appendChild(card);
    });
  }

  function updateStatus(id, status){
    const list = JSON.parse(localStorage.getItem('complaints') || '[]');
    const idx = list.findIndex(x=>x.id===id);
    if(idx>-1){
      list[idx].status = status;
      localStorage.setItem('complaints', JSON.stringify(list));
      render();
    }
  }

  filterSubject.addEventListener('change', render);
  filterDate.addEventListener('change', render);
  clearFilters.addEventListener('click', ()=>{
    filterSubject.value = '';
    filterDate.value = '';
    render();
  });

  render();
  </script>
</body>
</html>
