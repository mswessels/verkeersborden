(() => {
  const root = document.documentElement;
  const storageKey = "rvv-theme";
  const toggle = document.querySelector("[data-theme-toggle]");
  const label = document.querySelector("[data-theme-label]");
  const media = window.matchMedia("(prefers-color-scheme: dark)");

  const getStoredTheme = () => {
    try {
      const value = window.localStorage.getItem(storageKey);
      if (value === "light" || value === "dark") {
        return value;
      }
    } catch (error) {
      return null;
    }
    return null;
  };

  const setStoredTheme = (theme) => {
    try {
      window.localStorage.setItem(storageKey, theme);
    } catch (error) {
      return;
    }
  };

  const applyTheme = (theme) => {
    root.setAttribute("data-theme", theme);
    if (toggle) {
      toggle.setAttribute("aria-pressed", theme === "dark" ? "true" : "false");
    }
    if (label) {
      label.textContent = theme === "dark" ? "donker" : "licht";
    }
  };

  const storedTheme = getStoredTheme();
  if (storedTheme) {
    applyTheme(storedTheme);
  } else {
    applyTheme(media.matches ? "dark" : "light");
  }

  if (toggle) {
    toggle.addEventListener("click", () => {
      const current = root.getAttribute("data-theme");
      const next = current === "dark" ? "light" : "dark";
      setStoredTheme(next);
      applyTheme(next);
    });
  }

  const handleMediaChange = (event) => {
    if (getStoredTheme()) {
      return;
    }
    applyTheme(event.matches ? "dark" : "light");
  };

  if (typeof media.addEventListener === "function") {
    media.addEventListener("change", handleMediaChange);
  } else if (typeof media.addListener === "function") {
    media.addListener(handleMediaChange);
  }

  const sections = document.querySelectorAll(".rvv-main [id='divisie']");
  sections.forEach((section, index) => {
    const delay = Math.min(index, 10) * 70;
    section.style.setProperty("--rvv-delay", `${delay}ms`);
  });

  const stickyBar = document.querySelector("[data-sticky-cta]");
  if (stickyBar) {
    document.body.classList.add("has-sticky-cta");
    const primaryCta = document.querySelector("[data-primary-cta]");

    if ("IntersectionObserver" in window && primaryCta) {
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            stickyBar.classList.toggle("is-active", !entry.isIntersecting);
          });
        },
        { threshold: 0.4 },
      );
      observer.observe(primaryCta);
    } else {
      stickyBar.classList.add("is-active");
    }
  }
})();
