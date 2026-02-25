<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ALIMA Gateway Control Center</title>
    <style>
        :root{--side:#1f62d8;--sideText:#e4efff;--sideActive:#1b4faf;--bg:#dff0ff;--card:#f9fcff;--line:#bfdcff;--text:#10243e;--muted:#4f6f9e;--blue:#1d6ff2;--gray:#5f789c;--danger:#e64545;--ok:#16a34a}
        *{box-sizing:border-box}body{margin:0;font-family:Segoe UI,Tahoma,sans-serif;background:var(--bg);color:var(--text)}
        .dash{min-height:100vh;display:grid;grid-template-columns:250px 1fr}
        .side{background:linear-gradient(180deg,#2a77ea,#1f62d8);padding:18px;border-right:1px solid #5c9bf3}
        .brand{display:flex;align-items:center;gap:10px;font-size:24px;font-weight:800;color:#fff;margin:4px 0 18px;line-height:1.1}
        .brand img{width:38px;height:38px;border-radius:10px}
        .menu{display:grid;gap:8px}.menu button{all:unset;cursor:pointer;padding:11px 12px;border-radius:10px;color:var(--sideText);font-weight:700}.menu button.active{background:var(--sideActive);color:#fff;border:1px solid #9ac2ff}
        .main{padding:16px;display:flex;flex-direction:column;gap:12px;min-height:100vh}
        .hero{background:linear-gradient(120deg,#1b4f95,#1d6ff2);color:#fff;border-radius:14px;padding:14px 16px;border:1px solid #3f7fd8;box-shadow:0 10px 20px rgba(29,111,242,.16)}
        .hero h1{margin:0;font-size:20px}
        .hero p{margin:4px 0 0;opacity:.92;font-size:13px}
        .topbar{background:#fff;border:1px solid var(--line);border-radius:12px;padding:12px;display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap}
        .topbar-left{display:grid;gap:8px;min-width:300px;flex:1}.topbar-right{display:flex;gap:8px;align-items:center}
        .topbar-app{display:grid;gap:8px;grid-template-columns:1fr auto}
        .card{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:12px;box-shadow:0 8px 16px rgba(29,111,242,.08)}.view{display:none}.view.active{display:block}
        .content-wrap{display:grid;gap:12px;flex:1}
        .grid3{display:grid;gap:12px;grid-template-columns:repeat(3,1fr)}.two{display:grid;gap:12px;grid-template-columns:1.2fr .8fr}
        .row{display:grid;gap:10px;grid-template-columns:repeat(auto-fit,minmax(160px,1fr))}.row.actions{grid-template-columns:repeat(auto-fit,minmax(120px,max-content));justify-content:flex-start}
        .value{font-size:30px;font-weight:800;color:#195fc5}label{display:block;font-size:12px;color:var(--muted);margin-bottom:4px;font-weight:700}
        input,textarea,select{width:100%;border:1px solid #bfd4f3;border-radius:9px;padding:9px;font-size:14px;background:#fff}textarea{min-height:100px}
        .btn{border:0;border-radius:9px;padding:9px 13px;color:#fff;background:var(--blue);cursor:pointer;font-weight:700}.btn.gray{background:var(--gray)}.btn.danger{background:var(--danger)}.btn.ok{background:#23954f}
        .status{padding:8px 10px;border-radius:9px;font-size:13px;background:#eaf3ff;border:1px solid #d2e3fb;color:#386292}.status.ok{color:#166534;background:#ecfdf3;border-color:#b7f7ce}.status.warn{color:#92400e;background:#fff8ea;border-color:#fde68a}
        table{width:100%;border-collapse:collapse;font-size:13px}th,td{padding:8px;border-bottom:1px solid #e5edf9;text-align:left;vertical-align:top}th{background:#eef5ff;color:#335f90}
        .badge{display:inline-block;border-radius:999px;padding:3px 9px;font-size:12px;border:1px solid #c8daf5;background:#e9f2ff}.mono{font-family:Consolas,monospace;font-size:12px;background:#f3f8ff;border:1px solid #d7e6fb;border-radius:9px;padding:8px;word-break:break-all}
        .qr-box{min-height:250px;border:1px dashed #a5c3eb;border-radius:12px;display:grid;place-items:center;background:#f7fbff;overflow:hidden}.qr-box img{max-width:100%}
        .scroll{overflow:auto}.split-title{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:8px}
        .app-footer{margin-top:auto;background:#fff;border:1px solid var(--line);border-radius:12px;padding:10px 12px;font-size:13px;color:#4f678c;display:flex;justify-content:space-between;gap:8px;flex-wrap:wrap}
        @media(max-width:980px){.dash{grid-template-columns:1fr}.grid3,.two{grid-template-columns:1fr}.topbar-right{width:100%}.topbar-right .btn{flex:1}}
    </style>
</head>
<body>
<section class="dash">
    <aside class="side">
        <div class="brand">
            <img src="{{ asset('assets/alima-gateway-logo.svg') }}" alt="ALIMA GATEWAY">
            <span>ALIMA<br>GATEWAY</span>
        </div>
        <nav class="menu">
            <button class="active" data-view="dashboardView">Dashboard</button>
            <button data-view="deviceView">Device</button>
            <button data-view="phonebookView">Contact</button>
            <button data-view="groupView">WA Group</button>
            <button data-view="historyView">Message History</button>
            <button data-view="sendView">Send</button>
        </nav>
    </aside>
    <main class="main">
        <div class="hero">
            <h1>ALIMA Gateway Control Center</h1>
            <p>Satu pintu operasional gateway: manajemen app, device, webhook, blast, dan histori pesan.</p>
        </div>
        <div class="topbar">
            <div class="topbar-left">
                <strong>Login: {{ $panelUser }}</strong>
                <div class="topbar-app">
                    <div><label>App Aktif</label><select id="appSelectTop"></select></div>
                    <div style="align-self:end"><button id="lockAppBtn" class="btn gray" type="button">Kunci App</button></div>
                </div>
                <div id="appLockStatus" class="status">Mode app belum dikunci.</div>
            </div>
            <div class="topbar-right">
                <button id="refreshAllBtn" class="btn gray">Refresh</button>
                <form method="post" action="{{ route('logout') }}">@csrf <button class="btn danger" type="submit">Logout</button></form>
            </div>
        </div>
        <div class="content-wrap">
        <section id="dashboardView" class="view active">
            <div class="grid3">
                <div class="card"><h3>Devices</h3><div id="statDevices" class="value">0</div></div>
                <div class="card"><h3>Connected</h3><div id="statConnected" class="value">0</div></div>
                <div class="card"><h3>Messages</h3><div id="statMessages" class="value">0</div></div>
            </div>
            <div class="card" style="margin-top:12px">
                <h3 style="margin-top:0">Aplikasi & Setting Blast</h3>
                <div class="row">
                    <div><label>App ID</label><input id="newAppId" placeholder="sinyal-saham-indo"></div>
                    <div><label>Nama App</label><input id="newAppName" placeholder="Sinyal Saham Indo"></div>
                    <div style="align-self:end"><button id="createAppBtn" class="btn">Buat / Update App</button></div>
                </div>
                <div class="row" style="margin-top:8px">
                    <div><label>Jeda Antar Pesan (menit)</label><input id="delayMinutes" type="number" min="0" step="0.1" value="0.3"></div>
                    <div><label>Max Kirim per Blast</label><input id="maxPerBatch" type="number" min="1" step="1" value="50"></div>
                    <div style="align-self:end"><button id="saveBlastSettingBtn" class="btn ok">Simpan Setting</button></div>
                </div>
                <div class="row" style="margin-top:8px">
                    <div><label>Webhook URL</label><input id="webhookUrl" placeholder="https://app-kamu.com/webhook/wa/inbound"></div>
                    <div><label>Webhook Secret</label><input id="webhookSecret" placeholder="token rahasia"></div>
                    <div><label>Aktifkan Webhook</label><select id="webhookEnabled"><option value="0">Nonaktif</option><option value="1">Aktif</option></select></div>
                    <div style="align-self:end"><button id="saveWebhookSettingBtn" class="btn ok">Simpan Webhook</button></div>
                </div>
                <div id="webhookTargetInfo" class="status" style="margin-top:8px">Webhook akan tersimpan ke app aktif.</div>
                <div class="status" style="margin-top:8px">
                    Security note: simpan <strong>Webhook Secret</strong> sama persis di AMAL (BM Gateway/Webhook) untuk verifikasi signature inbound.
                </div>
                <div id="apiKeyBox" class="mono" style="margin-top:8px">API key: -</div>
                <div class="row actions" style="margin-top:8px">
                    <button id="genWebhookSecretBtn" class="btn gray" type="button">Generate Webhook Secret</button>
                    <button id="copyApiKeyBtn" class="btn gray" type="button">Copy API Key</button>
                    <button id="regenApiKeyBtn" class="btn danger" type="button">Regenerate API Key</button>
                    <button id="deleteActiveAppBtn" class="btn danger" type="button">Hapus App Aktif</button>
                </div>
                <div id="appStatus" class="status" style="margin-top:8px">Belum ada app.</div>
            </div>
        </section>

        <section id="deviceView" class="view">
            <div class="two">
                <div class="card" id="contactCard">
                    <h3 style="margin-top:0">Add Device</h3>
                    <div class="row">
                        <div><label>Session ID</label><input id="sessionId" placeholder="wa628xxxx"></div>
                        <div><label>Label</label><input id="sessionLabel" placeholder="Nomor Utama"></div>
                        <div><label>Mode</label><select id="connectMode"><option value="qr">QR</option><option value="sms">SMS</option></select></div>
                        <div><label>Nomor SMS Mode</label><input id="pairPhone" placeholder="628xxxx"></div>
                        <div style="align-self:end"><button id="createSessionBtn" class="btn">Add Device</button></div>
                    </div>
                    <div id="sessionStatus" class="status" style="margin-top:8px">Belum ada aksi.</div>
                    <h3 style="margin:14px 0 8px">Device List</h3>
                    <div class="scroll"><table><thead><tr><th>Device</th><th>Status</th><th>Action</th></tr></thead><tbody id="devicesTable"></tbody></table></div>
                </div>
                <div class="card"><h3 style="margin-top:0">QR / Pairing Code</h3><div id="qrBox" class="qr-box">QR belum tersedia.</div></div>
            </div>
        </section>

        <section id="phonebookView" class="view">
            <div class="card" id="contactCard">
                <div class="split-title"><h3 style="margin:0">Contact</h3><button id="fillSelectedToSendBtn" class="btn gray">Pilih Kontak ke Send</button></div>
                <div class="row">
                    <div><label>Nama</label><input id="pbName" placeholder="Nama kontak"></div>
                    <div><label>Nomor WA</label><input id="pbPhone" placeholder="628xxxx"></div>
                    <div><label>Catatan</label><input id="pbNote" placeholder="opsional"></div>
                    <div style="align-self:end"><button id="addPhonebookBtn" class="btn">Add Contact</button></div>
                </div>
                <div class="row" style="margin-top:8px"><div><label>Cari Kontak</label><input id="pbSearch" placeholder="nama / nomor"></div></div>
                <div id="pbStatus" class="status" style="margin-top:8px">Belum ada aksi.</div>
                <div class="scroll" style="margin-top:10px"><table><thead><tr><th><input type="checkbox" id="pbCheckAll"></th><th>Nama</th><th>Phone</th><th>Note</th><th>Action</th></tr></thead><tbody id="phonebookTable"></tbody></table></div>
            </div>
        </section>

        <section id="groupView" class="view">
            <div class="card" id="groupCard">
                <h3 style="margin-top:0">WA Group</h3>
                <div class="row"><div><label>Pilih Device</label><select id="groupSessionSelect"></select></div><div style="align-self:end"><button id="loadGroupsBtn" class="btn">Update List Group</button></div></div>
                <div id="groupStatus" class="status" style="margin-top:8px">Pilih device lalu update.</div>
                <div class="scroll" style="margin-top:10px;max-height:520px"><table><thead><tr><th>ID Group</th><th>Nama Group</th><th>Member</th></tr></thead><tbody id="groupTable"></tbody></table></div>
            </div>
        </section>

        <section id="historyView" class="view">
            <div class="card">
                <h3 style="margin-top:0">Message History</h3>
                <div class="row"><div><label>Filter Session</label><input id="historySessionId" placeholder="kosong=semua"></div><div><label>Search Text</label><input id="historySearch" placeholder="cari pesan"></div></div>
                <div class="row actions" style="margin-top:8px"><button id="loadHistoryBtn" class="btn gray">Load</button><button id="deleteSelectedHistoryBtn" class="btn danger">Delete Selected</button><button id="deleteAllHistoryBtn" class="btn danger">Delete All</button><button id="downloadHistoryBtn" class="btn">Download</button></div>
                <div id="historyStatus" class="status" style="margin-top:8px">Belum load data.</div>
                <div class="scroll" style="margin-top:10px"><table><thead><tr><th><input type="checkbox" id="chkAllHistory"></th><th>Time</th><th>Device</th><th>Target</th><th>Type</th><th>Message</th><th>Status</th><th>Action</th></tr></thead><tbody id="historyTable"></tbody></table></div>
            </div>
        </section>

        <section id="sendView" class="view">
            <div class="card">
                <h3 style="margin-top:0">Send / Blast</h3>
                <div class="row"><div><label>Session ID</label><input id="blastSessionId" placeholder="wa628xxxx"></div><div><label>Pilih Kontak</label><select id="sendContactSelect"></select></div><div style="align-self:end"><button id="addSendContactBtn" class="btn gray">Tambah ke Target</button></div></div>
                <div class="row" style="margin-top:8px"><div><label>Target (1/baris)</label><textarea id="blastTargets"></textarea></div></div>
                <div style="margin-top:8px"><label>Pesan</label><textarea id="blastMessage"></textarea></div>
                <div style="margin-top:8px"><label>Image URL (opsional)</label><input id="blastImageUrl" placeholder="https://.../gambar.jpg"></div>
                <div style="margin-top:8px"><button id="sendBlastBtn" class="btn">Kirim</button></div>
                <div id="blastStatus" class="status" style="margin-top:8px">Belum kirim.</div>
                <div id="blastResult" class="mono" style="margin-top:8px">-</div>
            </div>
        </section>
        </div>
        <footer class="app-footer">
            <span>Copyright (c) {{ date('Y') }} Alima Creation</span>
            <span>ALIMA Gateway Panel</span>
        </footer>
    </main>
</section>

<script>
const csrf=document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const $=id=>document.getElementById(id);
const state={apps:[],sessions:[],contacts:[],groups:[],selectedContactIds:new Set(),selectedApp:null,historyItems:[],appLocked:false};
const api=async(path,opts={})=>{const res=await fetch('/panel/api'+path,{...opts,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,...(opts.headers||{})}});const body=await res.json().catch(()=>({}));if(!res.ok)throw new Error(body.message||'Request gagal');return body;};
const setStatus=(id,msg,kind='')=>{const el=$(id);el.className='status'+(kind?` ${kind}`:'');el.textContent=msg;};
function randomToken(prefix='sec'){const bytes=new Uint8Array(24);if(window.crypto&&window.crypto.getRandomValues){window.crypto.getRandomValues(bytes);}else{for(let i=0;i<bytes.length;i++)bytes[i]=Math.floor(Math.random()*256);}const hex=Array.from(bytes,b=>b.toString(16).padStart(2,'0')).join('');return `${prefix}_${hex}`;}
async function copyText(value){if(!value)return;try{if(navigator.clipboard&&window.isSecureContext){await navigator.clipboard.writeText(value);}else{const el=document.createElement('textarea');el.value=value;document.body.appendChild(el);el.select();document.execCommand('copy');document.body.removeChild(el);}setStatus('appStatus','API key berhasil dicopy.','ok');}catch(_e){setStatus('appStatus','Gagal copy otomatis. Silakan copy manual.','warn');}}
function bindMenu(){document.querySelectorAll('.menu button[data-view]').forEach(btn=>btn.addEventListener('click',()=>{document.querySelectorAll('.menu > button[data-view]').forEach(x=>x.classList.remove('active'));const targetView=btn.dataset.view;const topMenu=document.querySelector(`.menu > button[data-view="${targetView}"]`);if(topMenu)topMenu.classList.add('active');document.querySelectorAll('.view').forEach(v=>v.classList.remove('active'));$(targetView).classList.add('active');}));}
function updateAppLockUi(){const sel=$('appSelectTop');const btn=$('lockAppBtn');if(state.appLocked){sel.disabled=true;btn.textContent='Buka Kunci';setStatus('appLockStatus',`Terkunci ke app: ${state.selectedApp?.appId||'-'}`,'ok');}else{sel.disabled=false;btn.textContent='Kunci App';setStatus('appLockStatus','Mode app belum dikunci.','');}}
function updateAppPanel(app){state.selectedApp=app;$('newAppId').value=String(app.appId||'');$('newAppName').value=String(app.name||app.appId||'');$('apiKeyBox').textContent=`API key: ${app.apiKey||'-'}`;const delaySec=Number(app.blastSettings?.delaySeconds??20);$('delayMinutes').value=(delaySec/60).toFixed(2);$('maxPerBatch').value=String(app.blastSettings?.maxPerBatch??50);$('webhookUrl').value=String(app.webhookSettings?.url||'');$('webhookSecret').value=String(app.webhookSettings?.secret||'');$('webhookEnabled').value=(app.webhookSettings?.enabled===true)?'1':'0';setStatus('webhookTargetInfo',`Webhook tersimpan ke app aktif: ${app.appId}`,'ok');setStatus('appStatus',`App aktif ${app.appId}. delay=${delaySec}s max=${app.blastSettings?.maxPerBatch??50}`,'ok');}
function renderAppsTop(){const sel=$('appSelectTop');sel.innerHTML='';state.apps.forEach(app=>{const op=document.createElement('option');op.value=app.appId;op.textContent=`${app.appId} - ${app.name||app.appId}`;sel.appendChild(op);});const lockedId=localStorage.getItem('wa_locked_app_id')||'';state.appLocked=Boolean(lockedId);const selected=(state.appLocked?state.apps.find(x=>x.appId===lockedId):null)||state.apps.find(x=>x.appId===state.selectedApp?.appId)||state.apps[0]||null;if(selected){sel.value=selected.appId;updateAppPanel(selected);}else{$('newAppId').value='';$('newAppName').value='';$('webhookUrl').value='';$('webhookSecret').value='';$('apiKeyBox').textContent='API key: -';setStatus('webhookTargetInfo','Webhook akan tersimpan ke app aktif.','');}updateAppLockUi();}
function renderSessions(){const tbody=$('devicesTable');tbody.innerHTML='';const activeAppId=state.selectedApp?.appId||'';const sessions=(state.sessions||[]).filter(s=>!activeAppId||s.appId===activeAppId);sessions.forEach(s=>{const tr=document.createElement('tr');tr.innerHTML=`<td><strong>${s.sessionId}</strong><br>${s.label||'-'}<br><small>${s.appId}</small></td><td><span class="badge">${s.status||'-'}</span><br><small style="color:#a33">${s.lastError||''}</small></td><td><button class="btn gray" data-act="reconnect" data-id="${s.sessionId}">Reconnect</button> <button class="btn danger" data-act="disconnect" data-id="${s.sessionId}">Disconnect</button> <button class="btn gray" data-act="token" data-id="${s.sessionId}">Token</button> <button class="btn" data-act="edit" data-id="${s.sessionId}">Edit</button> <button class="btn danger" data-act="delete" data-id="${s.sessionId}">Delete</button></td>`;tbody.appendChild(tr);});$('statDevices').textContent=String(sessions.length);$('statConnected').textContent=String(sessions.filter(x=>x.status==='connected').length);const groupSel=$('groupSessionSelect');groupSel.innerHTML='';sessions.forEach(s=>{const op=document.createElement('option');op.value=s.sessionId;op.textContent=`${s.sessionId} (${s.status})`;groupSel.appendChild(op);});if(!sessions.length){setStatus('groupStatus','Belum ada device untuk app aktif ini.','warn');state.groups=[];renderGroups();}}
function renderPhonebook(){const q=($('pbSearch').value||'').toLowerCase().trim();const tbody=$('phonebookTable');tbody.innerHTML='';const filtered=state.contacts.filter(c=>!q||`${c.name||''} ${c.phone||''} ${c.note||''}`.toLowerCase().includes(q));filtered.forEach(c=>{const checked=state.selectedContactIds.has(String(c.id))?'checked':'';const tr=document.createElement('tr');tr.innerHTML=`<td><input type="checkbox" data-pb-check="1" data-id="${c.id}" ${checked}></td><td>${c.name||'-'}</td><td>${c.phone||'-'}</td><td>${c.note||'-'}</td><td><button class="btn danger" data-id="${c.id}" data-phonebook-del="1">Delete</button></td>`;tbody.appendChild(tr);});const sendSel=$('sendContactSelect');sendSel.innerHTML='<option value="">-- pilih kontak --</option>';state.contacts.forEach(c=>{const op=document.createElement('option');op.value=c.phone||'';op.textContent=`${c.name||c.phone} - ${c.phone||'-'}`;sendSel.appendChild(op);});}
function renderGroups(){const tbody=$('groupTable');tbody.innerHTML='';state.groups.forEach(g=>{const m=Array.isArray(g.participants)?g.participants.length:Number(g.size||0);const groupName=g.subject||g.name||'-';const tr=document.createElement('tr');tr.innerHTML=`<td>${g.id||'-'}</td><td>${groupName}</td><td>${m||0}</td>`;tbody.appendChild(tr);});}
function renderHistory(){const q=($('historySearch').value||'').toLowerCase();const tbody=$('historyTable');tbody.innerHTML='';const rows=state.historyItems.filter(x=>!q||String(x.text||'').toLowerCase().includes(q));rows.forEach(it=>{const tr=document.createElement('tr');tr.innerHTML=`<td><input type="checkbox" data-history-id="${it.id}" /></td><td>${new Date(it.createdAt).toLocaleString()}</td><td>${it.sessionId||'-'}</td><td>${it.to||it.from||'-'}</td><td>${it.direction||'-'}</td><td>${(it.text||'').replace(/</g,'&lt;')}</td><td>${it.status||'-'}</td><td><button class="btn danger" data-history-del="${it.id}">Delete</button></td>`;tbody.appendChild(tr);});$('statMessages').textContent=String(rows.length);}
async function loadApps(){const d=await api('/apps');state.apps=d.apps||[];renderAppsTop();}
async function loadSessions(){const d=await api('/sessions');state.sessions=d.sessions||[];renderSessions();}
async function loadPhonebook(){if(!state.selectedApp)return;const d=await api(`/phonebook?appId=${encodeURIComponent(state.selectedApp.appId)}`);state.contacts=d.items||[];renderPhonebook();}
async function loadHistory(){if(!state.selectedApp)return;const sid=$('historySessionId').value.trim();const d=await api(`/messages?appId=${encodeURIComponent(state.selectedApp.appId)}&sessionId=${encodeURIComponent(sid)}&limit=300`);state.historyItems=d.items||[];renderHistory();setStatus('historyStatus',`Loaded ${state.historyItems.length} history.`,'ok');}
async function loadGroups(){const sid=$('groupSessionSelect').value;if(!sid)return setStatus('groupStatus','Pilih device dulu.','warn');setStatus('groupStatus','Mengambil group...','warn');const d=await api(`/groups?sessionId=${encodeURIComponent(sid)}`);state.groups=d.items||[];renderGroups();setStatus('groupStatus',`Group loaded: ${state.groups.length}`,'ok');}
async function refreshAll(){await loadApps();await loadSessions();await loadPhonebook();await loadHistory();}
function getApiKey(){return state.selectedApp?.apiKey||'';}
function toggleAppLock(){if(!state.selectedApp)return;state.appLocked=!state.appLocked;if(state.appLocked){localStorage.setItem('wa_locked_app_id',state.selectedApp.appId);}else{localStorage.removeItem('wa_locked_app_id');}updateAppLockUi();}
async function createApp(){const appId=$('newAppId').value.trim();const name=$('newAppName').value.trim();if(!appId)return setStatus('appStatus','App ID wajib.','warn');await api('/apps',{method:'POST',body:JSON.stringify({appId,name})});await loadApps();const app=state.apps.find(x=>x.appId===appId);if(app){$('appSelectTop').value=appId;updateAppPanel(app);}setStatus('appStatus',`App ${appId} tersimpan.`, 'ok');}
async function saveBlastSetting(){if(!state.selectedApp)return setStatus('appStatus','Pilih app dulu.','warn');const delaySeconds=Math.max(0,Math.round(Number($('delayMinutes').value||0)*60));const maxPerBatch=Math.max(1,Number($('maxPerBatch').value||1));await api(`/apps/${state.selectedApp.appId}/blast-settings`,{method:'PUT',body:JSON.stringify({delaySeconds,maxPerBatch})});await loadApps();setStatus('appStatus','Setting blast disimpan.','ok');}
async function saveWebhookSetting(){if(!state.selectedApp)return setStatus('appStatus','Pilih app dulu.','warn');const payload={enabled:$('webhookEnabled').value==='1',url:$('webhookUrl').value.trim(),secret:$('webhookSecret').value.trim()};if(payload.enabled&&!payload.url)return setStatus('appStatus','Webhook URL wajib saat aktif.','warn');const appId=String(state.selectedApp.appId||'').trim();await api(`/apps/${appId}/webhook-settings`,{method:'PUT',body:JSON.stringify(payload)});await loadApps();const app=state.apps.find(x=>x.appId===appId);if(app){$('appSelectTop').value=appId;updateAppPanel(app);}setStatus('appStatus',`Setting webhook disimpan ke app aktif: ${appId}.`,'ok');}
async function regenerateApiKey(){if(!state.selectedApp)return setStatus('appStatus','Pilih app dulu.','warn');if(!confirm(`Regenerate API key untuk app ${state.selectedApp.appId}?`))return;const payload={apiKey:randomToken('ak')};await api(`/apps/${state.selectedApp.appId}/api-key`,{method:'PUT',body:JSON.stringify(payload)});await loadApps();setStatus('appStatus','API key baru sudah dibuat. Update juga di AMAL/client.','ok');}
async function deleteActiveApp(){if(!state.selectedApp)return setStatus('appStatus','Pilih app dulu.','warn');const appId=String(state.selectedApp.appId||'');if(!confirm(`Hapus app aktif ${appId}? Semua session, history, dan phonebook app ini ikut terhapus.`))return;await api(`/apps/${encodeURIComponent(appId)}`,{method:'DELETE'});if(localStorage.getItem('wa_locked_app_id')===appId){localStorage.removeItem('wa_locked_app_id');state.appLocked=false;}state.selectedApp=null;await refreshAll();setStatus('appStatus',`App ${appId} berhasil dihapus.`,'ok');}
async function addDevice(){if(!state.selectedApp)return setStatus('sessionStatus','Pilih app dulu.','warn');const payload={sessionId:$('sessionId').value.trim(),label:$('sessionLabel').value.trim(),appApiKey:getApiKey(),connectMode:$('connectMode').value,phoneNumber:$('pairPhone').value.trim()};if(!payload.sessionId)return setStatus('sessionStatus','Session ID wajib.','warn');const d=await api('/sessions',{method:'POST',body:JSON.stringify(payload)});$('blastSessionId').value=payload.sessionId;$('historySessionId').value=payload.sessionId;setStatus('sessionStatus',`Device ${payload.sessionId} dibuat. status=${d.session?.status||'connecting'}`,'ok');await showQr(payload.sessionId);await loadSessions();}
async function showQr(id){const d=await api(`/sessions/${id}/qr?appApiKey=${encodeURIComponent(getApiKey())}`);if(d.qr)$('qrBox').innerHTML=`<img src="${d.qr}" alt="qr"/>`;else if(d.pairingCode)$('qrBox').innerHTML=`<div class="mono">Pairing Code:<br><strong style="font-size:22px">${d.pairingCode}</strong></div>`;else $('qrBox').textContent=`QR/Code belum tersedia. status=${d.status}`;}
async function onDeviceAction(e){const btn=e.target.closest('button[data-act]');if(!btn)return;const act=btn.dataset.act,id=btn.dataset.id;if(act==='reconnect')await api(`/sessions/${id}/reconnect`,{method:'POST',body:JSON.stringify({connectMode:$('connectMode').value,phoneNumber:$('pairPhone').value.trim()})});if(act==='disconnect')await api(`/sessions/${id}/disconnect`,{method:'POST'});if(act==='delete'){if(!confirm(`Hapus ${id}?`))return;await api(`/sessions/${id}`,{method:'DELETE'});}if(act==='edit'){const label=prompt('Label baru','');if(label!==null)await api(`/sessions/${id}`,{method:'PUT',body:JSON.stringify({label})});}if(act==='token'){const d=await api(`/sessions/${id}/token`);alert(`Session: ${d.sessionId}\nApp: ${d.appId}\nAPI Key: ${d.apiKey||'-'}`);}if(act==='reconnect')await showQr(id);$('blastSessionId').value=id;$('historySessionId').value=id;await loadSessions();await loadHistory();}
async function addPhonebook(){if(!state.selectedApp)return;const payload={appId:state.selectedApp.appId,name:$('pbName').value.trim(),phone:$('pbPhone').value.trim(),note:$('pbNote').value.trim()};if(!payload.phone)return setStatus('pbStatus','Nomor wajib.','warn');await api('/phonebook',{method:'POST',body:JSON.stringify(payload)});$('pbName').value='';$('pbPhone').value='';$('pbNote').value='';await loadPhonebook();}
function appendTargets(phones){const current=$('blastTargets').value.split(/\r?\n/).map(x=>x.trim()).filter(Boolean);$('blastTargets').value=[...new Set([...current,...phones])].join('\n');}
async function sendBulk(){if(!state.selectedApp)return;const targets=$('blastTargets').value.split(/\r?\n/).map(x=>x.trim()).filter(Boolean);const payload={appApiKey:getApiKey(),sessionId:$('blastSessionId').value.trim(),message:$('blastMessage').value.trim(),imageUrl:$('blastImageUrl').value.trim()||null,targets};if(!payload.sessionId||!payload.targets.length)return setStatus('blastStatus','Session dan target wajib.','warn');if(!payload.message&&!payload.imageUrl)return setStatus('blastStatus','Isi pesan atau image URL wajib diisi.','warn');const d=await api('/send-bulk',{method:'POST',body:JSON.stringify(payload)});$('blastResult').textContent=JSON.stringify(d,null,2);await loadHistory();setStatus('blastStatus',`Blast diproses. sukses=${d.summary?.success??0}, gagal=${d.summary?.failed??0}`,'ok');}
function bind(){bindMenu();$('refreshAllBtn').addEventListener('click',refreshAll);$('lockAppBtn').addEventListener('click',toggleAppLock);$('appSelectTop').addEventListener('change',()=>{if(state.appLocked){const lockedId=localStorage.getItem('wa_locked_app_id')||'';$('appSelectTop').value=lockedId;return setStatus('appLockStatus',`App terkunci: ${lockedId}`,'warn');}const app=state.apps.find(x=>x.appId===$('appSelectTop').value);if(app){updateAppPanel(app);state.groups=[];renderGroups();setStatus('groupStatus','Pilih device lalu update.','');loadSessions();loadPhonebook();loadHistory();}});$('createAppBtn').addEventListener('click',createApp);$('saveBlastSettingBtn').addEventListener('click',saveBlastSetting);$('saveWebhookSettingBtn').addEventListener('click',saveWebhookSetting);$('genWebhookSecretBtn').addEventListener('click',()=>{$('webhookSecret').value=randomToken('whsec');setStatus('appStatus','Webhook secret berhasil digenerate. Klik Simpan Webhook.','ok');});$('copyApiKeyBtn').addEventListener('click',()=>copyText(state.selectedApp?.apiKey||''));$('regenApiKeyBtn').addEventListener('click',regenerateApiKey);$('deleteActiveAppBtn').addEventListener('click',deleteActiveApp);$('createSessionBtn').addEventListener('click',addDevice);$('devicesTable').addEventListener('click',onDeviceAction);$('addPhonebookBtn').addEventListener('click',addPhonebook);$('pbSearch').addEventListener('input',renderPhonebook);$('phonebookTable').addEventListener('click',async e=>{const b=e.target.closest('button[data-phonebook-del]');if(!b)return;await api(`/phonebook/${b.dataset.id}`,{method:'DELETE'});state.selectedContactIds.delete(String(b.dataset.id));await loadPhonebook();});$('phonebookTable').addEventListener('change',e=>{const cb=e.target.closest('input[data-pb-check]');if(!cb)return;cb.checked?state.selectedContactIds.add(String(cb.dataset.id)):state.selectedContactIds.delete(String(cb.dataset.id));});$('pbCheckAll').addEventListener('change',e=>{document.querySelectorAll('input[data-pb-check]').forEach(cb=>{cb.checked=e.target.checked;const id=String(cb.dataset.id);e.target.checked?state.selectedContactIds.add(id):state.selectedContactIds.delete(id);});});$('fillSelectedToSendBtn').addEventListener('click',()=>{const map=new Map(state.contacts.map(x=>[String(x.id),x.phone]));appendTargets(Array.from(state.selectedContactIds).map(id=>map.get(id)).filter(Boolean));});$('loadGroupsBtn').addEventListener('click',async()=>{try{await loadGroups();}catch(err){setStatus('groupStatus',err.message||'Gagal load group.','warn');}});$('addSendContactBtn').addEventListener('click',()=>{const phone=$('sendContactSelect').value.trim();if(phone)appendTargets([phone]);});$('sendBlastBtn').addEventListener('click',sendBulk);$('loadHistoryBtn').addEventListener('click',loadHistory);$('historySearch').addEventListener('input',renderHistory);$('deleteSelectedHistoryBtn').addEventListener('click',async()=>{const ids=Array.from(document.querySelectorAll('input[data-history-id]:checked')).map(x=>x.dataset.historyId);if(!ids.length)return;await api('/messages/delete-bulk',{method:'POST',body:JSON.stringify({ids})});await loadHistory();});$('deleteAllHistoryBtn').addEventListener('click',async()=>{if(!confirm('Hapus semua history?'))return;await api('/messages',{method:'DELETE'});await loadHistory();});$('downloadHistoryBtn').addEventListener('click',()=>{const sid=$('historySessionId').value.trim();window.open(`/panel/api/messages/export.csv?appId=${encodeURIComponent(state.selectedApp?.appId||'')}&sessionId=${encodeURIComponent(sid)}`,'_blank');});$('historyTable').addEventListener('click',async e=>{const b=e.target.closest('button[data-history-del]');if(!b)return;await api(`/messages/${b.dataset.historyDel}`,{method:'DELETE'});await loadHistory();});$('chkAllHistory').addEventListener('change',e=>document.querySelectorAll('input[data-history-id]').forEach(x=>x.checked=e.target.checked));}
bind();refreshAll().catch(e=>alert(e.message));
</script>
</body>
</html>
