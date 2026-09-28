(() => {
  const tokenInput = document.querySelector('#token');
  const authState = document.querySelector('#auth-state');
  const baseUrl = new URL('./', window.location.href);

  document.querySelector('#toggle-token').addEventListener('click', (event) => {
    const button = event.currentTarget;
    const visible = tokenInput.type === 'password';
    tokenInput.type = visible ? 'text' : 'password';
    button.setAttribute('aria-label', visible ? 'Ocultar token' : 'Mostrar token');
    button.title = visible ? 'Ocultar token' : 'Mostrar token';
  });

  document.querySelector('#clear-token').addEventListener('click', () => {
    tokenInput.value = '';
    tokenInput.focus();
    setAuthState('manual', 'Token no configurado');
  });

  function setAuthState(state, message) {
    authState.className = `auth-state ${state}`;
    authState.innerHTML = '<span class="state-dot"></span>';
    authState.append(document.createTextNode(` ${message}`));
  }

  function getHeaders(json = false) {
    const headers = { Authorization: `Bearer ${tokenInput.value.trim()}` };
    if (json) headers['Content-Type'] = 'application/json';
    return headers;
  }

  function showResponse(element, { status = '…', duration = '', body = 'Enviando solicitud…', loading = false, error = false }) {
    element.className = `response visible${loading ? ' loading' : ''}`;
    element.replaceChildren();

    const header = document.createElement('div');
    header.className = 'response-head';
    const statusBadge = document.createElement('span');
    statusBadge.className = `response-status${error ? ' error' : ''}`;
    statusBadge.textContent = status;
    const time = document.createElement('span');
    time.className = 'response-time';
    time.textContent = duration;
    header.append(statusBadge, time);

    const output = document.createElement('pre');
    output.textContent = body;
    element.append(header, output);
  }

  async function request(buttonId, responseId, path, options = {}) {
    const button = document.getElementById(buttonId);
    const output = document.getElementById(responseId);
    const token = tokenInput.value.trim();

    if (!token) {
      showResponse(output, { status: 'TOKEN', body: 'Configura o pega un token antes de llamar al endpoint.', error: true });
      tokenInput.focus();
      setAuthState('manual', 'Token requerido');
      return;
    }

    const originalContent = button.innerHTML;
    button.disabled = true;
    button.textContent = 'Enviando…';
    showResponse(output, { status: '…', body: 'Esperando respuesta del servidor…', loading: true });
    const start = performance.now();

    try {
      const url = new URL(path, baseUrl);
      const response = await fetch(url, {
        ...options,
        headers: { ...getHeaders(Boolean(options.body)), ...options.headers },
      });
      const text = await response.text();
      let body = text || '(respuesta vacía)';
      try {
        body = JSON.stringify(JSON.parse(text), null, 2);
      } catch {
        // Algunas respuestas de error del servidor pueden no estar en formato JSON.
      }
      const elapsed = `${Math.round(performance.now() - start)} ms`;
      showResponse(output, { status: `${response.status} ${response.statusText}`, duration: elapsed, body, error: !response.ok });
      if (response.status === 401) setAuthState('manual', 'Token rechazado');
    } catch (error) {
      showResponse(output, { status: 'ERROR', duration: `${Math.round(performance.now() - start)} ms`, body: error.message || 'No se pudo conectar con el servidor.', error: true });
    } finally {
      button.disabled = false;
      button.innerHTML = originalContent;
    }
  }

  document.querySelector('#btn-get-all').addEventListener('click', () =>
    request('btn-get-all', 'res-get-all', 'api/get_all_client.php'));

  document.querySelector('#btn-get-id').addEventListener('click', () => {
    const id = document.querySelector('#get-id').value.trim();
    if (!id) {
      document.querySelector('#get-id').focus();
      showResponse(document.querySelector('#res-get-id'), { status: 'VALIDACIÓN', body: 'Escribe el ID del cliente que quieres consultar.', error: true });
      return;
    }
    request('btn-get-id', 'res-get-id', `api/get_client_id.php/${encodeURIComponent(id)}`);
  });

  document.querySelector('#btn-create').addEventListener('click', () => {
    const payload = {
      id: document.querySelector('#create-id').value.trim(),
      paterno: '',
      materno: '',
      nombres: document.querySelector('#create-names').value.trim(),
      correo: document.querySelector('#create-email').value.trim(),
      clave: 'changeme',
      semilla: 'seed',
    };
    request('btn-create', 'res-create', 'api/create_client.php', { method: 'POST', body: JSON.stringify(payload) });
  });

  document.querySelector('#btn-update').addEventListener('click', () => {
    const payload = {
      id: document.querySelector('#update-id').value.trim(),
      paterno: '',
      materno: '',
      nombres: document.querySelector('#update-names').value.trim(),
    };
    request('btn-update', 'res-update', 'api/update_client.php', { method: 'PATCH', body: JSON.stringify(payload) });
  });

  document.querySelector('#btn-delete').addEventListener('click', () => {
    const id = document.querySelector('#delete-id').value.trim();
    if (!id) {
      document.querySelector('#delete-id').focus();
      showResponse(document.querySelector('#res-delete'), { status: 'VALIDACIÓN', body: 'Escribe el ID del cliente que quieres eliminar.', error: true });
      return;
    }
    request('btn-delete', 'res-delete', 'api/delete_client.php', { method: 'DELETE', body: JSON.stringify({ id }) });
  });

  fetch(new URL('api/dev/token.php', baseUrl), { headers: { Accept: 'application/json' } })
    .then((response) => response.ok ? response.json() : Promise.reject(new Error('No disponible')))
    .then(({ token }) => {
      if (token) {
        tokenInput.value = token;
        setAuthState('ready', 'Token configurado');
      } else {
        setAuthState('manual', 'Pega tu token para comenzar');
      }
    })
    .catch(() => setAuthState('manual', 'Pega tu token para comenzar'));
})();
