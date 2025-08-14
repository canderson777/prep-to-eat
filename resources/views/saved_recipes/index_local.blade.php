<!DOCTYPE html>
<html>
<head>
    <title>My Local Recipes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8fafc; }
        .container { max-width: 800px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 0 10px #ddd; padding: 24px;}
        h1 { text-align: center; color: #333; }
        .actions { display:flex; gap:8px; flex-wrap:wrap; margin-bottom: 16px; }
        .btn { background:#38b6ff; color:#fff; border:none; padding:8px 14px; border-radius:4px; cursor:pointer; }
        .btn.secondary { background:#64748b; }
        .btn.danger { background:#ef4444; }
        .card { background:#e3f5ff; border-radius:6px; padding:16px; margin-bottom:12px; }
        .meta { color:#334155; font-size:12px; margin-top:6px; }
        .empty { text-align:center; color:#6b7280; margin-top:20px; }
        pre { white-space:pre-wrap; }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <div style="text-align:right; margin-bottom:12px;">
            <a href="/" style="color:#007bff;">Home</a>
        </div>

        <h1>My Local Recipes</h1>
		@guest
			<div style="text-align:center; margin-bottom: 12px;">
				<a href="/login" id="signinImportBtn" class="btn">Sign in to import all</a>
			</div>
		@endguest
        <div class="actions">
            <button class="btn" id="exportBtn">Export JSON</button>
            <button class="btn secondary" id="importBtn">Import JSON</button>
            <input type="file" id="importFile" accept="application/json" style="display:none;" />
            <button class="btn danger" id="clearAllBtn">Clear All</button>
        </div>

        <div id="list"></div>
        <div id="empty" class="empty" style="display:none;">No local recipes yet. Save some from the homepage.</div>
    </div>

    <script>
        (function() {
            const KEY = 'guestRecipes';
            const listEl = document.getElementById('list');
            const emptyEl = document.getElementById('empty');
            const importFile = document.getElementById('importFile');

            function getAll() {
                try { return JSON.parse(localStorage.getItem(KEY) || '[]'); } catch { return []; }
            }
            function setAll(arr) {
                localStorage.setItem(KEY, JSON.stringify(arr));
            }
            function render() {
                const items = getAll();
                listEl.innerHTML = '';
                emptyEl.style.display = items.length ? 'none' : 'block';
                items.forEach((r, idx) => {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.innerHTML = `
                        <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                            <div>
                                <div style="font-weight:600; font-size:18px;">${escapeHtml(r.title || 'Untitled')}</div>
                                <div class="meta">Category: ${escapeHtml(r.category || '—')} · Saved: ${formatDate(r.savedAt)}</div>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button class="btn secondary" data-view="${r.id}">View</button>
                                <button class="btn danger" data-delete="${r.id}">Delete</button>
                            </div>
                        </div>
                        <div id="detail_${r.id}" style="display:none; margin-top:10px; background:#fff; border-radius:6px; padding:12px;">
                            <strong>Ingredients</strong>
                            <pre>${escapeHtml(r.ingredients || '')}</pre>
                            <strong>Instructions</strong>
                            <pre>${escapeHtml(r.instructions || '')}</pre>
                            ${r.summary ? `<strong>Summary</strong><pre>${escapeHtml(r.summary)}</pre>` : ''}
                        </div>
                    `;
                    listEl.appendChild(card);
                });
            }

            function formatDate(iso) {
                if (!iso) return '—';
                try { return new Date(iso).toLocaleString(); } catch { return iso; }
            }
            function escapeHtml(str) {
                return String(str).replace(/[&<>"]+/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[s]));
            }

            document.addEventListener('click', function(e) {
                const viewId = e.target.getAttribute && e.target.getAttribute('data-view');
                if (viewId) {
                    const panel = document.getElementById('detail_' + viewId);
                    if (panel) panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
                }
                const delId = e.target.getAttribute && e.target.getAttribute('data-delete');
                if (delId) {
                    const items = getAll();
                    const next = items.filter(x => x.id !== delId);
                    setAll(next);
                    render();
                }
            });

            document.getElementById('clearAllBtn').addEventListener('click', function() {
                if (confirm('Clear all local recipes?')) {
                    setAll([]);
                    render();
                }
            });

            document.getElementById('exportBtn').addEventListener('click', function() {
                const data = getAll();
                const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'my-local-recipes.json';
                a.click();
                URL.revokeObjectURL(url);
            });

            document.getElementById('importBtn').addEventListener('click', function() {
                importFile.click();
            });
				const signInBtn = document.getElementById('signinImportBtn');
				if (signInBtn) {
					signInBtn.addEventListener('click', function() {
						try { localStorage.removeItem('import_banner_dismissed'); } catch {}
					});
				}
            importFile.addEventListener('change', function() {
                const file = importFile.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function() {
                    try {
                        const current = getAll();
                        const incoming = JSON.parse(reader.result || '[]');
                        const byId = new Map(current.map(x => [x.id, x]));
                        for (const r of Array.isArray(incoming) ? incoming : []) {
                            if (!r.id) r.id = 'gr_' + Date.now() + '_' + Math.random().toString(36).slice(2);
                            byId.set(r.id, r);
                        }
                        setAll(Array.from(byId.values()));
                        render();
                    } catch (err) {
                        alert('Invalid JSON file.');
                    }
                };
                reader.readAsText(file);
            });

            render();
        })();
    </script>
</body>
</html>


