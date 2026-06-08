(function () {
  const toggle = document.getElementById("header-menu-toggle");
  const panel = document.getElementById("header-mobile-panel");

  if (!toggle || !panel) {
    return;
  }

  function setOpen(open) {
    toggle.setAttribute("aria-expanded", String(open));
    panel.hidden = !open;
    document.body.classList.toggle("nav-open", open);
  }

  toggle.addEventListener("click", () => {
    setOpen(toggle.getAttribute("aria-expanded") !== "true");
  });

  panel.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => setOpen(false));
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth > 768) {
      setOpen(false);
    }
  });
})();
