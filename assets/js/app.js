// JS extraido de index.php
(function(){
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
})();
