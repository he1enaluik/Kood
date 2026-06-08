(function () {
  function initCarousel(trackSelector, prevSelector, nextSelector, cardSelector) {
    const track = document.querySelector(trackSelector);
    const prev = document.querySelector(prevSelector);
    const next = document.querySelector(nextSelector);

    if (!track || !prev || !next) return;

    const scrollStep = () => {
      const card = track.querySelector(cardSelector);
      if (!card) return 0;
      const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
      return card.offsetWidth + gap;
    };

    const updateButtons = () => {
      const maxScroll = track.scrollWidth - track.clientWidth;
      prev.disabled = track.scrollLeft <= 1;
      next.disabled = track.scrollLeft >= maxScroll - 1;
    };

    prev.addEventListener("click", () => {
      track.scrollBy({ left: -scrollStep(), behavior: "smooth" });
    });

    next.addEventListener("click", () => {
      track.scrollBy({ left: scrollStep(), behavior: "smooth" });
    });

    track.addEventListener("scroll", updateButtons, { passive: true });
    window.addEventListener("resize", updateButtons);
    updateButtons();
  }

  function initEventsCarousel() {
    const track = document.querySelector(".events__track");
    const prev = document.querySelector(".events__arrow--prev");
    const next = document.querySelector(".events__arrow--next");
    if (!track || !prev || !next) return;

    const originals = [...track.querySelectorAll(".event-card:not(.event-card--clone)")];
    if (!originals.length) return;

    originals.forEach((card) => {
      const clone = card.cloneNode(true);
      clone.classList.add("event-card--clone");
      clone.setAttribute("aria-hidden", "true");
      clone.querySelectorAll("a, button").forEach((el) => {
        el.tabIndex = -1;
      });
      track.appendChild(clone);
    });

    const cardStep = () => {
      const gap = parseFloat(getComputedStyle(track).gap) || 0;
      return originals[0].offsetWidth + gap;
    };

    const loopWidth = () => cardStep() * originals.length;

    let jumping = false;

    const normalize = () => {
      if (jumping) return;
      const loop = loopWidth();
      if (loop <= 0) return;

      if (track.scrollLeft >= loop) {
        jumping = true;
        track.style.scrollBehavior = "auto";
        track.scrollLeft -= loop;
        track.style.scrollBehavior = "";
        jumping = false;
      }
    };

    const jumpToCloneStart = () => {
      jumping = true;
      track.style.scrollBehavior = "auto";
      track.scrollLeft += loopWidth();
      track.style.scrollBehavior = "";
      jumping = false;
    };

    track.addEventListener("scroll", normalize, { passive: true });

    next.addEventListener("click", () => {
      track.scrollBy({ left: cardStep(), behavior: "smooth" });
    });

    prev.addEventListener("click", () => {
      if (track.scrollLeft <= 1) {
        jumpToCloneStart();
      }
      track.scrollBy({ left: -cardStep(), behavior: "smooth" });
    });

    window.addEventListener("resize", normalize);
  }

  document.addEventListener("DOMContentLoaded", () => {
    initCarousel(".clients-favourites__track", ".clients-favourites__arrow--prev", ".clients-favourites__arrow--next", ".product-card");
    initEventsCarousel();
  });
})();
