<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Journal Workflow • Superadmin</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    :root {
      --sidebar: #0f172a;
      --sidebar-dark: #020617;
      --hover: #1e293b;
      --text: #e2e8f0;
      --muted: #94a3b8;
      --bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --card-bg: #ffffff;
      --pending: #f59e0b;
      --progress: #3b82f6;
      --done: #10b981;
      --primary: #6366f1;
      --accent: #ec4899;
      --shadow: rgba(0, 0, 0, 0.1);
    }

    * { margin:0; padding:0; box-sizing:border-box; }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: #1e293b;
      height: 100vh;
      display: grid;
      grid-template-columns: 360px 1fr;
      overflow: hidden;
    }

    .sidebar {
      background: var(--sidebar);
      color: var(--text);
      overflow-y: auto;
      padding: 0;
      box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    .sidebar::-webkit-scrollbar { width: 8px; }
    .sidebar::-webkit-scrollbar-track { background: var(--sidebar-dark); }
    .sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }

    .sidebar-header {
      padding: 2rem 1.5rem;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      border-bottom: 3px solid rgba(255,255,255,0.1);
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .title {
      font-size: 1.4rem;
      font-weight: 800;
      color: white;
      text-align: center;
      letter-spacing: -0.5px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .title i { font-size: 1.6rem; }

    .menu-item {
      padding: 1rem 1.5rem;
      cursor: pointer;
      color: #cbd5e1;
      transition: all 0.2s;
      border-left: 4px solid transparent;
      position: relative;
    }

    .menu-item:hover {
      background: var(--hover);
      color: white;
      border-left-color: var(--primary);
    }

    .menu-item.open {
      background: var(--hover);
      color: white;
      border-left-color: var(--accent);
    }

    .stage-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
    }

    .stage-name {
      flex: 1;
      font-weight: 600;
      font-size: 0.95rem;
    }

    .stage-meta {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .stage-progress {
      width: 60px;
      height: 8px;
      background: #1e293b;
      border-radius: 4px;
      overflow: hidden;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.3);
    }

    .stage-progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #10b981, #34d399);
      transition: width 0.4s ease;
      box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
    }

    .stage-count {
      font-size: 0.8rem;
      color: var(--muted);
      font-weight: 500;
      min-width: 35px;
      text-align: right;
    }

    .chevron {
      transition: transform 0.3s;
      font-size: 0.85rem;
      color: var(--muted);
    }
    .menu-item.open .chevron {
      transform: rotate(180deg);
      color: var(--accent);
    }

    .submenu {
      display: none;
      background: var(--sidebar-dark);
      border-left: 2px solid #1e293b;
    }
    .submenu.open { display: block; }

    .task {
      padding: 0.7rem 2.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: var(--muted);
      cursor: pointer;
      transition: all 0.15s;
      border-left: 2px solid transparent;
      font-size: 0.9rem;
    }

    .task:hover {
      background: var(--hover);
      color: var(--text);
      border-left-color: var(--primary);
      padding-left: 2.7rem;
    }

    .status {
      padding: 4px 12px;
      border-radius: 16px;
      font-size: 0.7rem;
      font-weight: 700;
      min-width: 90px;
      text-align: center;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .status-pending  { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }
    .status-inprogress { background: linear-gradient(135deg, #3b82f6, #60a5fa); color: white; }
    .status-completed   { background: linear-gradient(135deg, #10b981, #34d399); color: white; }

    .main-content {
      padding: 2.5rem;
      overflow-y: auto;
      background: rgba(255,255,255,0.05);
    }

    .main-content::-webkit-scrollbar { width: 10px; }
    .main-content::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
    .main-content::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 5px; }

    .page-header {
      margin-bottom: 2rem;
    }

    .page-title {
      font-size: 2.5rem;
      font-weight: 800;
      color: white;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
      margin-bottom: 0.5rem;
    }

    .page-subtitle {
      color: rgba(255,255,255,0.9);
      font-size: 1.1rem;
      font-weight: 500;
    }

    .card {
      background: var(--card-bg);
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.15);
      padding: 2rem;
      margin-bottom: 2rem;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.2);
    }

    .overall-progress {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .progress-label {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.8rem;
      font-weight: 700;
      font-size: 1.1rem;
    }

    .progress-container {
      height: 16px;
      background: rgba(255,255,255,0.2);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #10b981, #34d399, #6ee7b7);
      transition: width 0.5s ease;
      box-shadow: 0 0 16px rgba(16, 185, 129, 0.6);
      position: relative;
      overflow: hidden;
    }

    .progress-fill::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    .papers-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.5rem;
      margin-top: 1.5rem;
    }

    .paper-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      transition: all 0.3s;
      cursor: pointer;
      border: 2px solid transparent;
    }

    .paper-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      border-color: var(--primary);
    }

    .paper-card.selected {
      border-color: var(--accent);
      box-shadow: 0 8px 24px rgba(236, 72, 153, 0.3);
    }

    .paper-title {
      font-weight: 700;
      font-size: 1rem;
      color: #1e293b;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .paper-id {
      font-size: 0.75rem;
      color: var(--muted);
      font-weight: 600;
      background: #f1f5f9;
      padding: 2px 8px;
      border-radius: 4px;
    }

    .paper-progress {
      margin-top: 1rem;
    }

    .paper-progress-label {
      font-size: 0.8rem;
      color: #64748b;
      margin-bottom: 0.4rem;
      display: flex;
      justify-content: space-between;
    }

    .paper-progress-bar {
      height: 8px;
      background: #e2e8f0;
      border-radius: 4px;
      overflow: hidden;
    }

    .paper-progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #6366f1, #8b5cf6);
      transition: width 0.4s ease;
    }

    .task-detail-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 2rem;
    }

    .task-detail-title h2 {
      font-size: 2rem;
      color: #1e293b;
      margin-bottom: 0.5rem;
    }

    .task-detail-meta {
      display: flex;
      gap: 1rem;
      margin-top: 0.5rem;
      flex-wrap: wrap;
    }

    .meta-badge {
      padding: 0.5rem 1rem;
      background: #f1f5f9;
      border-radius: 8px;
      font-size: 0.85rem;
      color: #475569;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .meta-badge i { color: var(--primary); }

    .action-buttons {
      margin-top: 2rem;
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    button {
      padding: 0.9rem 1.8rem;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: 700;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.2s;
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    button:active {
      transform: translateY(0);
    }

    button:disabled {
      opacity: 0.5;
      cursor: not-allowed;
      transform: none;
    }

    button.secondary {
      background: linear-gradient(135deg, #64748b, #94a3b8);
      box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }

    button.success {
      background: linear-gradient(135deg, #10b981, #34d399);
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      text-align: center;
    }

    .stat-value {
      font-size: 2rem;
      font-weight: 800;
      color: var(--primary);
      margin-bottom: 0.5rem;
    }

    .stat-label {
      font-size: 0.9rem;
      color: #64748b;
      font-weight: 600;
    }

    .add-paper-btn {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: linear-gradient(135deg, #ec4899, #f472b6);
      box-shadow: 0 8px 24px rgba(236, 72, 153, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      z-index: 100;
      padding: 0;
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.6);
      backdrop-filter: blur(4px);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .modal.open { display: flex; }

    .modal-content {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      max-width: 500px;
      width: 90%;
      box-shadow: 0 16px 48px rgba(0,0,0,0.3);
    }

    .modal-header {
      font-size: 1.5rem;
      font-weight: 800;
      margin-bottom: 1.5rem;
      color: #1e293b;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: #475569;
    }

    .form-group input {
      width: 100%;
      padding: 0.8rem;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      font-size: 1rem;
      transition: border 0.2s;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--primary);
    }

    .modal-actions {
      display: flex;
      gap: 1rem;
      justify-content: flex-end;
      margin-top: 2rem;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="sidebar-header">
    <div class="title">
      <i class="fas fa-journal-whills"></i>
      Journal Workflow
    </div>
  </div>
  <!-- Stages are built dynamically in JS -->
</div>

<div class="main-content">
  <div class="page-header">
    <h1 class="page-title">Superadmin Dashboard</h1>
    <p class="page-subtitle">Manage and track all manuscript submissions</p>
  </div>

  <div class="card overall-progress">
    <div class="progress-label">
      <span><i class="fas fa-chart-line"></i> Overall Progress</span>
      <span id="overallPercent">0%</span>
    </div>
    <div class="progress-container">
      <div class="progress-fill" id="overallProgress" style="width: 0%"></div>
    </div>
  </div>

  <div class="stats-grid" id="statsGrid">
    <div class="stat-card">
      <div class="stat-value" id="totalPapers">0</div>
      <div class="stat-label">Total Papers</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" id="completedPapers">0</div>
      <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" id="inProgressPapers">0</div>
      <div class="stat-label">In Progress</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" id="pendingPapers">0</div>
      <div class="stat-label">Pending</div>
    </div>
  </div>

  <div class="card" id="mainContent">
    <h2>📋 Recent Papers</h2>
    <div class="papers-grid" id="papersGrid"></div>
  </div>
</div>

<button class="add-paper-btn" onclick="openAddPaperModal()">
  <i class="fas fa-plus"></i>
</button>

<div class="modal" id="addPaperModal">
  <div class="modal-content">
    <div class="modal-header">
      <i class="fas fa-file-alt"></i> Add New Paper
    </div>
    <div class="form-group">
      <label>Paper Title</label>
      <input type="text" id="paperTitle" placeholder="Enter paper title...">
    </div>
    <div class="form-group">
      <label>Reference ID</label>
      <input type="text" id="paperId" placeholder="e.g., MS-2026-001">
    </div>
    <div class="modal-actions">
      <button class="secondary" onclick="closeAddPaperModal()">
        <i class="fas fa-times"></i> Cancel
      </button>
      <button onclick="addNewPaper()">
        <i class="fas fa-check"></i> Add Paper
      </button>
    </div>
  </div>
</div>

<script>
// ──────────────────────────────────────────────
// DATA STRUCTURE
// ──────────────────────────────────────────────
/*
 * Injected from Blade Controller
 */
const workflow = @json($workflow);
const initialPapers = @json($papers);

// ──────────────────────────────────────────────
// STATE MANAGEMENT
// ──────────────────────────────────────────────
let papers = initialPapers || [];

// If no papers passed from backend (or empty), try localStorage or default mock
// For production, you probably want to rely purely on backend data.
// Here we merge or fallback for the prototype feel:
if (papers.length === 0) {
    const stored = localStorage.getItem('journalPapers');
    if (stored) {
        papers = JSON.parse(stored);
    } else {
        // Fallback or empty
        papers = [];
    }
}

// Ensure the locally managed papers logic works (statuses field etc)
let selectedPaper = papers.length > 0 ? papers[0] : null;

// ──────────────────────────────────────────────
// BUILD SIDEBAR
// ──────────────────────────────────────────────
function buildSidebar() {
  const sidebar = document.querySelector('.sidebar');
  const existingStages = sidebar.querySelectorAll('.menu-item, .submenu');
  existingStages.forEach(el => el.remove());

  workflow.forEach(stage => {
    const menuItem = document.createElement('div');
    menuItem.className = 'menu-item';
    menuItem.setAttribute('data-target', stage.id);

    const completedInStage = getStageCompletedCount(stage.id);
    const totalInStage = stage.tasks.length;
    const progressPercent = totalInStage > 0 ? Math.round((completedInStage / totalInStage) * 100) : 0;

    menuItem.innerHTML = `
      <div class="stage-header">
        <span class="stage-name">${stage.name}</span>
        <div class="stage-meta">
          <span class="stage-count">${completedInStage}/${totalInStage}</span>
          <div class="stage-progress">
            <div class="stage-progress-fill" style="width: ${progressPercent}%"></div>
          </div>
        </div>
        <i class="fas fa-chevron-down chevron"></i>
      </div>
    `;

    const submenu = document.createElement('div');
    submenu.className = 'submenu';
    submenu.id = stage.id;

    stage.tasks.forEach(taskName => {
      const taskEl = document.createElement('div');
      taskEl.className = 'task';
      taskEl.dataset.task = taskName;
      taskEl.dataset.stage = stage.id;
      taskEl.innerHTML = `
        <span>${taskName}</span>
      `;
      taskEl.addEventListener('click', () => showTaskDetail(taskName, stage.name, stage.id));
      submenu.appendChild(taskEl);
    });

    sidebar.appendChild(menuItem);
    sidebar.appendChild(submenu);
  });

  updateOverallProgress();
}

// ──────────────────────────────────────────────
// HELPERS
// ──────────────────────────────────────────────
function toSlug(str) {
  return str.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
}

// ──────────────────────────────────────────────
// STAGE PROGRESS CALCULATIONS
// ──────────────────────────────────────────────
function getStageCompletedCount(stageId) {
  if (!selectedPaper) return 0;
  const stage = workflow.find(s => s.id === stageId);
  if (!stage) return 0;

  return stage.tasks.filter(t => {
    // Check slugged status
    const status = (selectedPaper.statuses && selectedPaper.statuses[toSlug(t)]) || '';
    return status === 'completed';
  }).length;
}

function getPaperProgress(paper) {
  const totalTasks = workflow.reduce((sum, stage) => sum + stage.tasks.length, 0);
  const completedTasks = workflow.reduce((sum, stage) => {
    return sum + stage.tasks.filter(t => {
      const status = (paper.statuses && paper.statuses[toSlug(t)]) || '';
      return status === 'completed';
    }).length;
  }, 0);

  return totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;
}

function updateOverallProgress() {
  if (papers.length === 0) {
    document.getElementById('overallProgress').style.width = '0%';
    document.getElementById('overallPercent').textContent = '0%';
    return;
  }

  const totalProgress = papers.reduce((sum, paper) => sum + getPaperProgress(paper), 0);
  const avgProgress = Math.round(totalProgress / papers.length);

  document.getElementById('overallProgress').style.width = avgProgress + '%';
  document.getElementById('overallPercent').textContent = avgProgress + '%';
}

// ──────────────────────────────────────────────
// UPDATE STATS
// ──────────────────────────────────────────────
function updateStats() {
  document.getElementById('totalPapers').textContent = papers.length;

  const completed = papers.filter(p => getPaperProgress(p) === 100).length;
  const inProgress = papers.filter(p => {
    const prog = getPaperProgress(p);
    return prog > 0 && prog < 100;
  }).length;
  const pending = papers.filter(p => getPaperProgress(p) === 0).length;

  document.getElementById('completedPapers').textContent = completed;
  document.getElementById('inProgressPapers').textContent = inProgress;
  document.getElementById('pendingPapers').textContent = pending;
}

// ──────────────────────────────────────────────
// SHOW PAPERS GRID
// ──────────────────────────────────────────────
function showPapersGrid() {
  const grid = document.getElementById('papersGrid');
  grid.innerHTML = '';

  if (papers.length === 0) {
    grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #94a3b8;">No papers yet. Click the + button to add one.</p>';
    return;
  }

  papers.forEach(paper => {
    const progress = getPaperProgress(paper);
    const card = document.createElement('div');
    card.className = 'paper-card';
    if (selectedPaper && selectedPaper.id === paper.id) {
      card.classList.add('selected');
    }

    card.innerHTML = `
      <div class="paper-title">
        <i class="fas fa-file-alt"></i>
        ${paper.title}
      </div>
      <div class="paper-id">${paper.id}</div>
      <div class="paper-progress">
        <div class="paper-progress-label">
          <span>Progress</span>
          <span>${progress}%</span>
        </div>
        <div class="paper-progress-bar">
          <div class="paper-progress-fill" style="width: ${progress}%"></div>
        </div>
      </div>
    `;

    card.addEventListener('click', () => selectPaper(paper));
    grid.appendChild(card);
  });
}

// ──────────────────────────────────────────────
// SELECT PAPER
// ──────────────────────────────────────────────
function selectPaper(paper) {
  selectedPaper = paper;
  buildSidebar();
  showPapersGrid();
  updateStats();
}

// ──────────────────────────────────────────────
// TASK DETAIL VIEW
// ──────────────────────────────────────────────
function showTaskDetail(taskName, stageName, stageId) {
  if (!selectedPaper) {
    alert('Please select a paper first');
    return;
  }

  const stage = workflow.find(s => s.id === stageId);
  const taskUrl = (stage.task_urls && stage.task_urls[taskName]) ? stage.task_urls[taskName] : null;

  const currentPaperStatus = selectedPaper.statuses ? selectedPaper.statuses : {};
  // Lookup via slug
  const statusSlug = currentPaperStatus[toSlug(taskName)] || 'pending';

  // Create display label from slug/value
  let currentStatus = 'Pending';
  if (statusSlug === 'completed') currentStatus = 'Completed';
  else if (statusSlug === 'in-progress') currentStatus = 'In Progress';
  else if (statusSlug) currentStatus = statusSlug.charAt(0).toUpperCase() + statusSlug.slice(1);

  const mainContent = document.getElementById('mainContent');

  let contentHtml = `
    <div class="task-detail-header">
      <div class="task-detail-title">
        <h2><i class="fas fa-tasks"></i> ${taskName}</h2>
        <div class="task-detail-meta">
          <div class="meta-badge">
            <i class="fas fa-layer-group"></i>
            <span>${stageName}</span>
          </div>
          <div class="meta-badge">
            <i class="fas fa-file-alt"></i>
            <span>${selectedPaper.id}</span>
          </div>
          <span class="status status-${statusSlug}">${currentStatus}</span>
        </div>
      </div>
    </div>
  `;

  if (taskUrl) {
    // Render Iframe for Real Items
    contentHtml += `
      <div style="width: 100%; height: 600px; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; margin-bottom: 1.5rem;">
        <iframe src="${taskUrl}" style="width: 100%; height: 100%; border: none;"></iframe>
      </div>
    `;
  } else {
    // Default Placeholders
    contentHtml += `
      <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
        <h3 style="margin-bottom: 1rem; color: #475569;">
          <i class="fas fa-clipboard-list"></i> Paper Details
        </h3>
        <p><strong>Title:</strong> ${selectedPaper.title}</p>
        <p style="margin-top: 0.5rem;"><strong>Overall Progress:</strong> ${getPaperProgress(selectedPaper)}%</p>
      </div>
    `;
  }

  contentHtml += `
    <div class="action-buttons">
      ${statusSlug === 'completed'
        ? '<button disabled><i class="fas fa-check-circle"></i> Already Completed</button>'
        : `
          <button onclick="updateTaskStatus('${taskName}', '${stageId}', 'In Progress')">
            <i class="fas fa-play"></i> ${statusSlug === 'in-progress' ? 'Continue' : 'Start'} Task
          </button>
          <button class="success" onclick="updateTaskStatus('${taskName}', '${stageId}', 'Completed')">
            <i class="fas fa-check"></i> Mark as Completed
          </button>
        `
      }
      <button class="secondary" onclick="backToPapers()">
        <i class="fas fa-arrow-left"></i> Back to Papers
      </button>
    </div>
  `;

  mainContent.innerHTML = contentHtml;
}

function backToPapers() {
  selectedPaper = null;
  document.getElementById('mainContent').innerHTML = `
    <h2>📋 Recent Papers</h2>
    <div class="papers-grid" id="papersGrid"></div>
  `;
  showPapersGrid();
  buildSidebar();
  updateStats();
}

// ──────────────────────────────────────────────
// UPDATE TASK STATUS
// ──────────────────────────────────────────────
function updateTaskStatus(taskName, stageId, newStatus) {
  if (!selectedPaper) return;

  // Optimistic UI Update
  if (!selectedPaper.statuses) selectedPaper.statuses = {};
  // Store slugged status on client to match server
  selectedPaper.statuses[toSlug(taskName)] = toSlug(newStatus);

  // AJAX CALL
  fetch('{{ route("admin.journal.workflow.update") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        submission_id: selectedPaper.submission_id,
        task_name: taskName,
        status: newStatus,
        stage_id: stageId
      })
  })
  .then(res => res.json())
  .then(data => {
      if (data.success) {
        // Success
      } else {
        alert('Failed to save status');
        // Revert optimistic update?
      }
  })
  .catch(err => console.error(err));

  buildSidebar();
  updateStats();

  const stage = workflow.find(s => s.id === stageId);
  showTaskDetail(taskName, stage.name, stageId);
}

// ──────────────────────────────────────────────
// ADD NEW PAPER MODAL
// ──────────────────────────────────────────────
function openAddPaperModal() {
  document.getElementById('addPaperModal').classList.add('open');
  document.getElementById('paperTitle').value = '';
  document.getElementById('paperId').value = '';
}

function closeAddPaperModal() {
  document.getElementById('addPaperModal').classList.remove('open');
}

function addNewPaper() {
  const title = document.getElementById('paperTitle').value.trim();
  const id = document.getElementById('paperId').value.trim();

  if (!title || !id) {
    alert('Please fill in all fields');
    return;
  }

  if (papers.find(p => p.id === id)) {
    alert('Paper with this ID already exists');
    return;
  }

  // AJAX CALL
  fetch('{{ route("admin.journal.workflow.store") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        title: title,
        id: id
      })
  })
  .then(res => res.json())
  .then(data => {
      if (data.success) {
          // Create Object with returned submission_id
          const newPaper = {
            id,
            title,
            submission_id: data.submission_id,
            statuses: {}
          };

          papers.push(newPaper);
          selectedPaper = newPaper;
          closeAddPaperModal();
          buildSidebar();
          showPapersGrid();
          updateStats();
      } else {
          alert('Failed to add paper: ' + (data.message || 'Unknown error'));
      }
  })
  .catch(err => {
      console.error(err);
      alert('Error connecting to server');
  });
}

// ──────────────────────────────────────────────
// TOGGLE SUBMENUS
// ──────────────────────────────────────────────
document.addEventListener('click', e => {
  const item = e.target.closest('.menu-item');
  if (!item) return;
  e.stopPropagation();

  const targetId = item.dataset.target;
  const submenu = document.getElementById(targetId);
  if (!submenu) return;

  const isOpen = submenu.classList.toggle('open');
  item.classList.toggle('open', isOpen);
});

// Close modal on outside click
document.getElementById('addPaperModal').addEventListener('click', e => {
  if (e.target.id === 'addPaperModal') {
    closeAddPaperModal();
  }
});

// ──────────────────────────────────────────────
// INITIALIZE
// ──────────────────────────────────────────────
buildSidebar();
showPapersGrid();
updateStats();
</script>
</body>
</html>
