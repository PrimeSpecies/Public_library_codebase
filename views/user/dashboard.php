<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library | ResearchHub</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --bg: #f4f5f7;
            --surface: #ffffff;
            --border: #e2e8f0;
            --border-soft: #f1f5f9;
            --text: #0f172a;
            --text-muted: #64748b;
            --text-faint: #94a3b8;
            --accent: #2563eb;
            --accent-soft: #eff6ff;
            --accent-border: #bfdbfe;
            --green: #10b981;
            --green-soft: #ecfdf5;
            --red: #ef4444;
            --red-soft: #fef2f2;
            --sidebar-w: 260px;
            --nav-h: 56px;
            --mono: 'IBM Plex Mono', monospace;
            --sans: 'IBM Plex Sans', sans-serif;
        }

        body {
            background: var(--bg);
            font-family: var(--sans);
            margin: 0;
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── NAV ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            height: var(--nav-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 24px;
        }
        .nav-brand { font-family: var(--mono); font-weight: 600; font-size: 1rem; letter-spacing: -0.5px; color: var(--text); }
        .nav-right { display: flex; align-items: center; gap: 16px; }
        .nav-user { font-size: 0.8rem; color: var(--text-muted); }
        .nav-logout { font-size: 0.8rem; font-weight: 600; color: var(--red); text-decoration: none; padding: 6px 14px; border-radius: 6px; background: var(--red-soft); }

        /* ── LAYOUT ── */
        .app-shell {
            display: flex;
            padding-top: var(--nav-h);
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        #sidebar {
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            position: fixed;
            top: var(--nav-h);
            left: 0;
            bottom: 0;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            z-index: 50;
        }

        .sidebar-section {
            padding: 16px;
            border-bottom: 1px solid var(--border-soft);
        }
        .sidebar-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-faint);
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sidebar-label button {
            background: none; border: none; cursor: pointer; padding: 2px;
            color: var(--text-faint); border-radius: 4px;
            display: flex; align-items: center;
            transition: color 0.15s, background 0.15s;
        }
        .sidebar-label button:hover { color: var(--accent); background: var(--accent-soft); }

        /* Search in sidebar */
        .sidebar-search {
            position: relative;
        }
        .sidebar-search input {
            width: 100%;
            padding: 8px 10px 8px 32px;
            border: 1px solid var(--border);
            border-radius: 7px;
            font-size: 0.8rem;
            font-family: var(--sans);
            background: var(--bg);
            color: var(--text);
            transition: border 0.2s;
        }
        .sidebar-search input:focus { outline: none; border-color: var(--accent); background: white; }
        .sidebar-search .search-icon {
            position: absolute; left: 9px; top: 50%; transform: translateY(-50%);
            color: var(--text-faint); pointer-events: none;
        }
        .adv-search-btn {
            margin-top: 6px;
            width: 100%;
            padding: 7px;
            border: 1px dashed var(--border);
            border-radius: 7px;
            background: none;
            font-size: 0.75rem;
            color: var(--text-muted);
            cursor: pointer;
            font-family: var(--sans);
            display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: all 0.2s;
        }
        .adv-search-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }

        /* Folder Tree */
        .folder-tree { list-style: none; margin: 0; padding: 0; }
        .folder-tree li { margin: 0; }

        .folder-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 6px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.15s;
            group: true;
        }
        .folder-row:hover { background: var(--bg); }
        .folder-row.active { background: var(--accent-soft); }

        .folder-left {
            display: flex; align-items: center; gap: 5px;
            flex: 1; min-width: 0;
            font-size: 0.82rem;
            color: var(--text);
            text-decoration: none;
        }
        .folder-row.active .folder-left { color: var(--accent); font-weight: 600; }

        .folder-name {
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .chevron-btn {
            background: none; border: none; cursor: pointer; padding: 0;
            color: var(--text-faint); display: flex; align-items: center;
            transition: transform 0.2s;
            flex-shrink: 0;
        }
        .chevron-btn.open { transform: rotate(90deg); }

        .folder-actions {
            display: none;
            align-items: center;
            gap: 2px;
            flex-shrink: 0;
        }
        .folder-row:hover .folder-actions { display: flex; }
        .folder-act-btn {
            background: none; border: none; cursor: pointer; padding: 3px;
            border-radius: 4px; color: var(--text-faint);
            display: flex; align-items: center;
            transition: all 0.15s;
        }
        .folder-act-btn:hover { background: var(--border); color: var(--text); }
        .folder-act-btn.del:hover { background: var(--red-soft); color: var(--red); }

        .folder-children { padding-left: 18px; }

        /* File items inside folders */
        .file-list { list-style: none; margin: 4px 0; padding: 0; }
        .file-item {
            display: flex; align-items: center; gap: 6px;
            padding: 4px 6px;
            border-radius: 5px;
            font-size: 0.78rem;
            color: var(--text-muted);
            cursor: grab;
            transition: background 0.15s;
        }
        .file-item:hover { background: var(--bg); color: var(--text); }
        .file-item.drag-over { background: var(--accent-soft); outline: 1px dashed var(--accent); }

        /* New folder input */
        .new-folder-input {
            display: flex; gap: 6px; margin-top: 8px;
        }
        .new-folder-input input {
            flex: 1; padding: 6px 8px; border: 1px solid var(--accent-border);
            border-radius: 6px; font-size: 0.8rem; font-family: var(--sans);
            background: var(--accent-soft); color: var(--text);
        }
        .new-folder-input input:focus { outline: none; border-color: var(--accent); }
        .new-folder-input button {
            background: var(--accent); color: white; border: none;
            border-radius: 6px; padding: 6px 10px; cursor: pointer; font-size: 0.8rem;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 32px 32px 60px;
            max-width: calc(100% - var(--sidebar-w));
        }

        /* Drop zone */
        #drop-zone {
            border: 2px dashed var(--border);
            padding: 20px 40px;
            border-radius: 12px;
            background: var(--surface);
            cursor: pointer;
            transition: 0.2s;
            text-align: center;
        }
        #drop-zone:hover, #drop-zone.dragover {
            background: var(--accent-soft);
            border-color: var(--accent);
        }

        /* Action bar */
        #action-bar { max-height: 0; overflow: hidden; opacity: 0; transition: all 0.4s cubic-bezier(0.4,0,0.2,1); }
        #action-bar.visible { max-height: 800px; opacity: 1; margin-bottom: 32px; }

        .input-field {
            width: 100%; padding: 10px 12px; border: 1px solid var(--border);
            border-radius: 7px; font-size: 0.875rem; font-family: var(--sans);
            color: var(--text); background: var(--surface);
            transition: border 0.2s;
        }
        .input-field:focus { outline: none; border-color: var(--accent); }

        .toggle-container { display: flex; align-items: center; background: var(--bg); padding: 3px; border-radius: 8px; width: fit-content; }
        .toggle-btn { padding: 5px 14px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: 0.2s; border: none; font-family: var(--sans); }
        .toggle-btn.active { background: white; color: var(--accent); box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        .toggle-btn.inactive { color: var(--text-muted); background: transparent; }

        /* Tables */
        .card {
            background: var(--surface);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 28px;
        }
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-soft);
            display: flex; align-items: center; gap: 10px;
        }
        .card-header h2 { font-size: 0.9rem; font-weight: 700; margin: 0; }

        .bloomberg-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        .bloomberg-table th {
            text-align: left; padding: 10px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text-faint);
            font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.06em;
            font-weight: 700; background: var(--bg);
        }
        .bloomberg-table td { padding: 11px 16px; border-bottom: 1px solid var(--border-soft); vertical-align: middle; }
        .bloomberg-table tr:last-child td { border-bottom: none; }
        .bloomberg-table tr:hover td { background: var(--bg); }

        .mono { font-family: var(--mono); color: var(--text); }
        .tag-pill {
            background: var(--border-soft); padding: 2px 7px;
            border-radius: 4px; font-size: 0.7rem; font-weight: 600;
            margin-right: 3px; display: inline-block; color: var(--text-muted);
        }

        /* Action buttons in table */
        .tbl-btn {
            background: none; border: none; cursor: pointer; padding: 5px;
            border-radius: 5px; display: inline-flex; align-items: center;
            transition: all 0.15s;
        }
        .tbl-btn:hover { background: var(--bg); }
        .tbl-btn.danger:hover { background: var(--red-soft); }

        /* Toast */
        #toast {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            background: var(--text); color: white;
            padding: 12px 20px; border-radius: 10px;
            font-size: 0.85rem; font-family: var(--sans);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            transform: translateY(80px); opacity: 0;
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            pointer-events: none;
        }
        #toast.show { transform: translateY(0); opacity: 1; }
        #toast.success { background: #065f46; }
        #toast.error { background: #991b1b; }

        /* Preview Modal */
        #preview-modal {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.85);
            z-index: 9999; backdrop-filter: blur(4px);
        }
        .modal-content {
            width: 90%; height: 92%; margin: 2% auto;
            background: white; border-radius: 12px;
            display: flex; flex-direction: column; overflow: hidden;
        }
        .modal-header {
            padding: 13px 20px; background: var(--bg);
            border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
        }

        /* Advanced Search Modal */
        #advsearch-modal {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.7);
            z-index: 9998; backdrop-filter: blur(4px);
            align-items: center; justify-content: center;
        }
        #advsearch-modal.open { display: flex; }
        .advsearch-box {
            background: white; border-radius: 16px;
            width: 560px; max-width: 95%;
            box-shadow: 0 24px 48px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .advsearch-header {
            padding: 18px 24px; border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
        }
        .advsearch-header h3 { margin: 0; font-size: 0.95rem; font-weight: 700; }
        .advsearch-body { padding: 24px; display: flex; flex-direction: column; gap: 16px; }
        .advsearch-results { max-height: 300px; overflow-y: auto; }
        .advsearch-result-item {
            padding: 12px; border-radius: 8px; border: 1px solid var(--border);
            margin-bottom: 8px; cursor: pointer; transition: border 0.2s, background 0.2s;
        }
        .advsearch-result-item:hover { border-color: var(--accent); background: var(--accent-soft); }
        .advsearch-result-item .res-title { font-weight: 600; font-size: 0.85rem; margin-bottom: 4px; }
        .advsearch-result-item .res-snippet { font-size: 0.78rem; color: var(--text-muted); line-height: 1.5; }
        .advsearch-result-item .res-snippet mark { background: #fef08a; border-radius: 2px; padding: 0 2px; }

        /* Search highlight row */
        .search-highlight td { background: #fefce8 !important; }

        /* Drag placeholder */
        .drag-placeholder {
            height: 32px;
            background: var(--accent-soft);
            border: 1px dashed var(--accent);
            border-radius: 5px;
            margin: 4px 0;
        }

        /* Folder drop target */
        .folder-row.drop-target { background: var(--accent-soft) !important; outline: 2px dashed var(--accent); }

        /* Scrollbar */
        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        /* Status badge */
        .status-msg {
            padding: 10px 16px; border-radius: 8px;
            font-size: 0.85rem; font-weight: 500; margin-bottom: 20px;
        }
        .status-msg.success { background: var(--green-soft); color: #065f46; border: 1px solid #a7f3d0; }
    </style>
</head>
<body>

<?php
$folderModel = new \Folder();
$allFolders = $folderModel->getAllFoldersByUserId($_SESSION['user_id']);
$folderController = new \App\Controllers\FolderController();
$folderTree = $folderController->buildTree($allFolders, null);
$currentFolderId = $_GET['folder_id'] ?? null;
?>

<!-- Toast -->
<div id="toast"></div>

<!-- Preview Modal -->
<div id="preview-modal">
    <div class="modal-content">
        <div class="modal-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <i data-lucide="file-text" style="color:var(--accent);width:18px;"></i>
                <span id="modal-title" class="mono" style="font-weight:600;font-size:0.9rem;">Document Preview</span>
            </div>
            <button onclick="closePreview()" style="background:var(--red-soft);color:var(--red);border:none;padding:7px 12px;border-radius:6px;cursor:pointer;font-weight:700;font-size:0.8rem;font-family:var(--sans);">
                ✕ Close
            </button>
        </div>
        <iframe id="preview-frame" style="flex-grow:1;border:none;" src=""></iframe>
    </div>
</div>

<!-- Advanced Search Modal -->
<div id="advsearch-modal">
    <div class="advsearch-box">
        <div class="advsearch-header">
            <h3>Advanced Search</h3>
            <button onclick="closeAdvSearch()" style="background:none;border:none;cursor:pointer;color:var(--text-muted);">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        <div class="advsearch-body">
            <div>
                <label style="font-size:0.75rem;font-weight:700;color:var(--text-muted);display:block;margin-bottom:6px;">SEARCH TERM</label>
                <input type="text" id="adv-query" placeholder="Keywords to find in file content…" class="input-field">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label style="font-size:0.75rem;font-weight:700;color:var(--text-muted);display:block;margin-bottom:6px;">SCOPE</label>
                    <select id="adv-scope" class="input-field" style="cursor:pointer;">
                        <option value="all">All Documents</option>
                        <option value="private">My Catalog Only</option>
                        <option value="public">Global Only</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.75rem;font-weight:700;color:var(--text-muted);display:block;margin-bottom:6px;">TAGS FILTER</label>
                    <input type="text" id="adv-tags" placeholder="e.g. ML, Finance" class="input-field">
                </div>
            </div>
            <button onclick="runAdvSearch()" style="background:var(--accent);color:white;border:none;padding:11px;border-radius:8px;font-weight:700;cursor:pointer;font-family:var(--sans);font-size:0.875rem;display:flex;align-items:center;justify-content:center;gap:8px;">
                <i data-lucide="search" style="width:16px;"></i>
                Search File Content
            </button>
            <div id="advsearch-status" style="font-size:0.8rem;color:var(--text-muted);text-align:center;display:none;">Searching…</div>
            <div id="advsearch-results" class="advsearch-results" style="display:none;"></div>
        </div>
    </div>
</div>

<!-- NAV -->
<nav>
    <div class="nav-brand">ResearchHub</div>
    <div class="nav-right">
        <span class="nav-user">Logged in as: <strong><?= htmlspecialchars($_SESSION['user_email'] ?? 'User') ?></strong></span>
        <a href="index.php?action=logout" class="nav-logout">Logout</a>
    </div>
</nav>

<div class="app-shell">

    <!-- ── SIDEBAR ── -->
    <aside id="sidebar">

        <!-- Simple Search -->
        <div class="sidebar-section">
            <div class="sidebar-label">Search</div>
            <div class="sidebar-search">
                <i data-lucide="search" class="search-icon" style="width:14px;"></i>
                <input type="text" id="simple-search" placeholder="Search titles, tags…" oninput="runSimpleSearch(this.value)">
            </div>
            <button class="adv-search-btn" onclick="openAdvSearch()">
                <i data-lucide="sliders" style="width:13px;"></i>
                Advanced Search (content)
            </button>
        </div>

        <!-- Folder Tree -->
        <div class="sidebar-section" style="flex:1;">
            <div class="sidebar-label">
                Folders
                <button onclick="showNewFolderInput(null)" title="New root folder">
                    <i data-lucide="folder-plus" style="width:14px;"></i>
                </button>
            </div>

            <!-- Root drop target -->
            <div class="folder-row"
                 style="margin-bottom:4px;"
                 ondragover="allowFolderDrop(event,this)"
                 ondragleave="this.classList.remove('drop-target')"
                 ondrop="dropToFolder(event,null)">
                <a href="index.php?action=dashboard" class="folder-left" style="<?= !$currentFolderId ? 'color:var(--accent);font-weight:600;' : '' ?>">
                    <i data-lucide="home" style="width:14px;flex-shrink:0;"></i>
                    <span class="folder-name">Root</span>
                </a>
            </div>

            <ul class="folder-tree" id="folder-tree-root">
                <?php renderFolderTree($folderTree, $currentFolderId); ?>
            </ul>

            <div id="new-folder-container" style="display:none;margin-top:8px;">
                <div class="new-folder-input">
                    <input type="text" id="new-folder-name" placeholder="Folder name…" onkeydown="if(event.key==='Enter')confirmNewFolder()">
                    <button onclick="confirmNewFolder()">Add</button>
                </div>
                <input type="hidden" id="new-folder-parent" value="">
            </div>
        </div>

    </aside>

    <!-- ── MAIN ── -->
    <main class="main-content">

        <?php if (isset($_GET['status']) && $_GET['status'] === 'bookmarked'): ?>
            <div class="status-msg success">✅ Research added to your private catalog!</div>
        <?php endif; ?>

        <!-- Header + Drop Zone -->
        <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:28px;">
            <div>
                <h1 style="margin:0;font-size:1.75rem;font-weight:700;letter-spacing:-1px;">Master Hub</h1>
                <p style="color:var(--text-muted);margin:4px 0 0;font-size:0.875rem;">Manage your private library and community research.</p>
            </div>
            <div id="drop-zone">
                <i data-lucide="upload-cloud" style="color:var(--accent);width:28px;height:28px;margin-bottom:6px;"></i>
                <div id="file-status" style="font-size:0.82rem;font-weight:600;color:var(--text);">Drag PDF here or click to browse</div>
                <input type="file" id="file-input" hidden accept=".pdf">
            </div>
        </div>

        <!-- Upload Action Bar -->
        <div id="action-bar">
            <div style="background:var(--surface);border-radius:12px;padding:24px;box-shadow:0 4px 16px rgba(0,0,0,0.07);border:1px solid var(--border);margin-bottom:28px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border-soft);">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="background:var(--accent-soft);padding:7px;border-radius:7px;"><i data-lucide="user" style="color:var(--accent);width:16px;"></i></div>
                        <div>
                            <span style="display:block;font-size:0.65rem;font-weight:700;color:var(--text-faint);text-transform:uppercase;letter-spacing:0.08em;">Authoring as</span>
                            <span class="mono" style="font-weight:600;font-size:0.85rem;">@<?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                        </div>
                    </div>
                    <div>
                        <span style="display:block;font-size:0.65rem;font-weight:700;color:var(--text-faint);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:5px;text-align:right;">Privacy</span>
                        <div class="toggle-container">
                            <button type="button" id="private-btn" class="toggle-btn active" onclick="setPrivacy(false)">Private</button>
                            <button type="button" id="public-btn" class="toggle-btn inactive" onclick="setPrivacy(true)">Public</button>
                        </div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div style="display:flex;flex-direction:column;gap:16px;">
                        <div>
                            <label style="display:block;font-size:0.72rem;font-weight:700;color:var(--text-muted);margin-bottom:6px;">Paper Title</label>
                            <input type="text" id="doc-title" placeholder="Document name" class="input-field">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.72rem;font-weight:700;color:var(--text-muted);margin-bottom:6px;">Short Description</label>
                            <textarea id="doc-desc" placeholder="What is this research about?" class="input-field" style="height:90px;resize:none;"></textarea>
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;justify-content:space-between;">
                        <div>
                            <label style="display:block;font-size:0.72rem;font-weight:700;color:var(--text-muted);margin-bottom:6px;">Categories / Tags</label>
                            <input type="text" id="doc-tags" placeholder="e.g. Machine Learning, Finance" class="input-field">
                        </div>
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-top:16px;">
                            <button onclick="resetUI()" style="background:none;border:none;color:var(--text-faint);font-weight:600;cursor:pointer;font-size:0.85rem;font-family:var(--sans);">Discard</button>
                            <button id="upload-btn" style="background:var(--accent);color:white;border:none;padding:10px 24px;border-radius:8px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;font-size:0.875rem;font-family:var(--sans);">
                                <i data-lucide="check" style="width:16px;"></i> Publish to Library
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Global Discovery -->
        <div class="card" id="global-table">
            <div class="card-header">
                <i data-lucide="globe" style="width:17px;color:var(--accent);"></i>
                <h2>Global Discovery</h2>
            </div>
            <table class="bloomberg-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <!-- <th>Uploader</th> -->
                        <th>Tags</th>
                        <th>Uploaded</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="global-tbody">
                    <?php foreach ($publicFiles as $file): ?>
                    <tr data-title="<?= strtolower(htmlspecialchars($file['title'] ?? '')) ?>"
                        data-tags="<?= strtolower(htmlspecialchars($file['tags'] ?? '')) ?>">
                        <td class="mono" style="font-weight:600;"><?= htmlspecialchars($file['title'] ?? 'Untitled') ?></td>
                        <!-- <td style="color:var(--text-muted);">@<?= htmlspecialchars($file['username'] ?? 'Unknown') ?></td> -->
                        <td>
                            <?php foreach (explode(',', $file['tags'] ?? 'General') as $tag): ?>
                                <span class="tag-pill"><?= htmlspecialchars(trim($tag)) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td class="mono" style="font-size:0.78rem;"><?= date('M d, Y', strtotime($file['uploaded_at'] ?? 'now')) ?></td>
                        <td style="text-align:right;">
                            <button class="tbl-btn" onclick="openPreview('<?= $file['id'] ?>', '<?= htmlspecialchars($file['title'], ENT_QUOTES) ?>')" title="Preview">
                                <i data-lucide="eye" style="width:16px;color:var(--accent);"></i>
                            </button>
                            <a href="index.php?action=save-to-catalog&id=<?= $file['id'] ?>" class="tbl-btn" title="Save to Catalog">
                                <i data-lucide="bookmark-plus" style="width:16px;color:var(--green);"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Private Catalog -->
        <div class="card" id="private-table">
            <div class="card-header">
                <i data-lucide="lock" style="width:17px;color:var(--green);"></i>
                <h2>Private Catalog</h2>
                <?php if ($currentFolderId): ?>
                    <span style="margin-left:auto;font-size:0.72rem;background:var(--accent-soft);color:var(--accent);padding:3px 10px;border-radius:20px;font-weight:600;">
                        Folder view
                    </span>
                <?php endif; ?>
            </div>
            <?php if (empty($userFiles)): ?>
                <p style="text-align:center;color:var(--text-faint);padding:32px;font-size:0.85rem;">No documents in this view.</p>
            <?php else: ?>
            <table class="bloomberg-table">
                <thead>
                    <tr>
                        <th style="width:36px;"></th>
                        <th>Document Name</th>
                        <th>Location</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="private-tbody">
                    <?php foreach ($userFiles as $item): ?>
                    <tr data-file-id="<?= $item['document_id'] ?>"
                        data-title="<?= strtolower(htmlspecialchars($item['custom_display_name'] ?? $item['title'] ?? '')) ?>"
                        draggable="true"
                        ondragstart="fileDragStart(event, <?= $item['document_id'] ?>)">
                        <td><i data-lucide="file-text" style="width:16px;color:var(--text-faint);"></i></td>
                        <td style="font-weight:600;"><?= htmlspecialchars($item['custom_display_name'] ?? $item['title']) ?></td>
                        <td class="mono" style="font-size:0.72rem;color:var(--text-faint);">
                            <?= $item['folder_id'] ? 'FOLDER: ' . $item['folder_id'] : 'ROOT' ?>
                        </td>
                        <td style="text-align:right;white-space:nowrap;">
                            <button class="tbl-btn" onclick="openPreview('<?= $item['document_id'] ?>', '<?= htmlspecialchars($item['custom_display_name'] ?? $item['title'], ENT_QUOTES) ?>')" title="Preview">
                                <i data-lucide="maximize-2" style="width:15px;color:var(--accent);"></i>
                            </button>
                            <button class="tbl-btn danger" onclick="removeFromCatalog(<?= $item['document_id'] ?>, this)" title="Remove from Catalog">
                                <i data-lucide="trash-2" style="width:15px;color:var(--red);"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

    </main>
</div>

<?php
/**
 * Renders the folder tree recursively as <li> elements
 */
function renderFolderTree(array $tree, $currentFolderId): void {
    foreach ($tree as $folder) {
        $isActive  = ($folder['id'] == $currentFolderId);
        $hasChildren = !empty($folder['children']);
        $activeClass = $isActive ? 'active' : '';
        ?>
        <li>
            <div class="folder-row <?= $activeClass ?>"
                 ondragover="allowFolderDrop(event,this)"
                 ondragleave="this.classList.remove('drop-target')"
                 ondrop="dropToFolder(event,<?= $folder['id'] ?>)">

                <!-- Chevron -->
                <?php if ($hasChildren): ?>
                <button class="chevron-btn open" onclick="toggleFolder(this)" style="margin-right:2px;">
                    <i data-lucide="chevron-right" style="width:13px;"></i>
                </button>
                <?php else: ?>
                <span style="width:17px;flex-shrink:0;display:inline-block;"></span>
                <?php endif; ?>

                <a href="index.php?action=dashboard&folder_id=<?= $folder['id'] ?>" class="folder-left">
                    <i data-lucide="folder" style="width:14px;flex-shrink:0;"></i>
                    <span class="folder-name"><?= htmlspecialchars($folder['name']) ?></span>
                </a>

                <!-- Action buttons -->
                <div class="folder-actions">
                    <button class="folder-act-btn" onclick="startRename(<?= $folder['id'] ?>, '<?= addslashes($folder['name']) ?>', event)" title="Rename">
                        <i data-lucide="edit-2" style="width:12px;"></i>
                    </button>
                    <button class="folder-act-btn" onclick="showNewFolderInput(<?= $folder['id'] ?>, event)" title="New subfolder">
                        <i data-lucide="plus" style="width:12px;"></i>
                    </button>
                    <button class="folder-act-btn del" onclick="deleteFolder(<?= $folder['id'] ?>, event)" title="Delete">
                        <i data-lucide="trash-2" style="width:12px;"></i>
                    </button>
                </div>
            </div>

            <?php if ($hasChildren): ?>
            <div class="folder-children">
                <ul class="folder-tree">
                    <?php renderFolderTree($folder['children'], $currentFolderId); ?>
                </ul>
            </div>
            <?php endif; ?>
        </li>
        <?php
    }
}
?>

<script>
lucide.createIcons();

/* ── TOAST ── */
function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'show ' + type;
    setTimeout(() => { t.className = ''; }, 3000);
}

/* ── URL MSG CLEANUP ── */
(function() {
    const p = new URLSearchParams(window.location.search);
    if (p.get('msg') === 'success') showToast('✅ Added to your catalog!');
    else if (p.get('msg') === 'error') showToast('❌ Failed to add to catalog.', 'error');
    if (p.has('msg')) {
        p.delete('msg');
        history.replaceState({}, '', window.location.pathname + (p.toString() ? '?' + p : ''));
    }
})();

/* ── PREVIEW ── */
function openPreview(id, title) {
    document.getElementById('modal-title').innerText = title;
    document.getElementById('preview-frame').src = 'index.php?action=view-doc&id=' + id;
    document.getElementById('preview-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closePreview() {
    document.getElementById('preview-modal').style.display = 'none';
    document.getElementById('preview-frame').src = '';
    document.body.style.overflow = 'auto';
}
window.addEventListener('keydown', e => { if (e.key === 'Escape') { closePreview(); closeAdvSearch(); } });

/* ── UPLOAD ── */
const dropZone   = document.getElementById('drop-zone');
const fileInput  = document.getElementById('file-input');
const fileStatus = document.getElementById('file-status');
const actionBar  = document.getElementById('action-bar');
const uploadBtn  = document.getElementById('upload-btn');
const titleInput = document.getElementById('doc-title');
let isPublic = false;

function setPrivacy(val) {
    isPublic = val;
    document.getElementById('public-btn').className  = val ? 'toggle-btn active' : 'toggle-btn inactive';
    document.getElementById('private-btn').className = val ? 'toggle-btn inactive' : 'toggle-btn active';
}

dropZone.onclick = () => fileInput.click();
dropZone.ondragover = e => { e.preventDefault(); dropZone.classList.add('dragover'); };
dropZone.ondragleave = () => dropZone.classList.remove('dragover');
dropZone.ondrop = e => {
    e.preventDefault(); dropZone.classList.remove('dragover');
    if (e.dataTransfer.files.length > 0) { fileInput.files = e.dataTransfer.files; handleSelection(e.dataTransfer.files[0]); }
};
fileInput.onchange = e => { if (e.target.files.length > 0) handleSelection(e.target.files[0]); };

function handleSelection(file) {
    fileStatus.innerText = 'Selected: ' + file.name;
    titleInput.value = file.name.replace('.pdf', '');
    actionBar.classList.add('visible');
}
function resetUI() {
    fileInput.value = '';
    actionBar.classList.remove('visible');
    fileStatus.innerText = 'Drag PDF here or click to browse';
}

uploadBtn.onclick = () => {
    const file = fileInput.files[0];
    if (!file || !titleInput.value) return;
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i data-lucide="loader" style="width:16px;"></i> Uploading…';
    lucide.createIcons();

    const fd = new FormData();
    fd.append('document', file);
    fd.append('title', titleInput.value);
    fd.append('description', document.getElementById('doc-desc').value);
    fd.append('tags', document.getElementById('doc-tags').value);
    fd.append('is_public', isPublic ? '1' : '0');

    fetch('index.php?action=upload-doc', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
            else { showToast(data.message || 'Upload failed.', 'error'); uploadBtn.disabled = false; uploadBtn.innerHTML = '<i data-lucide="check" style="width:16px;"></i> Publish to Library'; lucide.createIcons(); }
        });
};

/* ── REMOVE FROM CATALOG ── */
function removeFromCatalog(fileId, btn) {
    if (!confirm('Remove this document from your catalog?')) return;
    const row = btn.closest('tr');
    fetch('index.php?action=remove-from-catalog&id=' + fileId)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                row.style.opacity = '0';
                row.style.transition = 'opacity 0.3s';
                setTimeout(() => row.remove(), 300);
                showToast('Removed from catalog.');
            } else {
                showToast(data.message || 'Failed to remove.', 'error');
            }
        });
}

/* ── SIMPLE SEARCH ── */
function runSimpleSearch(q) {
    q = q.toLowerCase().trim();
    // Search both tables
    ['global-tbody', 'private-tbody'].forEach(id => {
        const tbody = document.getElementById(id);
        if (!tbody) return;
        tbody.querySelectorAll('tr').forEach(row => {
            const title = row.dataset.title || '';
            const tags  = row.dataset.tags  || '';
            const match = !q || title.includes(q) || tags.includes(q);
            row.style.display = match ? '' : 'none';
            row.classList.toggle('search-highlight', !!q && match);
        });
    });
}

/* ── ADVANCED SEARCH ── */
function openAdvSearch()  { document.getElementById('advsearch-modal').classList.add('open'); document.getElementById('adv-query').focus(); }
function closeAdvSearch() { document.getElementById('advsearch-modal').classList.remove('open'); }

async function runAdvSearch() {
    const query = document.getElementById('adv-query').value.trim();
    const scope = document.getElementById('adv-scope').value;
    const tags  = document.getElementById('adv-tags').value.trim();

    if (!query) return;

    const status  = document.getElementById('advsearch-status');
    const results = document.getElementById('advsearch-results');

    status.style.display = 'block';
    status.textContent = 'Searching file content…';
    results.style.display = 'none';
    results.innerHTML = '';

    try {
        const res  = await fetch('index.php?action=search-content', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query, scope, tags })
        });

        const text = await res.text(); // Read as text first
        console.log('Raw response:', text); // Check for junk before JSON

        const data = JSON.parse(text); // Then parse manually

        status.style.display = 'none';
        results.style.display = 'block';

        if (!data.results || data.results.length === 0) {
            results.innerHTML = '<p style="text-align:center;color:var(--text-faint);font-size:0.82rem;">No results found.</p>';
            return;
        }

        data.results.forEach(item => {
            const div = document.createElement('div');
            div.className = 'advsearch-result-item';
            div.innerHTML = `
                <div class="res-title">${item.title}</div>
                <div class="res-snippet">${item.snippet}</div>
            `;
            div.onclick = () => { openPreview(item.id, item.title); closeAdvSearch(); };
            results.appendChild(div);
        });

    } catch (err) {
        console.error('Search error:', err);
        status.style.display = 'none';
        results.style.display = 'block';
        results.innerHTML = '<p style="text-align:center;color:var(--red);font-size:0.82rem;">Search failed: ' + err.message + '</p>';
    }
}
document.getElementById('adv-query').addEventListener('keydown', e => {
    if (e.key === 'Enter') runAdvSearch();
});

/* ── FOLDER TREE: TOGGLE ── */
function toggleFolder(chevronBtn) {
    chevronBtn.classList.toggle('open');
    const li = chevronBtn.closest('li');
    const children = li.querySelector('.folder-children');
    if (children) children.style.display = chevronBtn.classList.contains('open') ? '' : 'none';
}

/* ── FOLDER TREE: CREATE ── */
let _newFolderParent = null;

function showNewFolderInput(parentId, e) {
    if (e) e.stopPropagation();
    _newFolderParent = parentId;
    const container = document.getElementById('new-folder-container');
    container.style.display = 'block';
    document.getElementById('new-folder-name').value = '';
    document.getElementById('new-folder-name').focus();
}

function confirmNewFolder() {
    const name = document.getElementById('new-folder-name').value.trim();
    if (!name) return;

    fetch('index.php?action=create-folder', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, parent_id: _newFolderParent })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { showToast('Folder created.'); location.reload(); }
        else showToast(data.message || 'Failed.', 'error');
    });
}

/* ── FOLDER TREE: RENAME ── */
function startRename(folderId, currentName, e) {
    e.stopPropagation();
    const newName = prompt('Rename folder:', currentName);
    if (!newName || newName === currentName) return;

    fetch('index.php?action=rename-folder', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ folder_id: folderId, name: newName })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { showToast('Renamed.'); location.reload(); }
        else showToast(data.message || 'Failed.', 'error');
    });
}

/* ── FOLDER TREE: DELETE ── */
function deleteFolder(folderId, e) {
    e.stopPropagation();
    if (!confirm('Delete this folder? Contents will be moved to Root.')) return;

    fetch('index.php?action=delete-folder', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ folder_id: folderId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { showToast('Folder deleted.'); location.reload(); }
        else showToast(data.message || 'Failed.', 'error');
    });
}

/* ── DRAG & DROP FILES INTO FOLDERS ── */
let _draggingFileId = null;

function fileDragStart(e, fileId) {
    _draggingFileId = fileId;
    e.dataTransfer.effectAllowed = 'move';
}

function allowFolderDrop(e, rowEl) {
    e.preventDefault();
    e.stopPropagation();
    if (rowEl) rowEl.classList.add('drop-target');
}

function dropToFolder(e, folderId) {
    e.preventDefault(); e.stopPropagation();
    document.querySelectorAll('.folder-row').forEach(r => r.classList.remove('drop-target'));
    if (_draggingFileId === null) return;

    fetch('index.php?action=move-file', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ file_id: _draggingFileId, folder_id: folderId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { showToast('File moved.'); location.reload(); }
        else showToast(data.message || 'Move failed.', 'error');
    });

    _draggingFileId = null;
}
</script>
</body>
</html>