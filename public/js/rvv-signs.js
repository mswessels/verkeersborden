(() => {
  const meta = document.querySelector("[data-sign-meta]");
  if (!meta) {
    return;
  }

  const sign = {
    code: meta.getAttribute("data-sign-code") || "",
    title: meta.getAttribute("data-sign-title") || "",
    url: meta.getAttribute("data-sign-url") || "",
    image: meta.getAttribute("data-sign-image") || "",
  };

  const learnedKey = "rvv-learned-signs";
  const recentKey = "rvv-recent-signs";
  const maxRecent = 6;

  const readList = (key) => {
    try {
      const raw = window.localStorage.getItem(key);
      const data = raw ? JSON.parse(raw) : [];
      return Array.isArray(data) ? data : [];
    } catch (error) {
      return [];
    }
  };

  const writeList = (key, list) => {
    try {
      window.localStorage.setItem(key, JSON.stringify(list));
    } catch (error) {
      return;
    }
  };

  const normalizeRecentItem = (item) => {
    if (!item || typeof item !== "object") {
      return null;
    }
    if (!item.code || !item.url) {
      return null;
    }
    return {
      code: String(item.code),
      title: String(item.title || ""),
      url: String(item.url),
      image: String(item.image || ""),
    };
  };

  const updateLearnedUI = (isLearned) => {
    const toggle = document.querySelector("[data-learn-toggle]");
    const status = document.querySelector("[data-learn-status]");
    if (toggle) {
      toggle.classList.toggle("is-active", isLearned);
      toggle.textContent = isLearned ? "Gemarkeerd als geleerd" : "Markeer als geleerd";
    }
    if (status) {
      status.textContent = isLearned ? "Gemarkeerd in jouw overzicht" : "Niet gemarkeerd";
    }
  };

  const updateLearned = () => {
    const learned = readList(learnedKey)
      .map((item) => String(item))
      .filter((item) => item !== "");

    const index = learned.indexOf(sign.code);
    const isLearned = index !== -1;

    updateLearnedUI(isLearned);

    const toggle = document.querySelector("[data-learn-toggle]");
    if (toggle) {
      toggle.addEventListener("click", () => {
        const updated = readList(learnedKey)
          .map((item) => String(item))
          .filter((item) => item !== "");
        const currentIndex = updated.indexOf(sign.code);
        if (currentIndex === -1) {
          updated.unshift(sign.code);
        } else {
          updated.splice(currentIndex, 1);
        }
        writeList(learnedKey, updated);
        updateLearnedUI(currentIndex === -1);
      });
    }
  };

  const renderRecent = (items) => {
    const container = document.querySelector("[data-recent-list]");
    const empty = document.querySelector("[data-recent-empty]");
    if (!container) {
      return;
    }

    container.innerHTML = "";

    if (!items.length) {
      if (empty) {
        container.appendChild(empty);
      }
      return;
    }

    if (empty) {
      empty.remove();
    }

    items.forEach((item) => {
      const link = document.createElement("a");
      link.className = "rvv-sign-card";
      link.href = item.url;

      const image = document.createElement("img");
      image.src = item.image;
      image.alt = "Verkeersbord " + item.code;
      link.appendChild(image);

      const metaBox = document.createElement("div");
      metaBox.className = "rvv-sign-card__meta";

      const code = document.createElement("span");
      code.className = "rvv-sign-card__code";
      code.textContent = item.code;
      metaBox.appendChild(code);

      const title = document.createElement("span");
      title.className = "rvv-sign-card__title";
      title.textContent = item.title;
      metaBox.appendChild(title);

      link.appendChild(metaBox);
      container.appendChild(link);
    });
  };

  const updateRecent = () => {
    if (!sign.code || !sign.url) {
      return;
    }

    const current = normalizeRecentItem(sign);
    if (!current) {
      return;
    }

    const recent = readList(recentKey)
      .map(normalizeRecentItem)
      .filter((item) => item && item.code !== current.code);

    recent.unshift(current);

    const trimmed = recent.slice(0, maxRecent);
    writeList(recentKey, trimmed);
    renderRecent(trimmed);
  };

  updateLearned();
  updateRecent();
})();
