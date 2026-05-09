<?php
// Página de bienvenida simple para la API
// Muestra endpoints, formularios y ejemplos curl
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>API REST PHP — Welcome</title>
  <style>
    body{font-family:Inter,system-ui,Arial,Helvetica,sans-serif;background:#f6f8fa;color:#0b1220;padding:28px}
    .wrap{max-width:980px;margin:0 auto}
    header{display:flex;align-items:center;gap:16px}
    h1{margin:0;font-size:22px}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:18px}
    .card{background:#fff;border:1px solid #e1e4e8;border-radius:8px;padding:14px}
    pre{background:#0b1220;color:#c9f1ff;padding:10px;border-radius:6px;overflow:auto}
    label{display:block;font-size:13px;margin-top:8px}
    input[type=text],input[type=email],input[type=password]{width:100%;padding:8px;margin-top:6px;border:1px solid #d0d7de;border-radius:6px}
    button{background:#0366d6;color:#fff;padding:8px 12px;border:0;border-radius:6px;cursor:pointer}
    .res{white-space:pre-wrap;background:#111827;color:#d1fae5;padding:10px;border-radius:6px;margin-top:8px}
    .full{grid-column:1/3}
    small{color:#6b7280}
  </style>
</head>
<body>
  <div class="wrap">
    <header>
      <h1>Bienvenido — API REST PHP (demostración)</h1>
      <small>Endpoints: create, delete, get, update</small>
    </header>

    <p>Usa el campo <strong>Token</strong> para enviar el header <code>Authorization: Bearer &lt;token&gt;</code> requerido por los endpoints.</p>

    <div class="card">
      <label>Token (obtenlo de <code>includes/Client.class.php</code> si usas el token estático)</label>
      <input id="token" type="text" placeholder="Bearer token value" />
    </div>

    <div class="grid">
      <div class="card">
        <h3>GET — Listar clientes</h3>
        <p>Endpoint: <code>/api/get_all_client.php</code></p>
        <button id="btn-get-all">Llamar GET</button>
        <div id="res-get-all" class="res" aria-live="polite"></div>
        <pre>curl -H "Authorization: Bearer &lt;token&gt;" "{{base}}/api/get_all_client.php"</pre>
      </div>

      <div class="card">
        <h3>GET — Cliente por ID</h3>
        <label>ID de usuario</label>
        <input id="get-id" type="text" placeholder="00001" />
        <button id="btn-get-id">Llamar GET por ID</button>
        <div id="res-get-id" class="res"></div>
        <pre>curl -H "Authorization: Bearer &lt;token&gt;" "{{base}}/api/get_client_id.php/00001"</pre>
      </div>

      <div class="card">
        <h3>POST — Crear cliente</h3>
        <label>ID</label>
        <input id="create-id" type="text" value="00011" />
        <label>Nombres</label>
        <input id="create-names" type="text" value="Nombre" />
        <label>Correo</label>
        <input id="create-email" type="email" value="nuevo@example.com" />
        <button id="btn-create">Enviar POST</button>
        <div id="res-create" class="res"></div>
        <pre>curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer &lt;token&gt;" -d '{...}' "{{base}}/api/create_client.php"</pre>
      </div>

      <div class="card">
        <h3>POST — Actualizar cliente</h3>
        <label>ID</label>
        <input id="update-id" type="text" value="00001" />
        <label>Nombres</label>
        <input id="update-names" type="text" value="NuevoNombre" />
        <button id="btn-update">Enviar POST</button>
        <div id="res-update" class="res"></div>
        <pre>curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer &lt;token&gt;" -d '{...}' "{{base}}/api/update_client.php"</pre>
      </div>

      <div class="card full">
        <h3>POST — Eliminar cliente</h3>
        <label>ID</label>
        <input id="delete-id" type="text" value="00010" />
        <button id="btn-delete">Enviar POST</button>
        <div id="res-delete" class="res"></div>
        <pre>curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer &lt;token&gt;" -d '{"id":"00010"}' "{{base}}/api/delete_client.php"</pre>
      </div>

      <div class="card full">
        <h3>Utilidades</h3>
        <p><a href="db/migrate.php">Ejecutar migración (db/migrate.php)</a> — crea BD y carga datos de prueba.</p>
        <p><a href="README.md">Leer documentación</a></p>
      </div>
    </div>
  </div>

  <script>
    const base = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '');
    document.querySelectorAll('pre').forEach(p => p.innerText = p.innerText.replace('{{base}}', base));

    function getToken(){
      return document.getElementById('token').value.trim();
    }

    async function fetchJson(url, options={}){
      try{
        const res = await fetch(url, options);
        const text = await res.text();
        try{ return JSON.stringify(JSON.parse(text), null, 2); } catch(e){ return text; }
      } catch(e){ return 'ERROR: '+e.message; }
    }

    document.getElementById('btn-get-all').addEventListener('click', async ()=>{
      const token = getToken();
      const url = 'api/get_all_client.php';
      const out = await fetchJson(url, { headers: { 'Authorization': 'Bearer '+token } });
      document.getElementById('res-get-all').innerText = out;
    });

    document.getElementById('btn-get-id').addEventListener('click', async ()=>{
      const token = getToken();
      const id = document.getElementById('get-id').value.trim();
      if(!id){ document.getElementById('res-get-id').innerText='Ingrese ID'; return; }
      const url = `api/get_client_id.php/${encodeURIComponent(id)}`;
      const out = await fetchJson(url, { headers: { 'Authorization': 'Bearer '+token } });
      document.getElementById('res-get-id').innerText = out;
    });

    document.getElementById('btn-create').addEventListener('click', async ()=>{
      const token = getToken();
      const payload = {
        id: document.getElementById('create-id').value.trim(),
        paterno: '',
        materno: '',
        nombres: document.getElementById('create-names').value.trim(),
        correo: document.getElementById('create-email').value.trim(),
        clave: 'changeme',
        semilla: 'seed'
      };
      const out = await fetchJson('api/create_client.php', { method:'POST', headers: { 'Content-Type':'application/json', 'Authorization':'Bearer '+token }, body: JSON.stringify(payload) });
      document.getElementById('res-create').innerText = out;
    });

    document.getElementById('btn-update').addEventListener('click', async ()=>{
      const token = getToken();
      const payload = { id: document.getElementById('update-id').value.trim(), paterno:'', materno:'', nombres: document.getElementById('update-names').value.trim() };
      const out = await fetchJson('api/update_client.php', { method:'POST', headers:{ 'Content-Type':'application/json','Authorization':'Bearer '+token }, body: JSON.stringify(payload) });
      document.getElementById('res-update').innerText = out;
    });

    document.getElementById('btn-delete').addEventListener('click', async ()=>{
      const token = getToken();
      const payload = { id: document.getElementById('delete-id').value.trim() };
      const out = await fetchJson('api/delete_client.php', { method:'POST', headers:{ 'Content-Type':'application/json','Authorization':'Bearer '+token }, body: JSON.stringify(payload) });
      document.getElementById('res-delete').innerText = out;
    });
  </script>
</body>
</html>
