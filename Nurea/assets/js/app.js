/* Minimal UI enhancements: parallax hero and scroll reveal */
document.addEventListener('DOMContentLoaded', function () {
  // Parallax hero background
  const hero = document.querySelector('.hero-parallax');
  if (hero) {
    window.addEventListener('scroll', function () {
      const sc = window.scrollY;
      hero.style.backgroundPosition = `center ${Math.round(sc * 0.15)}px`;
    }, { passive: true });
  }

  // Scroll reveal for elements with .reveal
  const revealElems = Array.from(document.querySelectorAll('.reveal'));
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });

  revealElems.forEach(el => revealObserver.observe(el));

  // Small hover micro-interaction for buttons
  document.querySelectorAll('.btn-warning').forEach(btn => {
    btn.addEventListener('mouseenter', () => btn.style.transform = 'translateY(-2px)');
    btn.addEventListener('mouseleave', () => btn.style.transform = 'none');
  });
  
  // Simple toast helper: create toast container if missing
  let toastContainer = document.querySelector('.toast-container-custom');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container-custom';
    document.body.appendChild(toastContainer);
  }

  window.showToast = function (title, message, type = 'info', timeout = 5000) {
    const t = document.createElement('div');
    t.className = 'toast-custom ' + (type === 'success' ? 'success' : (type === 'error' ? 'error' : ''));
    t.setAttribute('role','status');
    t.setAttribute('aria-live','polite');

    const titleEl = document.createElement('div');
    titleEl.className = 'title';
    titleEl.textContent = title || '';

    const bodyEl = document.createElement('div');
    bodyEl.className = 'body';
    bodyEl.textContent = message || '';

    const closeBtn = document.createElement('button');
    closeBtn.className = 'toast-close';
    closeBtn.setAttribute('aria-label','Fermer');
    closeBtn.innerHTML = '&times;';

    t.appendChild(titleEl);
    t.appendChild(bodyEl);
    t.appendChild(closeBtn);

    toastContainer.appendChild(t);

    // animate in
    requestAnimationFrame(() => t.classList.add('visible'));

    let hideTimer = setTimeout(hide, timeout);

    function hide() {
      t.classList.remove('visible');
      setTimeout(() => { try{ t.remove(); } catch(e){} }, 320);
    }

    // pause on hover
    t.addEventListener('mouseenter', () => { clearTimeout(hideTimer); });
    t.addEventListener('mouseleave', () => { hideTimer = setTimeout(hide, 2500); });

    closeBtn.addEventListener('click', hide);
  };

  // Admin drawer toggle
  const drawerToggle = document.querySelector('.admin-drawer-toggle');
  const drawer = document.getElementById('adminDrawer');
  const drawerOverlay = document.getElementById('adminDrawerOverlay');
  const drawerClose = document.querySelector('.admin-drawer-close');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.add('open');
    if (drawerOverlay) drawerOverlay.classList.add('open');
    drawer.setAttribute('aria-hidden','false');
  }
  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.remove('open');
    if (drawerOverlay) drawerOverlay.classList.remove('open');
    drawer.setAttribute('aria-hidden','true');
  }

  if (drawerToggle) drawerToggle.addEventListener('click', openDrawer);
  if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
  if (drawerOverlay) drawerOverlay.addEventListener('click', closeDrawer);

  // close on Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDrawer();
  });
});
