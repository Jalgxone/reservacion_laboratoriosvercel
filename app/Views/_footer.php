    </main>
    <footer style="background-color: white; color: var(--color-texto-claro); padding: var(--espacio-md) 0; margin-top: <?= ($fullWidth ?? false) ? '0' : 'var(--espacio-xl)' ?>; border-top: 1px solid var(--color-borde);">
        <div class="contenedor" style="display: flex; align-items: center; justify-content: center; gap: var(--espacio-lg);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 28px; height: 28px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 4px; border: 1px solid var(--color-borde);">
                    <img src="<?= $base ?>/logo.png" alt="LabReserva Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <span style="font-weight: 700; color: var(--color-primario); letter-spacing: 0.05em; font-size: 1rem;">LabReserva</span>
            </div>
            <div style="height: 14px; width: 1px; background-color: var(--color-borde);"></div>
            <p style="font-size: 0.85rem; margin: 0; font-weight: 500;">&copy; <?= date('Y') ?> Sistema de Reservación de Laboratorios</p>
        </div>
    </footer>
</body>
</html>
<script>

window.appConfirm = function(message) {
    return new Promise(function(resolve) {
        let modal = document.getElementById('app-confirm-modal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'app-confirm-modal';
            modal.style.position = 'fixed';
            modal.style.left = '0';
            modal.style.top = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.background = 'rgba(0,0,0,0.45)';
            modal.style.zIndex = '9999';
            modal.innerHTML = `
                <div style="background: #1f1f1f; color: #fff; padding: 18px; border-radius: 8px; width: 420px; max-width:90%; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
                    <div id="app-confirm-message" style="margin-bottom: 14px; font-weight:600;"></div>
                    <div style="display:flex; gap:8px; justify-content:flex-end;">
                        <button id="app-confirm-cancel" style="background:#444; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;">Cancelar</button>
                        <button id="app-confirm-ok" style="background:#0b78e3; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;">Aceptar</button>
                    </div>
                </div>`;
            document.body.appendChild(modal);
        }

        const msgEl = modal.querySelector('#app-confirm-message');
        msgEl.textContent = message;

        modal.style.display = 'flex';

        const clean = () => { modal.style.display = 'none'; };

        const okBtn = modal.querySelector('#app-confirm-ok');
        const cancelBtn = modal.querySelector('#app-confirm-cancel');

        const onOk = function() { cleanupHandlers(); clean(); resolve(true); };
        const onCancel = function() { cleanupHandlers(); clean(); resolve(false); };

        function cleanupHandlers() {
            okBtn.removeEventListener('click', onOk);
            cancelBtn.removeEventListener('click', onCancel);
        }

        okBtn.addEventListener('click', onOk);
        cancelBtn.addEventListener('click', onCancel);
    });
};


document.addEventListener('click', function(e) {
    const el = e.target.closest && e.target.closest('[data-confirm]');
    if (!el) return;


    const href = el.getAttribute('href');
    if (!href) return;

    e.preventDefault();
    const msg = el.getAttribute('data-confirm') || '¿Confirma?';
    window.appConfirm(msg).then(function(ok) {
        if (!ok) return;

        window.location.href = href;
    });
});
</script>
