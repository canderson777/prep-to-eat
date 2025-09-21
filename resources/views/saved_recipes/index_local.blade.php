@extends('layouts.site')

@section('title', 'My Local Recipes | PrepToEat')

@section('content')
<div class="content-shell">
    <div class="page-header">
        <h1 class="page-title">My Local Recipes</h1>
        <p class="page-subtitle">These recipes live in your browser. Export, import, or sync them to an account before clearing your cache.</p>
    </div>

    <div class="card" style="margin-bottom:2rem;">
        <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; justify-content:space-between;">
            <a class="muted-link" href="{{ url('/') }}">&larr; Back to recipe creator</a>
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                <button class="btn btn-primary btn-small" id="exportBtn">Export JSON</button>
                <button class="btn btn-secondary btn-small" id="importBtn">Import JSON</button>
                <input type="file" id="importFile" accept="application/json" style="display:none;" />
                <button class="btn btn-danger btn-small" id="clearAllBtn">Clear All</button>
            </div>
        </div>
    </div>

    @guest
        <div class="card" style="margin-bottom:2rem; text-align:center;">
            <p style="margin:0 0 1rem;">Create an account to back up your local recipes in the cloud.</p>
            <a href="{{ route('login') }}" id="signinImportBtn" class="btn btn-primary btn-small" style="display:inline-flex;">Sign in to import all</a>
        </div>
    @endguest

    <div id="list" class="summary-grid"></div>
    <div id="empty" class="card" style="display:none;">
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75l7.5-3 7.5 3m-15 0l7.5 3 7.5-3m-15 0V17.25a2.25 2.25 0 001.344 2.07l6.375 2.85c.52.232 1.098.232 1.618 0l6.375-2.85A2.25 2.25 0 0019.5 17.25V6.75m-15 0L12 9.75" />
            </svg>
            <div>No local recipes yet. Save one from the homepage to get started.</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const KEY = 'guestRecipes';
        const MAX_ITEMS = 4;
        const listEl = document.getElementById('list');
        const emptyEl = document.getElementById('empty');
        const importFile = document.getElementById('importFile');

        function sanitizeRecipes(list) {
            if (!Array.isArray(list)) return [];

            const nowIso = new Date().toISOString();
            const deduped = [];
            const seen = new Set();

            for (const item of list) {
                if (!item || typeof item !== 'object') continue;
                const recipe = Object.assign({}, item);
                if (!recipe.id) {
                    const fallbackId = 'gr_' + Date.now() + '_' + Math.random().toString(36).slice(2);
                    recipe.id = (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : fallbackId;
                }
                if (!recipe.savedAt) {
                    recipe.savedAt = nowIso;
                }
                if (seen.has(recipe.id)) continue;
                seen.add(recipe.id);
                deduped.push(recipe);
            }

            deduped.sort(function(a, b) {
                return new Date(a.savedAt).getTime() - new Date(b.savedAt).getTime();
            });

            if (deduped.length > MAX_ITEMS) {
                deduped.splice(0, deduped.length - MAX_ITEMS);
            }

            return deduped;
        }

        function getAll() {
            try {
                const parsed = JSON.parse(localStorage.getItem(KEY) || '[]');
                const sanitized = sanitizeRecipes(parsed);
                localStorage.setItem(KEY, JSON.stringify(sanitized));
                return sanitized;
            } catch {
                return [];
            }
        }
        function setAll(arr, options = {}) {
            const sanitized = sanitizeRecipes(arr);
            localStorage.setItem(KEY, JSON.stringify(sanitized));
            if (options.notifyLimit && sanitized.length < arr.length && arr.length > MAX_ITEMS) {
                alert('Only the most recent four recipes were kept due to the local storage limit.');
            }
            return sanitized;
        }
        function render() {
            const items = getAll();
            listEl.innerHTML = '';
            emptyEl.style.display = items.length ? 'none' : 'block';
            items.forEach((r) => {
                const card = document.createElement('article');
                card.className = 'card';
                card.style.display = 'flex';
                card.style.flexDirection = 'column';
                card.style.gap = '0.9rem';
                card.innerHTML = `
                    <header style="display:flex; justify-content:space-between; align-items:flex-start; gap:0.75rem;">
                        <div>
                            <h3 class="recipe-title" style="margin:0 0 0.35rem;">${escapeHtml(r.title || 'Untitled recipe')}</h3>
                            <div class="recipe-meta" style="gap:0.45rem;">
                                <span class="recipe-tag">${escapeHtml(r.category || 'Uncategorized')}</span>
                                <span>Saved ${formatDate(r.savedAt)}</span>
                            </div>
                        </div>
                        <div style="display:flex; gap:0.5rem;">
                            <button class="btn btn-secondary btn-small" data-view="${r.id}">Details</button>
                            <button class="btn btn-danger btn-small" data-delete="${r.id}">Delete</button>
                        </div>
                    </header>
                    <section id="detail_${r.id}" style="display:none;">
                        <div class="recipe-section">
                            <div class="section-title">
                                <span class="icon-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 4.5h-3a2.25 2.25 0 00-2.25 2.25v3m5.25-5.25h9m0 0h3a2.25 2.25 0 012.25 2.25v3m-5.25-5.25v15m0 0h-9m9 0h3a2.25 2.25 0 002.25-2.25v-3m-14.25 5.25h-3A2.25 2.25 0 012.25 18v-3m0-6v9" />
                                    </svg>
                                </span>
                                Ingredients
                            </div>
                            <div class="note-box" style="background:rgba(56,182,255,0.06); border:none;">
                                ${formatMultiline(r.ingredients)}
                            </div>
                        </div>
                        <div class="recipe-section">
                            <div class="section-title">
                                <span class="icon-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487zm0 0L19.5 7.125" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 13.5V21" />
                                    </svg>
                                </span>
                                Instructions
                            </div>
                            <div class="note-box" style="background:rgba(56,182,255,0.06); border:none;">
                                ${formatMultiline(r.instructions)}
                            </div>
                        </div>
                        ${r.summary ? `
                        <div class="recipe-section">
                            <div class="section-title">
                                <span class="icon-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9.75L9 12l2.25 2.25m3-4.5L12 12l2.25 2.25M3.75 6h16.5M3.75 9.75h16.5M3.75 13.5h16.5M3.75 17.25h16.5" />
                                    </svg>
                                </span>
                                Personal Notes
                            </div>
                            <div class="note-box" style="background:rgba(56,182,255,0.06); border:none;">
                                ${formatMultiline(r.summary)}
                            </div>
                        </div>` : ''}
                    </section>
                `;
                listEl.appendChild(card);
            });
        }

        function formatDate(iso) {
            if (!iso) return '—';
            try { return new Date(iso).toLocaleString(); } catch { return iso; }
        }
        function escapeHtml(str) {
            return String(str).replace(/[&<>\"]/g, function (s) {
                return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[s]);
            });
        }
        function formatMultiline(text) {
            if (!text) return '';
            return '<pre style="white-space:pre-wrap; margin:0; font-family:inherit;">' + escapeHtml(text) + '</pre>';
        }

        document.addEventListener('click', function(e) {
            const viewId = e.target.getAttribute && e.target.getAttribute('data-view');
            if (viewId) {
                const panel = document.getElementById('detail_' + viewId);
                if (panel) {
                    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
                }
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
                    const merged = Array.isArray(incoming) ? current.concat(incoming) : current;
                    setAll(merged, { notifyLimit: true });
                    render();
                } catch (err) {
                    alert('Invalid JSON file.');
                }
            };
            reader.readAsText(file);
            importFile.value = '';
        });

        render();
    })();
</script>
@endpush
