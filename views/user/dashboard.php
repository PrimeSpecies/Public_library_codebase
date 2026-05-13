<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library | ResearchHub</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
</script>
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
            overflow-x: hidden;
        }

        /* ── NAV ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            height: var(--nav-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 24px;
            gap: 12px;
        }
        .nav-left { display: flex; align-items: center; gap: 12px; }
        .nav-brand { font-family: var(--mono); font-weight: 600; font-size: 1rem; letter-spacing: -0.5px; color: var(--text); white-space: nowrap; }
        .nav-right { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
        .nav-user { font-size: 0.8rem; color: var(--text-muted); }
        .nav-logout { font-size: 0.8rem; font-weight: 600; color: var(--red); text-decoration: none; padding: 6px 14px; border-radius: 6px; background: var(--red-soft); white-space: nowrap; }

        /* Hamburger */
        .hamburger {
            display: none;
            background: none;
            border: 1px solid var(--border);
            border-radius: 7px;
            padding: 6px;
            cursor: pointer;
            color: var(--text);
            align-items: center;
            flex-shrink: 0;
        }

        /* ── SIDEBAR OVERLAY ── */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.5);
            z-index: 49;
            backdrop-filter: blur(2px);
        }
        #sidebar-overlay.open { display: block; }

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
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
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

        .sidebar-search { position: relative; }
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
            margin-top: 6px; width: 100%; padding: 7px;
            border: 1px dashed var(--border); border-radius: 7px;
            background: none; font-size: 0.75rem; color: var(--text-muted);
            cursor: pointer; font-family: var(--sans);
            display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: all 0.2s;
        }
        .adv-search-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }

        /* Folder Tree */
        .folder-tree { list-style: none; margin: 0; padding: 0; }
        .folder-tree li { margin: 0; }
        .folder-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 5px 6px; border-radius: 6px; cursor: pointer;
            transition: background 0.15s;
        }
        .folder-row:hover { background: var(--bg); }
        .folder-row.active { background: var(--accent-soft); }
        .folder-left {
            display: flex; align-items: center; gap: 5px;
            flex: 1; min-width: 0; font-size: 0.82rem;
            color: var(--text); text-decoration: none;
        }
        .folder-row.active .folder-left { color: var(--accent); font-weight: 600; }
        .folder-name { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .chevron-btn {
            background: none; border: none; cursor: pointer; padding: 0;
            color: var(--text-faint); display: flex; align-items: center;
            transition: transform 0.2s; flex-shrink: 0;
        }
        .chevron-btn.open { transform: rotate(90deg); }
        .folder-actions { display: none; align-items: center; gap: 2px; flex-shrink: 0; }
        .folder-row:hover .folder-actions { display: flex; }
        .folder-act-btn {
            background: none; border: none; cursor: pointer; padding: 3px;
            border-radius: 4px; color: var(--text-faint);
            display: flex; align-items: center; transition: all 0.15s;
        }
        .folder-act-btn:hover { background: var(--border); color: var(--text); }
        .folder-act-btn.del:hover { background: var(--red-soft); color: var(--red); }
        .folder-children { padding-left: 18px; }

        .file-list { list-style: none; margin: 4px 0; padding: 0; }
        .file-item {
            display: flex; align-items: center; gap: 6px;
            padding: 4px 6px; border-radius: 5px;
            font-size: 0.78rem; color: var(--text-muted);
            cursor: grab; transition: background 0.15s;
        }
        .file-item:hover { background: var(--bg); color: var(--text); }

        .new-folder-input { display: flex; gap: 6px; margin-top: 8px; }
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
            min-width: 0;
        }

        /* Drop zone */
        #drop-zone {
            border: 2px dashed var(--border); padding: 20px 40px;
            border-radius: 12px; background: var(--surface);
            cursor: pointer; transition: 0.2s; text-align: center;
        }
        #drop-zone:hover, #drop-zone.dragover { background: var(--accent-soft); border-color: var(--accent); }

        /* Action bar */
        #action-bar { max-height: 0; overflow: hidden; opacity: 0; transition: all 0.4s cubic-bezier(0.4,0,0.2,1); }
        #action-bar.visible { max-height: 800px; opacity: 1; margin-bottom: 32px; }

        .input-field {
            width: 100%; padding: 10px 12px; border: 1px solid var(--border);
            border-radius: 7px; font-size: 0.875rem; font-family: var(--sans);
            color: var(--text); background: var(--surface); transition: border 0.2s;
        }
        .input-field:focus { outline: none; border-color: var(--accent); }

        .toggle-container { display: flex; align-items: center; background: var(--bg); padding: 3px; border-radius: 8px; width: fit-content; }
        .toggle-btn { padding: 5px 14px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: 0.2s; border: none; font-family: var(--sans); }
        .toggle-btn.active { background: white; color: var(--accent); box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        .toggle-btn.inactive { color: var(--text-muted); background: transparent; }

        /* Cards / Tables */
        .card { background: var(--surface); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 28px; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--border-soft); display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .card-header h2 { font-size: 0.9rem; font-weight: 700; margin: 0; }

        .table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        .bloomberg-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; min-width: 480px; }
        .bloomberg-table th {
            text-align: left; padding: 10px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text-faint); font-size: 0.7rem;
            text-transform: uppercase; letter-spacing: 0.06em;
            font-weight: 700; background: var(--bg); white-space: nowrap;
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

        .tbl-btn {
            background: none; border: none; cursor: pointer; padding: 5px;
            border-radius: 5px; display: inline-flex; align-items: center; transition: all 0.15s;
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
            pointer-events: none; max-width: calc(100vw - 32px);
        }
        #toast.show { transform: translateY(0); opacity: 1; }
        #toast.success { background: #065f46; }
        #toast.error { background: #991b1b; }

        /* Preview Modal */
        #preview-modal {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.85); z-index: 9999; backdrop-filter: blur(4px);
        }
        .modal-content {
            width: 95%; height: 92%; margin: 2% auto;
            background: white; border-radius: 12px;
            display: flex; flex-direction: column; overflow: hidden;
        }
        .modal-header {
            padding: 13px 20px; background: var(--bg);
            border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center; gap: 10px;
        }

        /* Advanced Search Modal */
        #advsearch-modal {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.7); z-index: 9998;
            backdrop-filter: blur(4px);
            align-items: center; justify-content: center; padding: 16px;
        }
        #advsearch-modal.open { display: flex; }
        .advsearch-box {
            background: white; border-radius: 16px;
            width: 100%; max-width: 560px;
            box-shadow: 0 24px 48px rgba(0,0,0,0.2); overflow: hidden;
        }
        .advsearch-header {
            padding: 18px 24px; border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
        }
        .advsearch-header h3 { margin: 0; font-size: 0.95rem; font-weight: 700; }
        .advsearch-body { padding: 20px; display: flex; flex-direction: column; gap: 14px; }
        .advsearch-results { max-height: 280px; overflow-y: auto; }
        .advsearch-result-item {
            padding: 12px; border-radius: 8px; border: 1px solid var(--border);
            margin-bottom: 8px; cursor: pointer; transition: border 0.2s, background 0.2s;
        }
        .advsearch-result-item:hover { border-color: var(--accent); background: var(--accent-soft); }
        .advsearch-result-item .res-title { font-weight: 600; font-size: 0.85rem; margin-bottom: 4px; }
        .advsearch-result-item .res-snippet { font-size: 0.78rem; color: var(--text-muted); line-height: 1.5; }
        .advsearch-result-item .res-snippet mark { background: #fef08a; border-radius: 2px; padding: 0 2px; }

        .search-highlight td { background: #fefce8 !important; }
        .folder-row.drop-target { background: var(--accent-soft) !important; outline: 2px dashed var(--accent); }

        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .status-msg { padding: 10px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; margin-bottom: 20px; }
        .status-msg.success { background: var(--green-soft); color: #065f46; border: 1px solid #a7f3d0; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            /* Sidebar hidden by default on mobile */
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }

            .hamburger { display: flex; }

            /* Nav adjustments */
            .nav-user { display: none; }
            nav { padding: 0 16px; }

            /* Main takes full width */
            .main-content { margin-left: 0; max-width: 100%; padding: 16px 16px 40px; }

            /* Header stack on mobile */
            .page-header-row { flex-direction: column !important; align-items: flex-start !important; gap: 16px !important; }
            #drop-zone { width: 100%; padding: 16px; }

            /* Action bar grid */
            .action-grid { grid-template-columns: 1fr !important; }

            /* Advanced search grid */
            .adv-grid { grid-template-columns: 1fr !important; }

            .modal-content { width: 100%; height: 95%; margin: 2.5% auto; border-radius: 10px; }
        }

        @media (max-width: 480px) {
            .tag-pill { font-size: 0.65rem; padding: 1px 5px; }
            .card-header h2 { font-size: 0.85rem; }
        }
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

<div id="toast"></div>
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- Preview Modal -->
<!-- Preview Modal -->
<div id="preview-modal">
    <div class="modal-content">
        <div class="modal-header">
    <div style="display:flex;align-items:center;gap:10px;min-width:0;">
        <i data-lucide="file-text" style="color:var(--accent);width:18px;flex-shrink:0;"></i>
        <span id="modal-title" class="mono" style="font-weight:600;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <?= __('preview.title') ?>
        </span>
    </div>
    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
        <!-- <button id="download-btn" onclick="downloadDoc()" style="background:var(--accent-soft);color:var(--accent);border:none;padding:7px 12px;border-radius:6px;cursor:pointer;font-weight:700;font-size:0.8rem;font-family:var(--sans);display:flex;align-items:center;gap:6px;">
            <i data-lucide="download" style="width:14px;"></i> <?= __('preview.download') ?>
        </button> -->
        <button onclick="closePreview()" style="background:var(--red-soft);color:var(--red);border:none;padding:7px 12px;border-radius:6px;cursor:pointer;font-weight:700;font-size:0.8rem;font-family:var(--sans);white-space:nowrap;">
            ✕ <?= __('preview.close') ?>
        </button>
    </div>
</div><!-- PDF.js viewer container -->
        <div id="pdf-container" style="flex-grow:1;overflow-y:auto;background:#525659;padding:16px;display:flex;flex-direction:column;align-items:center;gap:12px;">
            <div id="pdf-loading" style="color:white;font-family:var(--sans);margin-top:40px;display:none;">
                Loading document…
            </div>
            <div id="pdf-error" style="color:#fca5a5;font-family:var(--sans);margin-top:40px;display:none;">
                Failed to load document.
            </div>
        </div>
    </div>
</div>

<!-- Advanced Search Modal -->
<div id="advsearch-modal">
    <div class="advsearch-box">
        <div class="advsearch-header">
            <h3><?= __('search.advanced_title') ?></h3>
            <button onclick="closeAdvSearch()" style="background:none;border:none;cursor:pointer;color:var(--text-muted);">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        <div class="advsearch-body">
            <div>
                <label style="font-size:0.75rem;font-weight:700;color:var(--text-muted);display:block;margin-bottom:6px;">
                    <?= __('search.term') ?>
                </label>
                <input type="text" id="adv-query" placeholder="<?= __('search.term_ph') ?>" class="input-field">
            </div>
            <div class="adv-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label style="font-size:0.75rem;font-weight:700;color:var(--text-muted);display:block;margin-bottom:6px;">
                        <?= __('search.scope') ?>
                    </label>
                    <select id="adv-scope" class="input-field" style="cursor:pointer;">
                        <option value="all"><?= __('search.scope_all') ?></option>
                        <option value="private"><?= __('search.scope_mine') ?></option>
                        <option value="public"><?= __('search.scope_pub') ?></option>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.75rem;font-weight:700;color:var(--text-muted);display:block;margin-bottom:6px;">
                        <?= __('search.tags_filter') ?>
                    </label>
                    <input type="text" id="adv-tags" placeholder="<?= __('search.tags_ph') ?>" class="input-field">
                </div>
            </div>
            <button onclick="runAdvSearch()" style="background:var(--accent);color:white;border:none;padding:11px;border-radius:8px;font-weight:700;cursor:pointer;font-family:var(--sans);font-size:0.875rem;display:flex;align-items:center;justify-content:center;gap:8px;">
                <i data-lucide="search" style="width:16px;"></i> <?= __('search.run') ?>
            </button>
            <div id="advsearch-status" style="font-size:0.8rem;color:var(--text-muted);text-align:center;display:none;">
                <?= __('search.searching') ?>
            </div>
            <div id="advsearch-results" class="advsearch-results" style="display:none;"></div>
        </div>
    </div>
</div>

<!-- NAV -->
<nav>
    <div class="nav-left">
        <button class="hamburger" onclick="openSidebar()">
            <i data-lucide="menu" style="width:18px;"></i>
        </button>
        <div class="nav-brand"><?= __('nav.brand') ?></div>
    </div>
    <div class="nav-right">
        <span class="nav-user">
            <?= __('nav.logged_in_as') ?>: <strong><?= htmlspecialchars($_SESSION['user_email'] ?? 'User') ?></strong>
        </span>
        <div style="display:flex;align-items:center;gap:4px;">
            <a href="index.php?action=set-lang&lang=en"
               style="font-size:0.75rem;font-weight:600;padding:4px 9px;border-radius:5px;text-decoration:none;
                      <?= ($_SESSION['lang'] ?? 'en') === 'en' ? 'background:var(--accent);color:white;' : 'color:var(--text-muted);' ?>">
                EN
            </a>
            <a href="index.php?action=set-lang&lang=fr"
               style="font-size:0.75rem;font-weight:600;padding:4px 9px;border-radius:5px;text-decoration:none;
                      <?= ($_SESSION['lang'] ?? 'en') === 'fr' ? 'background:var(--accent);color:white;' : 'color:var(--text-muted);' ?>">
                FR
            </a>
        </div>
        <a href="index.php?action=logout" class="nav-logout"><?= __('nav.logout') ?></a>
    </div>
</nav>

<div class="app-shell">

    <!-- ── SIDEBAR ── -->
    <aside id="sidebar">
        <div class="sidebar-section">
            <div class="sidebar-label"><?= __('search.label') ?></div>
            <div class="sidebar-search">
                <i data-lucide="search" class="search-icon" style="width:14px;"></i>
                <input type="text" id="simple-search" placeholder="<?= __('search.placeholder') ?>" oninput="runSimpleSearch(this.value)">
            </div>
            <button class="adv-search-btn" onclick="openAdvSearch()">
                <i data-lucide="sliders" style="width:13px;"></i>
                <?= __('search.advanced') ?>
            </button>
        </div>

        <div class="sidebar-section" style="flex:1;">
            <div class="sidebar-label">
                <?= __('folders.label') ?>
                <button onclick="showNewFolderInput(null)" title="<?= __('folders.new_root') ?>">
                    <i data-lucide="folder-plus" style="width:14px;"></i>
                </button>
            </div>

            <div class="folder-row" style="margin-bottom:4px;"
                 ondragover="allowFolderDrop(event,this)"
                 ondragleave="this.classList.remove('drop-target')"
                 ondrop="dropToFolder(event,null)">
                <a href="index.php?action=dashboard" class="folder-left" style="<?= !$currentFolderId ? 'color:var(--accent);font-weight:600;' : '' ?>">
                    <i data-lucide="home" style="width:14px;flex-shrink:0;"></i>
                    <span class="folder-name"><?= __('folders.root') ?></span>
                </a>
            </div>

            <ul class="folder-tree" id="folder-tree-root">
                <?php renderFolderTree($folderTree, $currentFolderId); ?>
            </ul>

            <div id="new-folder-container" style="display:none;margin-top:8px;">
                <div class="new-folder-input">
                    <input type="text" id="new-folder-name" placeholder="<?= __('folders.new') ?>" onkeydown="if(event.key==='Enter')confirmNewFolder()">
                    <button onclick="confirmNewFolder()"><?= __('folders.add') ?></button>
                </div>
            </div>
        </div>
    </aside>

    <!-- ── MAIN ── -->
    <main class="main-content">

        <?php if (isset($_GET['status']) && $_GET['status'] === 'bookmarked'): ?>
            <div class="status-msg success"><?= __('status.bookmarked') ?></div>
        <?php endif; ?>

        <!-- Header + Drop Zone -->
        <div class="page-header-row" style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:28px;gap:16px;">
            <div>
                <h1 style="margin:0;font-size:1.75rem;font-weight:700;letter-spacing:-1px;"><?= __('dashboard.title') ?></h1>
                <p style="color:var(--text-muted);margin:4px 0 0;font-size:0.875rem;"><?= __('dashboard.subtitle') ?></p>
            </div>
            <div id="drop-zone" style="flex-shrink:0;">
                <i data-lucide="upload-cloud" style="color:var(--accent);width:28px;height:28px;margin-bottom:6px;"></i>
                <div id="file-status" style="font-size:0.82rem;font-weight:600;color:var(--text);">
                    <?= __('dashboard.upload_drag') ?>
                </div>
                <input type="file" id="file-input" hidden accept=".pdf">
            </div>
        </div>

        <!-- Upload Action Bar -->
        <div id="action-bar">
            <div style="background:var(--surface);border-radius:12px;padding:24px;box-shadow:0 4px 16px rgba(0,0,0,0.07);border:1px solid var(--border);margin-bottom:28px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border-soft);flex-wrap:wrap;gap:12px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="background:var(--accent-soft);padding:7px;border-radius:7px;">
                            <i data-lucide="user" style="color:var(--accent);width:16px;"></i>
                        </div>
                        <div>
                            <span style="display:block;font-size:0.65rem;font-weight:700;color:var(--text-faint);text-transform:uppercase;letter-spacing:0.08em;">
                                <?= __('dashboard.authoring') ?>
                            </span>
                            <span class="mono" style="font-weight:600;font-size:0.85rem;">@<?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                        </div>
                    </div>
                    <div>
                        <span style="display:block;font-size:0.65rem;font-weight:700;color:var(--text-faint);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:5px;text-align:right;">
                            <?= __('dashboard.privacy') ?>
                        </span>
                        <div class="toggle-container">
                            <button type="button" id="private-btn" class="toggle-btn active" onclick="setPrivacy(false)">
                                <?= __('dashboard.private') ?>
                            </button>
                            <button type="button" id="public-btn" class="toggle-btn inactive" onclick="setPrivacy(true)">
                                <?= __('dashboard.public') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="action-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div style="display:flex;flex-direction:column;gap:16px;">
                        <div>
                            <label style="display:block;font-size:0.72rem;font-weight:700;color:var(--text-muted);margin-bottom:6px;">
                                <?= __('dashboard.title_label') ?>
                            </label>
                            <input type="text" id="doc-title" placeholder="<?= __('dashboard.doc_name_ph') ?>" class="input-field">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.72rem;font-weight:700;color:var(--text-muted);margin-bottom:6px;">
                                <?= __('dashboard.desc_label') ?>
                            </label>
                            <textarea id="doc-desc" placeholder="<?= __('dashboard.desc_ph') ?>" class="input-field" style="height:90px;resize:none;"></textarea>
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;justify-content:space-between;">
                        <div>
                            <label style="display:block;font-size:0.72rem;font-weight:700;color:var(--text-muted);margin-bottom:6px;">
                                <?= __('dashboard.tags_label') ?>
                            </label>
                            <input type="text" id="doc-tags" placeholder="<?= __('dashboard.tags_ph') ?>" class="input-field">
                        </div>
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-top:16px;flex-wrap:wrap;">
                            <button onclick="resetUI()" style="background:none;border:none;color:var(--text-faint);font-weight:600;cursor:pointer;font-size:0.85rem;font-family:var(--sans);">
                                <?= __('dashboard.discard') ?>
                            </button>
                            <div id="upload-progress-wrap" style="display:none;margin-top:12px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
        <span style="font-size:0.75rem;font-weight:600;color:var(--text-muted);" id="upload-progress-label">Uploading…</span>
        <span style="font-size:0.75rem;font-weight:700;color:var(--accent);" id="upload-progress-pct">0%</span>
    </div>
    <div style="background:var(--border);border-radius:99px;height:6px;overflow:hidden;">
        <div id="upload-progress-bar" style="height:100%;width:0%;background:var(--accent);border-radius:99px;transition:width 0.2s;"></div>
    </div>
</div>
                            <button id="upload-btn" style="background:var(--accent);color:white;border:none;padding:10px 24px;border-radius:8px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;font-size:0.875rem;font-family:var(--sans);">
                                <i data-lucide="check" style="width:16px;"></i> <?= __('dashboard.publish') ?>
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
                <h2><?= __('global.title') ?></h2>
            </div>
            <div class="table-wrapper">
                <table class="bloomberg-table">
                    <thead>
                        <tr>
                            <th><?= __('global.col_title') ?></th>
                            <th><?= __('global.col_tags') ?></th>
                            <th><?= __('global.col_date') ?></th>
                            <th style="text-align:right;"><?= __('global.col_actions') ?></th>
                        </tr>
                    </thead>
                    <tbody id="global-tbody">
                        <?php foreach ($publicFiles as $file): ?>
                        <tr data-title="<?= strtolower(htmlspecialchars($file['title'] ?? '')) ?>"
                            data-tags="<?= strtolower(htmlspecialchars($file['tags'] ?? '')) ?>">
                            <td class="mono" style="font-weight:600;"><?= htmlspecialchars($file['title'] ?? 'Untitled') ?></td>
                            <td>
                                <?php foreach (explode(',', $file['tags'] ?? 'General') as $tag): ?>
                                    <span class="tag-pill"><?= htmlspecialchars(trim($tag)) ?></span>
                                <?php endforeach; ?>
                            </td>
                            <td class="mono" style="font-size:0.78rem;white-space:nowrap;"><?= date('M d, Y', strtotime($file['uploaded_at'] ?? 'now')) ?></td>
                            <td style="text-align:right;white-space:nowrap;">
                                <button class="tbl-btn" onclick="openPreview('<?= $file['id'] ?>', '<?= htmlspecialchars($file['title'], ENT_QUOTES) ?>')" title="<?= __('preview.title') ?>">
                                    <i data-lucide="eye" style="width:16px;color:var(--accent);"></i>
                                </button>
                                <a href="index.php?action=save-to-catalog&id=<?= $file['id'] ?>" class="tbl-btn" title="<?= __('catalog.save') ?>">
                                    <i data-lucide="bookmark-plus" style="width:16px;color:var(--green);"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Private Catalog -->
        <div class="card" id="private-table">
            <div class="card-header">
                <i data-lucide="lock" style="width:17px;color:var(--green);"></i>
                <h2><?= __('catalog.title') ?></h2>
                <?php if ($currentFolderId): ?>
                    <span style="margin-left:auto;font-size:0.72rem;background:var(--accent-soft);color:var(--accent);padding:3px 10px;border-radius:20px;font-weight:600;">
                        <?= __('catalog.folder_view') ?>
                    </span>
                <?php endif; ?>
            </div>
            <?php if (empty($userFiles)): ?>
                <p style="text-align:center;color:var(--text-faint);padding:32px;font-size:0.85rem;">
                    <?= __('catalog.empty') ?>
                </p>
            <?php else: ?>
            <div class="table-wrapper">
                <table class="bloomberg-table">
                    <thead>
                        <tr>
                            <th style="width:36px;"></th>
                            <th><?= __('catalog.col_name') ?></th>
                            <th><?= __('catalog.col_location') ?></th>
                            <th style="text-align:right;"><?= __('catalog.col_actions') ?></th>
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
                            <td class="mono" style="font-size:0.72rem;color:var(--text-faint);white-space:nowrap;">
                                <?= $item['folder_id'] ? 'FOLDER: ' . $item['folder_id'] : __('folders.root') ?>
                            </td>
                            <td style="text-align:right;white-space:nowrap;">
    <button class="tbl-btn" onclick="openPreview('<?= $item['document_id'] ?>', '<?= htmlspecialchars($item['custom_display_name'] ?? $item['title'], ENT_QUOTES) ?>')" title="<?= __('preview.title') ?>">
        <i data-lucide="maximize-2" style="width:15px;color:var(--accent);"></i>
    </button>
    <a href="index.php?action=download-doc&id=<?= $item['document_id'] ?>" class="tbl-btn" title="<?= __('preview.download') ?>">
        <i data-lucide="download" style="width:15px;color:var(--green);"></i>
    </a>
    <button class="tbl-btn danger" onclick="removeFromCatalog(<?= $item['document_id'] ?>, this)" title="<?= __('catalog.remove') ?>">
        <i data-lucide="trash-2" style="width:15px;color:var(--red);"></i>
    </button>
</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

    </main>
</div>
<?php
function renderFolderTree(array $tree, $currentFolderId): void {
    foreach ($tree as $folder) {
        $isActive    = ($folder['id'] == $currentFolderId);
        $hasChildren = !empty($folder['children']);
        $activeClass = $isActive ? 'active' : '';
        ?>
        <li>
            <div class="folder-row <?= $activeClass ?>"
                 ondragover="allowFolderDrop(event,this)"
                 ondragleave="this.classList.remove('drop-target')"
                 ondrop="dropToFolder(event,<?= $folder['id'] ?>)">
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

let _currentDocUrl   = null;
let _currentDocTitle = null;

async function openPreview(id, title) {
    document.getElementById('modal-title').innerText = title;
    document.getElementById('preview-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    document.getElementById('preview-frame').src = '';
    _currentDocUrl   = null;
    _currentDocTitle = title;

    try {
        const res  = await fetch('index.php?action=get-doc-url&id=' + id);
        const data = await res.json();

        if (data.success && data.url) {
            _currentDocUrl = data.url;
            document.getElementById('preview-frame').src = data.url;
        } else {
            alert('Could not load document.');
        }
    } catch (err) {
        console.error('Preview error:', err);
        alert('Failed to load document.');
    }
}

async function downloadDoc() {
    if (!_currentDocUrl) return;
    try {
        const res    = await fetch(_currentDocUrl);
        const blob   = await res.blob();
        const blobUrl = URL.createObjectURL(blob);
        const a      = document.createElement('a');
        a.href       = blobUrl;
        a.download   = (_currentDocTitle || 'document') + '.pdf';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(blobUrl);
    } catch (err) {
        // Fallback — open in new tab if blob fetch fails (CORS)
        window.open(_currentDocUrl, '_blank');
    }
}


/* ── SIDEBAR (mobile) ── */
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebar-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('open');
    document.body.style.overflow = '';
}

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
let _pdfDoc = null;

async function openPreview(id, title) {
    document.getElementById('modal-title').innerText = title;
    document.getElementById('preview-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';

    const container = document.getElementById('pdf-container');
    const loading   = document.getElementById('pdf-loading');
    const errorEl   = document.getElementById('pdf-error');

    container.querySelectorAll('canvas').forEach(c => c.remove());
    loading.style.display = 'block';
    errorEl.style.display = 'none';

    try {
        const url = 'index.php?action=view-doc&id=' + id;
        _pdfDoc = await pdfjsLib.getDocument(url).promise;
        loading.style.display = 'none';

        const dpr = window.devicePixelRatio || 1;

        for (let pageNum = 1; pageNum <= _pdfDoc.numPages; pageNum++) {
            const page     = await _pdfDoc.getPage(pageNum);
            const baseScale = Math.min(container.clientWidth / page.getViewport({ scale: 1 }).width, 1.5);
            const scale    = baseScale * dpr; // scale up for device pixel ratio
            const viewport = page.getViewport({ scale });

            const canvas   = document.createElement('canvas');
            canvas.width   = viewport.width;
            canvas.height  = viewport.height;

            // Display size stays the same — only the internal resolution scales up
            canvas.style.width  = (viewport.width / dpr) + 'px';
            canvas.style.height = (viewport.height / dpr) + 'px';
            canvas.style.cssText += ';max-width:100%;border-radius:4px;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:block;';

            await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
            container.appendChild(canvas);
        }
    } catch (err) {
        loading.style.display = 'none';
        errorEl.style.display = 'block';
        console.error('PDF load error:', err);
    }
}

function closePreview() {
    document.getElementById('preview-modal').style.display = 'none';
    document.getElementById('pdf-container').querySelectorAll('canvas').forEach(c => c.remove());
    document.body.style.overflow = 'auto';
    _pdfDoc = null;
}
window.addEventListener('keydown', e => { if (e.key === 'Escape') { closePreview(); closeAdvSearch(); closeSidebar(); } });

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
}uploadBtn.onclick = () => {
    const file = fileInput.files[0];
    if (!file || !titleInput.value) return;

    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i data-lucide="loader" style="width:16px;"></i> Uploading…';
    lucide.createIcons();

    // Show progress bar
    const progressWrap  = document.getElementById('upload-progress-wrap');
    const progressBar   = document.getElementById('upload-progress-bar');
    const progressPct   = document.getElementById('upload-progress-pct');
    const progressLabel = document.getElementById('upload-progress-label');
    progressWrap.style.display = 'block';
    progressBar.style.width    = '0%';
    progressPct.textContent    = '0%';

    const fd = new FormData();
    fd.append('document', file);
    fd.append('title', titleInput.value);
    fd.append('description', document.getElementById('doc-desc').value);
    fd.append('tags', document.getElementById('doc-tags').value);
    fd.append('is_public', isPublic ? '1' : '0');

    const xhr = new XMLHttpRequest();

    // Progress event
    xhr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
            const pct = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = pct + '%';
            progressPct.textContent = pct + '%';

            if (pct === 100) {
                progressLabel.textContent = 'Processing…';
            }
        }
    });

    xhr.addEventListener('load', () => {
        try {
            const data = JSON.parse(xhr.responseText);
            if (data.success) {
                progressBar.style.width    = '100%';
                progressPct.textContent    = '100%';
                progressLabel.textContent  = 'Done!';
                setTimeout(() => location.reload(), 500);
            } else {
                showToast(data.message || 'Upload failed.', 'error');
                resetUploadUI();
            }
        } catch (err) {
            showToast('Upload failed.', 'error');
            resetUploadUI();
        }
    });

    xhr.addEventListener('error', () => {
        showToast('Upload failed.', 'error');
        resetUploadUI();
    });

    xhr.open('POST', 'index.php?action=upload-doc');
    xhr.send(fd);
};

function resetUploadUI() {
    uploadBtn.disabled = false;
    uploadBtn.innerHTML = '<i data-lucide="check" style="width:16px;"></i> Publish to Library';
    lucide.createIcons();
    document.getElementById('upload-progress-wrap').style.display = 'none';
    document.getElementById('upload-progress-bar').style.width = '0%';
    document.getElementById('upload-progress-pct').textContent = '0%';
    document.getElementById('upload-progress-label').textContent = 'Uploading…';
}
/* ── REMOVE FROM CATALOG ── */
function removeFromCatalog(fileId, btn) {
    if (!confirm('Remove this document from your catalog?')) return;
    const row = btn.closest('tr');
    fetch('index.php?action=remove-from-catalog&id=' + fileId)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                row.style.opacity = '0'; row.style.transition = 'opacity 0.3s';
                setTimeout(() => row.remove(), 300);
                showToast('Removed from catalog.');
            } else { showToast(data.message || 'Failed to remove.', 'error'); }
        });
}

/* ── SIMPLE SEARCH ── */
function runSimpleSearch(q) {
    q = q.toLowerCase().trim();
    ['global-tbody', 'private-tbody'].forEach(id => {
        const tbody = document.getElementById(id);
        if (!tbody) return;
        tbody.querySelectorAll('tr').forEach(row => {
            const match = !q || (row.dataset.title || '').includes(q) || (row.dataset.tags || '').includes(q);
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
    status.style.display = 'block'; status.textContent = 'Searching file content…';
    results.style.display = 'none'; results.innerHTML = '';
    try {
        const res  = await fetch('index.php?action=search-content', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query, scope, tags })
        });
        const text = await res.text();
        const data = JSON.parse(text);
        status.style.display = 'none'; results.style.display = 'block';
        if (!data.results || data.results.length === 0) {
            results.innerHTML = '<p style="text-align:center;color:var(--text-faint);font-size:0.82rem;">No results found.</p>';
            return;
        }
        data.results.forEach(item => {
            const div = document.createElement('div');
            div.className = 'advsearch-result-item';
            div.innerHTML = `<div class="res-title">${item.title}</div><div class="res-snippet">${item.snippet}</div>`;
            div.onclick = () => { openPreview(item.id, item.title); closeAdvSearch(); };
            results.appendChild(div);
        });
    } catch (err) {
        status.style.display = 'none'; results.style.display = 'block';
        results.innerHTML = '<p style="text-align:center;color:var(--red);font-size:0.82rem;">Search failed: ' + err.message + '</p>';
    }
}
document.getElementById('adv-query').addEventListener('keydown', e => { if (e.key === 'Enter') runAdvSearch(); });

/* ── FOLDER TREE ── */
function toggleFolder(chevronBtn) {
    chevronBtn.classList.toggle('open');
    const children = chevronBtn.closest('li').querySelector('.folder-children');
    if (children) children.style.display = chevronBtn.classList.contains('open') ? '' : 'none';
}

let _newFolderParent = null;
function showNewFolderInput(parentId, e) {
    if (e) e.stopPropagation();
    _newFolderParent = parentId;
    const c = document.getElementById('new-folder-container');
    c.style.display = 'block';
    document.getElementById('new-folder-name').value = '';
    document.getElementById('new-folder-name').focus();
}
function confirmNewFolder() {
    const name = document.getElementById('new-folder-name').value.trim();
    if (!name) return;
    fetch('index.php?action=create-folder', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ name, parent_id: _newFolderParent }) })
    .then(r => r.json()).then(data => { if (data.success) { showToast('Folder created.'); location.reload(); } else showToast(data.message || 'Failed.', 'error'); });
}
function startRename(folderId, currentName, e) {
    e.stopPropagation();
    const newName = prompt('Rename folder:', currentName);
    if (!newName || newName === currentName) return;
    fetch('index.php?action=rename-folder', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ folder_id: folderId, name: newName }) })
    .then(r => r.json()).then(data => { if (data.success) { showToast('Renamed.'); location.reload(); } else showToast(data.message || 'Failed.', 'error'); });
}
function deleteFolder(folderId, e) {
    e.stopPropagation();
    if (!confirm('Delete this folder? Contents will be moved to Root.')) return;
    fetch('index.php?action=delete-folder', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ folder_id: folderId }) })
    .then(r => r.json()).then(data => { if (data.success) { showToast('Folder deleted.'); location.reload(); } else showToast(data.message || 'Failed.', 'error'); });
}

/* ── DRAG & DROP ── */
let _draggingFileId = null;
function fileDragStart(e, fileId) { _draggingFileId = fileId; e.dataTransfer.effectAllowed = 'move'; }
function allowFolderDrop(e, rowEl) { e.preventDefault(); e.stopPropagation(); if (rowEl) rowEl.classList.add('drop-target'); }
function dropToFolder(e, folderId) {
    e.preventDefault(); e.stopPropagation();
    document.querySelectorAll('.folder-row').forEach(r => r.classList.remove('drop-target'));
    if (_draggingFileId === null) return;
    fetch('index.php?action=move-file', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ file_id: _draggingFileId, folder_id: folderId }) })
    .then(r => r.json()).then(data => { if (data.success) { showToast('File moved.'); location.reload(); } else showToast(data.message || 'Move failed.', 'error'); });
    _draggingFileId = null;
}
</script>
</body>
</html>