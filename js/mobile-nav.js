(function () {
  function initMobileNav() {
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

    toggle.addEventListener("click", (event) => {
      event.preventDefault();
      event.stopPropagation();
      setOpen(toggle.getAttribute("aria-expanded") !== "true");
    });

    panel.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => setOpen(false));
    });

    document.addEventListener("click", (event) => {
      if (
        panel.hidden ||
        panel.contains(event.target) ||
        toggle.contains(event.target)
      ) {
        return;
      }
      setOpen(false);
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth > 768) {
        setOpen(false);
      }
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initMobileNav);
  } else {
    initMobileNav();
  }
})();
