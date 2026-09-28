(() => {
  const tokenInput = document.querySelector('#token');
  const authState = document.querySelector('#auth-state');
  const appRoot = new URL('./', window.location.href);
  const endpointPaths = {
    getAll: 'get_all_client.php',
    getById: (id) => `get_client_id.php/${encodeURIComponent(id)}`,
    create: 'create_client.php',
    update: 'update_client.php',
    delete: 'delete_client.php',
  };
  const configPromise = fetch(new URL('api/config.php', appRoot), { headers: { Accept: 'application/json' } })
    .then((response) => {
      if (!response.ok) throw new Error(`No se pudo cargar la configuración (${response.status})`);
      return response.json();
    })
    .then((config) => {
      if (!config.apiBaseUrl) throw new Error('API_BASE_URL no está configurada.');
      config.apiBaseUrl = `${new URL(config.apiBaseUrl, appRoot).href.replace(/\/+$/, '')}/`;
      if (config.appName) document.title = `${config.appName} · Consola de clientes`;
      return config;
    });

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

  async function request(buttonId, responseId, endpoint, options = {}, pathParameter = '') {
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
      const config = await configPromise;
      const route = typeof endpointPaths[endpoint] === 'function'
        ? endpointPaths[endpoint](pathParameter)
        : endpointPaths[endpoint];
      const url = new URL(route, config.apiBaseUrl);
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
    request('btn-get-all', 'res-get-all', 'getAll'));

  document.querySelector('#btn-get-id').addEventListener('click', () => {
    const id = document.querySelector('#get-id').value.trim();
    if (!id) {
      document.querySelector('#get-id').focus();
      showResponse(document.querySelector('#res-get-id'), { status: 'VALIDACIÓN', body: 'Escribe el ID del cliente que quieres consultar.', error: true });
      return;
    }
    request('btn-get-id', 'res-get-id', 'getById', {}, id);
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
    request('btn-create', 'res-create', 'create', { method: 'POST', body: JSON.stringify(payload) });
  });

  document.querySelector('#btn-update').addEventListener('click', () => {
    const payload = {
      id: document.querySelector('#update-id').value.trim(),
      paterno: '',
      materno: '',
      nombres: document.querySelector('#update-names').value.trim(),
    };
    request('btn-update', 'res-update', 'update', { method: 'PATCH', body: JSON.stringify(payload) });
  });

  document.querySelector('#btn-delete').addEventListener('click', () => {
    const id = document.querySelector('#delete-id').value.trim();
    if (!id) {
      document.querySelector('#delete-id').focus();
      showResponse(document.querySelector('#res-delete'), { status: 'VALIDACIÓN', body: 'Escribe el ID del cliente que quieres eliminar.', error: true });
      return;
    }
    request('btn-delete', 'res-delete', 'delete', { method: 'DELETE', body: JSON.stringify({ id }) });
  });

  configPromise
    .then(({ apiBaseUrl }) => fetch(new URL('dev/token.php', apiBaseUrl), { headers: { Accept: 'application/json' } }))
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
