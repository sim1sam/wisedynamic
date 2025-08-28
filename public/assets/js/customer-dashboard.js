(function(){
  const toggleBtn = document.getElementById('sidebar-toggle');
  const sidebar = document.getElementById('customer-sidebar');
  const backdrop = document.getElementById('sidebar-backdrop');
  if(!sidebar || !toggleBtn || !backdrop) return;

  const open = () => {
    document.body.classList.add('sidebar-open');
    document.body.classList.add('overflow-hidden');
    // also ensure Tailwind utility doesn't keep it off-canvas
    sidebar.classList.remove('-translate-x-full');
    toggleBtn.setAttribute('aria-expanded', 'true');
  };

  const close = () => {
    document.body.classList.remove('sidebar-open');
    document.body.classList.remove('overflow-hidden');
    // restore Tailwind utility on mobile
    sidebar.classList.add('-translate-x-full');
    toggleBtn.setAttribute('aria-expanded', 'false');
  };

  const isOpen = () => document.body.classList.contains('sidebar-open');

  toggleBtn.addEventListener('click', function(){
    isOpen() ? close() : open();
  });

  backdrop.addEventListener('click', close);

  document.addEventListener('keydown', (e)=>{
    if(e.key === 'Escape' && isOpen()) close();
  });

  // Ensure correct state on resize
  const mq = window.matchMedia('(min-width: 768px)');
  const handleResize = () => {
    if(mq.matches){
      // desktop: no off-canvas, clear locks
      document.body.classList.remove('sidebar-open');
      document.body.classList.remove('overflow-hidden');
      toggleBtn.setAttribute('aria-expanded', 'true');
    } else {
      // mobile: start closed
      document.body.classList.remove('sidebar-open');
      document.body.classList.remove('overflow-hidden');
      toggleBtn.setAttribute('aria-expanded', 'false');
    }
  };
  handleResize();
  mq.addEventListener('change', handleResize);

  // Make the "Back to site" link open the sidebar on mobile
  const openSidebarLink = document.getElementById('open-sidebar-link');
  if(openSidebarLink){
    openSidebarLink.addEventListener('click', (e)=>{
      const isMobile = !mq.matches; // <768px
      if(isMobile){
        e.preventDefault();
        open();
      }
    });
  }
})();
