<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | ResearchHub</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:       #0a0e1a;
            --ink-2:     #1e2535;
            --ink-3:     #374151;
            --muted:     #6b7280;
            --faint:     #9ca3af;
            --border:    #e5e7eb;
            --border-s:  #f3f4f6;
            --bg:        #f9fafb;
            --surface:   #ffffff;
            --accent:    #2563eb;
            --accent-s:  #eff6ff;
            --green:     #16a34a;
            --green-s:   #dcfce7;
            --red:       #dc2626;
            --red-s:     #fee2e2;
            --amber:     #d97706;
            --amber-s:   #fef3c7;
            --mono:      'DM Mono', monospace;
            --sans:      'DM Sans', sans-serif;
            --sidebar-w: 240px;
        }

        body {
            font-family: var(--sans);
            background: var(--bg);
            color: var(--ink);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            background: var(--ink);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-brand .logo {
            font-family: var(--mono);
            font-size: 0.95rem;
            font-weight: 500;
            color: #fff;
            letter-spacing: -0.3px;
        }
        .sidebar-brand .logo span {
            color: var(--accent);
        }
        .sidebar-brand .role-badge {
            display: inline-block;
            margin-top: 6px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            background: rgba(37,99,235,0.2);
            color: #93c5fd;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .nav-item:hover { background: rgba(255,255,255,0.06); color: #e2e8f0; }
        .nav-item.active { background: rgba(37,99,235,0.15); color: #fff; }
        .nav-item.active i { color: #60a5fa; }

        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #475569;
            padding: 12px 12px 6px;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            background: rgba(255,255,255,0.04);
            margin-bottom: 8px;
        }
        .admin-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; color: white;
            flex-shrink: 0;
        }
        .admin-name { font-size: 0.8rem; font-weight: 600; color: #e2e8f0; }
        .admin-role { font-size: 0.7rem; color: #64748b; }

        /* ── MAIN ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top bar */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--ink);
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Search bar */
        .search-wrap {
            position: relative;
        }
        .search-wrap input {
            padding: 8px 12px 8px 34px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.82rem;
            font-family: var(--sans);
            background: var(--bg);
            color: var(--ink);
            width: 220px;
            transition: all 0.2s;
        }
        .search-wrap input:focus { outline: none; border-color: var(--accent); background: white; width: 260px; }
        .search-wrap .si {
            position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
            color: var(--faint); pointer-events: none;
        }

        /* Content */
        .content {
            padding: 32px;
            flex: 1;
        }

        .page-header {
            margin-bottom: 28px;
        }
        .page-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--ink);
        }
        .page-header p {
            color: var(--muted);
            font-size: 0.875rem;
            margin-top: 4px;
        }

        /* ── STAT CARDS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .stat-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .stat-icon {
            width: 38px; height: 38px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            font-family: var(--mono);
            letter-spacing: -1px;
            line-height: 1;
        }
        .stat-label {
            font-size: 0.75rem;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .stat-delta {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 4px;
        }

        /* ── TABLE CARD ── */
        .table-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .table-toolbar {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-s);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .table-toolbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .table-toolbar h2 {
            font-size: 0.9rem;
            font-weight: 700;
        }
        .record-count {
            font-size: 0.72rem;
            background: var(--border-s);
            color: var(--muted);
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Filter pills */
        .filter-pills {
            display: flex;
            gap: 6px;
        }
        .filter-pill {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--muted);
            transition: all 0.15s;
            font-family: var(--sans);
        }
        .filter-pill:hover { border-color: var(--accent); color: var(--accent); }
        .filter-pill.active { background: var(--accent); color: white; border-color: var(--accent); }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.845rem;
        }
        thead tr {
            background: var(--bg);
            border-bottom: 1px solid var(--border);
        }
        th {
            padding: 11px 16px;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--faint);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            white-space: nowrap;
        }
        td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border-s);
            vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.1s; }
        tbody tr:hover td { background: var(--bg); }
        tbody tr.suspended td { opacity: 0.6; }

        /* User cell */
        .user-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            font-family: var(--mono);
        }
        .user-name { font-weight: 600; color: var(--ink); font-size: 0.875rem; }
        .user-email { font-size: 0.75rem; color: var(--muted); font-family: var(--mono); }

        .mono-cell { font-family: var(--mono); font-size: 0.8rem; color: var(--ink-3); }

        /* Status badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-active    { background: var(--green-s); color: var(--green); }
        .badge-suspended { background: var(--red-s);   color: var(--red); }
        .badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
        }
        .badge-active .badge-dot    { background: var(--green); }
        .badge-suspended .badge-dot { background: var(--red); }

        /* Toggle switch */
        .toggle {
            position: relative;
            width: 40px; height: 22px;
            cursor: pointer;
            flex-shrink: 0;
        }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-track {
            position: absolute;
            inset: 0;
            border-radius: 11px;
            background: #d1d5db;
            transition: background 0.2s;
        }
        .toggle input:checked + .toggle-track { background: var(--green); }
        .toggle-thumb {
            position: absolute;
            top: 3px; left: 3px;
            width: 16px; height: 16px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            transition: transform 0.2s;
        }
        .toggle input:checked ~ .toggle-thumb { transform: translateX(18px); }
        .toggle.loading .toggle-track { background: var(--amber); }

        /* Action cell */
        .action-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: flex-end;
        }
        .icon-btn {
            background: none;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 5px 8px;
            cursor: pointer;
            color: var(--muted);
            display: flex; align-items: center;
            transition: all 0.15s;
            font-family: var(--sans);
            font-size: 0.78rem;
            gap: 5px;
        }
        .icon-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-s); }
        .icon-btn.danger:hover { border-color: var(--red); color: var(--red); background: var(--red-s); }

        /* Toast */
        #toast {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            padding: 12px 18px; border-radius: 10px;
            font-size: 0.85rem; font-family: var(--sans); font-weight: 500;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            transform: translateY(60px); opacity: 0;
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            pointer-events: none;
            display: flex; align-items: center; gap: 8px;
        }
        #toast.show { transform: translateY(0); opacity: 1; }
        #toast.success { background: #052e16; color: #86efac; }
        #toast.error   { background: #450a0a; color: #fca5a5; }
        #toast.info    { background: var(--ink); color: #e2e8f0; }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--faint);
        }
        .empty-state i { margin-bottom: 12px; }
        .empty-state p { font-size: 0.875rem; }

        /* Confirm modal */
        #confirm-modal {
            display: none;
            position: fixed; inset: 0;
            background: rgba(10,14,26,0.7);
            z-index: 9998;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }
        #confirm-modal.open { display: flex; }
        .confirm-box {
            background: var(--surface);
            border-radius: 14px;
            padding: 28px;
            width: 380px;
            box-shadow: 0 24px 48px rgba(0,0,0,0.2);
        }
        .confirm-box h3 { font-size: 1rem; font-weight: 700; margin-bottom: 8px; }
        .confirm-box p  { font-size: 0.875rem; color: var(--muted); margin-bottom: 20px; line-height: 1.5; }
        .confirm-actions { display: flex; gap: 10px; justify-content: flex-end; }
        .btn {
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-family: var(--sans);
            transition: all 0.15s;
        }
        .btn-ghost  { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
        .btn-ghost:hover { background: var(--border-s); }
        .btn-danger { background: var(--red); color: white; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: #1d4ed8; }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-top: 1px solid var(--border-s);
        }
        .pagination-info { font-size: 0.78rem; color: var(--muted); }
        .pagination-btns { display: flex; gap: 6px; }
        .page-btn {
            width: 32px; height: 32px;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: var(--surface);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            color: var(--muted);
            transition: all 0.15s;
        }
        .page-btn:hover { border-color: var(--accent); color: var(--accent); }
        .page-btn.active { background: var(--accent); color: white; border-color: var(--accent); }
        .page-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    </style>
</head>
<body>

<?php

// Calculate stats
$totalUsers     = count($users);
$suspendedUsers = 0;
$adminUsers     = 0;
foreach ($users as $u) {
    if ($u['is_suspended'] === 't' || $u['is_suspended'] === true) $suspendedUsers++;
    if (($u['role'] ?? '') === 'admin') $adminUsers++;
}
$activeUsers = $totalUsers - $suspendedUsers;

// Avatar color palette
$avatarColors = ['#2563eb','#7c3aed','#db2777','#059669','#d97706','#dc2626','#0891b2','#65a30d'];
function avatarColor($str, $colors) {
    return $colors[abs(crc32($str)) % count($colors)];
}
function initials($name) {
    $parts = explode(' ', trim($name));
    return strtoupper(substr($parts[0],0,1) . (isset($parts[1]) ? substr($parts[1],0,1) : ''));
}
?>

<!-- Toast -->
<div id="toast"></div>

<!-- Confirm Modal -->
<div id="confirm-modal">
    <div class="confirm-box">
        <h3 id="confirm-title">Confirm Action</h3>
        <p id="confirm-body">Are you sure?</p>
        <div class="confirm-actions">
            <button class="btn btn-ghost" onclick="closeConfirm()">Cancel</button>
            <button class="btn btn-danger" id="confirm-ok">Confirm</button>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo">Research<span>Hub</span></div>
        <div class="role-badge">Admin Panel</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Management</div>
        <a href="index.php?action=admin-dashboard" class="nav-item active">
            <i data-lucide="users" style="width:16px;"></i> Users
        </a>
        <a href="#" class="nav-item">
            <i data-lucide="file-text" style="width:16px;"></i> Documents
        </a>
        <a href="#" class="nav-item">
            <i data-lucide="bar-chart-2" style="width:16px;"></i> Analytics
        </a>

        <div class="nav-section-label" style="margin-top:8px;">System</div>
        <a href="#" class="nav-item">
            <i data-lucide="settings" style="width:16px;"></i> Settings
        </a>
        <a href="#" class="nav-item">
            <i data-lucide="shield" style="width:16px;"></i> Security
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-info">
            <div class="admin-avatar"><?= initials($_SESSION['user_email'] ?? 'Admin') ?></div>
            <div>
                <div class="admin-name"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></div>
                <div class="admin-role">Super Admin</div>
            </div>
        </div>
        <a href="index.php?action=logout" class="nav-item" style="color:#f87171;">
            <i data-lucide="log-out" style="width:16px;"></i> Logout
        </a>
    </div>
</aside>

<!-- MAIN -->
<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-title">User Management</div>
        <div class="topbar-right">
            <div class="search-wrap">
                <i data-lucide="search" class="si" style="width:14px;"></i>
                <input type="text" id="user-search" placeholder="Search users…" oninput="filterTable(this.value)">
            </div>
        </div>
    </div>

    <div class="content">

        <!-- Page Header -->
        <div class="page-header">
            <h1>All Users</h1>
            <p>Manage accounts, monitor activity, and control access.</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value" style="color:var(--ink);"><?= $totalUsers ?></div>
                    </div>
                    <div class="stat-icon" style="background:#eff6ff;">
                        <i data-lucide="users" style="width:18px;color:var(--accent);"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-label">Active</div>
                        <div class="stat-value" style="color:var(--green);"><?= $activeUsers ?></div>
                    </div>
                    <div class="stat-icon" style="background:var(--green-s);">
                        <i data-lucide="user-check" style="width:18px;color:var(--green);"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-label">Suspended</div>
                        <div class="stat-value" style="color:var(--red);"><?= $suspendedUsers ?></div>
                    </div>
                    <div class="stat-icon" style="background:var(--red-s);">
                        <i data-lucide="user-x" style="width:18px;color:var(--red);"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-top">
                    <div>
                        <div class="stat-label">Admins</div>
                        <div class="stat-value" style="color:var(--amber);"><?= $adminUsers ?></div>
                    </div>
                    <div class="stat-icon" style="background:var(--amber-s);">
                        <i data-lucide="shield" style="width:18px;color:var(--amber);"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-toolbar">
                <div class="table-toolbar-left">
                    <h2>Users</h2>
                    <span class="record-count" id="record-count"><?= $totalUsers ?> records</span>
                </div>
                <div class="filter-pills">
                    <button class="filter-pill active" onclick="setFilter('all', this)">All</button>
                    <button class="filter-pill" onclick="setFilter('active', this)">Active</button>
                    <button class="filter-pill" onclick="setFilter('suspended', this)">Suspended</button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Username</th>
                        <th>Joined</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Active</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="user-tbody">
                    <?php foreach ($users as $u):
                        $isSuspended = ($u['is_suspended'] === 't' || $u['is_suspended'] === true);
                        $isActive    = !$isSuspended;
                        $name        = $u['username'] ?? $u['email'];
                        $color       = avatarColor($u['email'], $avatarColors);
                        $initials    = initials($name);
                        $role        = $u['role'] ?? 'user';
                        $joined      = isset($u['created_at']) ? date('M d, Y', strtotime($u['created_at'])) : '—';
                    ?>
                    <tr class="user-row <?= $isSuspended ? 'suspended' : '' ?>"
                        data-status="<?= $isSuspended ? 'suspended' : 'active' ?>"
                        data-search="<?= strtolower(htmlspecialchars($u['email'] . ' ' . ($u['username'] ?? ''))) ?>">

                        <td>
                            <div class="user-cell">
                                <div class="user-avatar" style="background:<?= $color ?>;"><?= $initials ?></div>
                                <div>
                                    <div class="user-name"><?= htmlspecialchars($u['username'] ?? 'Unknown') ?></div>
                                    <div class="user-email"><?= htmlspecialchars($u['email']) ?></div>
                                </div>
                            </div>
                        </td>

                        <td class="mono-cell">@<?= htmlspecialchars($u['username'] ?? '—') ?></td>

                        <td class="mono-cell"><?= $joined ?></td>

                        <td>
                            <span class="badge" style="<?= $role === 'admin' ? 'background:var(--amber-s);color:var(--amber);' : 'background:var(--border-s);color:var(--muted);' ?>">
                                <?= ucfirst($role) ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge <?= $isActive ? 'badge-active' : 'badge-suspended' ?>">
                                <span class="badge-dot"></span>
                                <?= $isActive ? 'Active' : 'Suspended' ?>
                            </span>
                        </td>

                        <td>
                            <label class="toggle" title="<?= $isActive ? 'Suspend user' : 'Reactivate user' ?>">
                                <input type="checkbox"
                                       <?= $isActive ? 'checked' : '' ?>
                                       onchange="toggleSuspension(<?= $u['id'] ?>, this)">
                                <div class="toggle-track"></div>
                                <div class="toggle-thumb"></div>
                            </label>
                        </td>

                        <td>
                            <div class="action-cell">
                                <button class="icon-btn" onclick="viewUser(<?= $u['id'] ?>)" title="View details">
                                    <i data-lucide="eye" style="width:13px;"></i>
                                </button>
                                <button class="icon-btn danger" onclick="confirmDelete(<?= $u['id'] ?>, '<?= addslashes($u['username'] ?? $u['email']) ?>')" title="Delete user">
                                    <i data-lucide="trash-2" style="width:13px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (empty($users)): ?>
            <div class="empty-state">
                <i data-lucide="users" style="width:40px;height:40px;color:var(--border);"></i>
                <p>No users found.</p>
            </div>
            <?php endif; ?>

            <div class="pagination">
                <div class="pagination-info" id="pagination-info">
                    Showing <strong><?= $totalUsers ?></strong> of <strong><?= $totalUsers ?></strong> users
                </div>
                <div class="pagination-btns">
                    <button class="page-btn" disabled><i data-lucide="chevron-left" style="width:14px;"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn" disabled><i data-lucide="chevron-right" style="width:14px;"></i></button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
lucide.createIcons();

/* ── TOAST ── */
function showToast(msg, type = 'info') {
    const t = document.getElementById('toast');
    t.innerHTML = msg;
    t.className = 'show ' + type;
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.className = '', 3000);
}

/* ── TOGGLE SUSPENSION ── */
function toggleSuspension(userId, checkbox) {
    const row    = checkbox.closest('tr');
    const toggle = checkbox.closest('.toggle');
    const isNowActive = checkbox.checked; // checked = active (not suspended)

    toggle.classList.add('loading');
    checkbox.disabled = true;

    fetch('index.php?action=toggle-suspension&id=' + userId)
        .then(r => r.json())
        .then(data => {
            toggle.classList.remove('loading');
            checkbox.disabled = false;

            if (data.success) {
                // Update row class
                row.classList.toggle('suspended', !isNowActive);
                row.dataset.status = isNowActive ? 'active' : 'suspended';

                // Update badge
                const badge = row.querySelector('.badge.badge-active, .badge.badge-suspended');
                if (badge) {
                    badge.className = 'badge ' + (isNowActive ? 'badge-active' : 'badge-suspended');
                    badge.innerHTML = `<span class="badge-dot"></span>${isNowActive ? 'Active' : 'Suspended'}`;
                }

                showToast(
                    isNowActive ? '✓ User reactivated' : '⊘ User suspended',
                    isNowActive ? 'success' : 'info'
                );

                updateStats();
            } else {
                // Revert toggle
                checkbox.checked = !isNowActive;
                showToast('Failed: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(() => {
            toggle.classList.remove('loading');
            checkbox.checked = !isNowActive;
            checkbox.disabled = false;
            showToast('Request failed.', 'error');
        });
}

/* ── UPDATE STAT CARDS LIVE ── */
function updateStats() {
    const rows       = document.querySelectorAll('#user-tbody .user-row');
    const total      = rows.length;
    const suspended  = [...rows].filter(r => r.dataset.status === 'suspended').length;
    const active     = total - suspended;

    // Update the stat card values (by order: total, active, suspended, admins)
    const vals = document.querySelectorAll('.stat-value');
    if (vals[0]) vals[0].textContent = total;
    if (vals[1]) vals[1].textContent = active;
    if (vals[2]) vals[2].textContent = suspended;
}

/* ── SEARCH ── */
function filterTable(q) {
    q = q.toLowerCase().trim();
    let visible = 0;
    document.querySelectorAll('#user-tbody .user-row').forEach(row => {
        const match = !q || row.dataset.search.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('record-count').textContent = visible + ' records';
}

/* ── FILTER PILLS ── */
let currentFilter = 'all';
function setFilter(filter, btn) {
    currentFilter = filter;
    document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');

    let visible = 0;
    document.querySelectorAll('#user-tbody .user-row').forEach(row => {
        const show = filter === 'all' || row.dataset.status === filter;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('record-count').textContent = visible + ' records';
}

/* ── VIEW USER ── */
function viewUser(userId) {
    window.location.href = 'index.php?action=view-user&id=' + userId;
}

/* ── DELETE USER ── */
let _deleteUserId = null;
function confirmDelete(userId, name) {
    _deleteUserId = userId;
    document.getElementById('confirm-title').textContent = 'Delete User';
    document.getElementById('confirm-body').textContent  = `Are you sure you want to permanently delete "${name}"? This cannot be undone.`;
    document.getElementById('confirm-modal').classList.add('open');
}
function closeConfirm() {
    document.getElementById('confirm-modal').classList.remove('open');
    _deleteUserId = null;
}
document.getElementById('confirm-ok').onclick = () => {
    if (!_deleteUserId) return;
    fetch('index.php?action=delete-user&id=' + _deleteUserId)
        .then(r => r.json())
        .then(data => {
            closeConfirm();
            if (data.success) {
                const row = document.querySelector(`tr[data-search]`); // fallback
                // Remove row by user id attribute
                document.querySelectorAll('#user-tbody .user-row').forEach(r => {
                    if (r.querySelector(`button[onclick*="${_deleteUserId}"]`)) {
                        r.style.transition = 'opacity 0.3s';
                        r.style.opacity = '0';
                        setTimeout(() => { r.remove(); updateStats(); }, 300);
                    }
                });
                showToast('User deleted.', 'info');
            } else {
                showToast(data.message || 'Delete failed.', 'error');
            }
        });
};

window.addEventListener('keydown', e => { if (e.key === 'Escape') closeConfirm(); });
</script>
</body>
</html>