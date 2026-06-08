(function () {
  const USERS_KEY = "tarukoda-users";
  const SESSION_KEY = "tarukoda-session";
  const DEFAULT_PASSWORD_HASHES = {
    tarukoda123: "db3bef59e23163bf0914f904e2766f4b4eba1d0dc1927035fc0f5913be061ee8",
  };

  function bytesToHex(bytes) {
    return Array.from(bytes)
      .map((byte) => byte.toString(16).padStart(2, "0"))
      .join("");
  }

  function sha256Pure(message) {
    const rotr = (n, x) => (x >>> n) | (x << (32 - n));
    const sigma0 = (x) => rotr(2, x) ^ rotr(13, x) ^ rotr(22, x);
    const sigma1 = (x) => rotr(6, x) ^ rotr(11, x) ^ rotr(25, x);
    const ch = (x, y, z) => (x & y) ^ (~x & z);
    const maj = (x, y, z) => (x & y) ^ (x & z) ^ (y & z);
    const sigma0small = (x) => rotr(7, x) ^ rotr(18, x) ^ (x >>> 3);
    const sigma1small = (x) => rotr(17, x) ^ rotr(19, x) ^ (x >>> 10);
    const K = [
      0x428a2f98, 0x71374491, 0xb5c0fbcf, 0xe9b5dba5, 0x3956c25b, 0x59f111f1, 0x923f82a4, 0xab1c5ed5,
      0xd807aa98, 0x12835b01, 0x243185be, 0x550c7dc3, 0x72be5d74, 0x80deb1fe, 0x9bdc06a7, 0xc19bf174,
      0xe49b69c1, 0xefbe4786, 0x0fc19dc6, 0x240ca1cc, 0x2de92c6f, 0x4a7484aa, 0x5cb0a9dc, 0x76f988da,
      0x983e5152, 0xa831c66d, 0xb00327c8, 0xbf597fc7, 0xc6e00bf3, 0xd5a79147, 0x06ca6351, 0x14292967,
      0x27b70a85, 0x2e1b2138, 0x4d2c6dfc, 0x53380d13, 0x650a7354, 0x766a0abb, 0x81c2c92e, 0x92722c85,
      0xa2bfe8a1, 0xa81a664b, 0xc24b8b70, 0xc76c51a3, 0xd192e819, 0xd6990624, 0xf40e3585, 0x106aa070,
      0x19a4c116, 0x1e376c08, 0x2748774c, 0x34b0bcb5, 0x391c0cb3, 0x4ed8aa4a, 0x5b9cca4f, 0x682e6ff3,
      0x748f82ee, 0x78a5636f, 0x84c87814, 0x8cc70208, 0x90befffa, 0xa4506ceb, 0xbef9a3f7, 0xc67178f2,
    ];

    let h0 = 0x6a09e667;
    let h1 = 0xbb67ae85;
    let h2 = 0x3c6ef372;
    let h3 = 0xa54ff53a;
    let h4 = 0x510e527f;
    let h5 = 0x9b05688c;
    let h6 = 0x1f83d9ab;
    let h7 = 0x5be0cd19;

    const bitLen = message.length * 8;
    const padLen = (64 + 56 - ((message.length + 1) % 64)) % 64;
    const totalLen = message.length + 1 + padLen + 8;
    const padded = new Uint8Array(totalLen);
    padded.set(message);
    padded[message.length] = 0x80;

    const view = new DataView(padded.buffer);
    view.setUint32(totalLen - 8, Math.floor(bitLen / 0x100000000), false);
    view.setUint32(totalLen - 4, bitLen >>> 0, false);

    const w = new Uint32Array(64);

    for (let offset = 0; offset < totalLen; offset += 64) {
      for (let i = 0; i < 16; i += 1) {
        w[i] = view.getUint32(offset + i * 4, false);
      }

      for (let i = 16; i < 64; i += 1) {
        w[i] = (sigma1small(w[i - 2]) + w[i - 7] + sigma0small(w[i - 15]) + w[i - 16]) >>> 0;
      }

      let a = h0;
      let b = h1;
      let c = h2;
      let d = h3;
      let e = h4;
      let f = h5;
      let g = h6;
      let h = h7;

      for (let i = 0; i < 64; i += 1) {
        const t1 = (h + sigma1(e) + ch(e, f, g) + K[i] + w[i]) >>> 0;
        const t2 = (sigma0(a) + maj(a, b, c)) >>> 0;
        h = g;
        g = f;
        f = e;
        e = (d + t1) >>> 0;
        d = c;
        c = b;
        b = a;
        a = (t1 + t2) >>> 0;
      }

      h0 = (h0 + a) >>> 0;
      h1 = (h1 + b) >>> 0;
      h2 = (h2 + c) >>> 0;
      h3 = (h3 + d) >>> 0;
      h4 = (h4 + e) >>> 0;
      h5 = (h5 + f) >>> 0;
      h6 = (h6 + g) >>> 0;
      h7 = (h7 + h) >>> 0;
    }

    return [h0, h1, h2, h3, h4, h5, h6, h7].map((value) => value.toString(16).padStart(8, "0")).join("");
  }

  async function hashPassword(password) {
    const data = new TextEncoder().encode(password);

    if (window.isSecureContext && globalThis.crypto?.subtle?.digest) {
      try {
        const hash = await crypto.subtle.digest("SHA-256", data);
        return bytesToHex(new Uint8Array(hash));
      } catch {
        // Fall back when SubtleCrypto is unavailable (e.g. mixed/insecure context).
      }
    }

    return sha256Pure(data);
  }

  function getUsers() {
    try {
      const raw = localStorage.getItem(USERS_KEY);
      const parsed = raw ? JSON.parse(raw) : [];
      return Array.isArray(parsed) ? parsed : [];
    } catch {
      return [];
    }
  }

  function saveUsers(users) {
    localStorage.setItem(USERS_KEY, JSON.stringify(users));
  }

  async function seedDefaultUsers() {
    const users = getUsers();
    const demoEmail = "demo@tarukoda.ee";

    if (users.some((user) => user.email === demoEmail)) {
      refreshSessionFromUsers(users);
      return;
    }

    const updated = [
      ...users,
      {
        name: "Demo Kasutaja",
        email: demoEmail,
        passwordHash: DEFAULT_PASSWORD_HASHES.tarukoda123,
      },
    ];

    saveUsers(updated);
    refreshSessionFromUsers(updated);
  }

  function refreshSessionFromUsers(users) {
    const session = getSession();
    if (!session) {
      return;
    }

    const user = users.find((entry) => entry.email === session.email);
    if (!user) {
      return;
    }

    const nextSession = {
      ...session,
      name: user.name,
    };

    if (JSON.stringify(nextSession) !== JSON.stringify(session)) {
      localStorage.setItem(SESSION_KEY, JSON.stringify(nextSession));
    }
  }

  function getSession() {
    try {
      const raw = localStorage.getItem(SESSION_KEY);
      return raw ? JSON.parse(raw) : null;
    } catch {
      return null;
    }
  }

  function setSession(user) {
    localStorage.setItem(
      SESSION_KEY,
      JSON.stringify({
        email: user.email,
        name: user.name,
        loggedInAt: new Date().toISOString(),
      })
    );
    updateHeader();
    window.dispatchEvent(new CustomEvent("tarukoda-auth-changed"));
  }

  function clearSession() {
    localStorage.removeItem(SESSION_KEY);
    updateHeader();
    window.dispatchEvent(new CustomEvent("tarukoda-auth-changed"));
  }

  function getCurrentUser() {
    const session = getSession();
    if (!session) {
      return null;
    }

    return getUsers().find((user) => user.email === session.email) || null;
  }

  function isLoggedIn() {
    return Boolean(getCurrentUser());
  }

  async function register(name, email, password) {
    const trimmedName = name.trim();
    const trimmedEmail = email.trim().toLowerCase();

    if (trimmedName.length < 2) {
      throw new Error("Nimi peab olema vähemalt 2 tähemärki.");
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(trimmedEmail)) {
      throw new Error("Sisesta kehtiv e-posti aadress.");
    }

    if (password.length < 6) {
      throw new Error("Parool peab olema vähemalt 6 tähemärki.");
    }

    const users = getUsers();

    if (users.some((user) => user.email === trimmedEmail)) {
      throw new Error("Selle e-posti aadressiga konto on juba olemas.");
    }

    const newUser = {
      name: trimmedName,
      email: trimmedEmail,
      passwordHash: await hashPassword(password),
    };

    saveUsers([...users, newUser]);
    setSession(newUser);

    return newUser;
  }

  async function login(email, password) {
    const trimmedEmail = email.trim().toLowerCase();
    const user = getUsers().find((entry) => entry.email === trimmedEmail);

    if (!user) {
      throw new Error("Vale e-post või parool.");
    }

    const passwordHash = await hashPassword(password);

    if (user.passwordHash !== passwordHash) {
      throw new Error("Vale e-post või parool.");
    }

    setSession(user);
    return user;
  }

  function logout() {
    clearSession();
  }

  function updateHeader() {
    const profileWrap = document.querySelector(".header__profile");
    const profileLink = document.getElementById("header-profile-link");
    const profileMenu = document.getElementById("header-profile-menu");
    const profileName = document.getElementById("header-profile-name");
    const session = getSession();
    const loginUrl = profileWrap?.dataset.loginUrl || "login.html";

    if (!profileLink || !profileMenu || !profileName) {
      return;
    }

    if (session) {
      profileName.textContent = session.name;
      profileLink.href = "#";
      profileLink.setAttribute("aria-label", `Profiil: ${session.name}`);
      profileLink.dataset.loggedIn = "true";
    } else {
      profileName.textContent = "";
      profileLink.href = loginUrl;
      profileLink.setAttribute("aria-label", "Logi sisse");
      delete profileLink.dataset.loggedIn;
      profileMenu.hidden = true;
    }
  }

  function initProfileMenu() {
    const profileLink = document.getElementById("header-profile-link");
    const profileMenu = document.getElementById("header-profile-menu");
    const logoutBtn = document.getElementById("header-profile-logout");
    const profileWrap = document.querySelector(".header__profile");

    profileLink?.addEventListener("click", (event) => {
      const loggedIn = isLoggedIn() || profileLink.dataset.loggedIn === "true";

      if (!loggedIn || !profileMenu) {
        return;
      }

      event.preventDefault();
      profileMenu.hidden = !profileMenu.hidden;
    });

    logoutBtn?.addEventListener("click", () => {
      if (logoutBtn.type === "submit") {
        return;
      }

      logout();
      profileMenu.hidden = true;

      if (window.location.pathname.includes("login")) {
        return;
      }

      const homeUrl = profileWrap?.dataset.homeUrl || "index.html";
      window.location.href = homeUrl;
    });

    document.addEventListener("click", (event) => {
      if (!event.target.closest(".header__profile")) {
        if (profileMenu) {
          profileMenu.hidden = true;
        }
      }
    });
  }

  function showAuthMessage(message, isError = false) {
    const messageEl = document.getElementById("auth-message");
    if (!messageEl) {
      return;
    }

    if (!message) {
      messageEl.hidden = true;
      messageEl.textContent = "";
      messageEl.classList.remove("login-page__message--error");
      return;
    }

    messageEl.textContent = message;
    messageEl.hidden = false;
    messageEl.classList.toggle("login-page__message--error", isError);
  }

  function initLoginPage() {
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");
    const loginHeading = document.getElementById("login-heading");
    const switchRegister = document.getElementById("auth-switch-register");
    const switchLogin = document.getElementById("auth-switch-login");
    const redirectUrl = new URLSearchParams(window.location.search).get("redirect") || "index.html";

    function switchTab(tab) {
      const isLogin = tab === "login";
      loginForm?.classList.toggle("login-page__panel--active", isLogin);
      registerForm?.classList.toggle("login-page__panel--active", !isLogin);
      if (loginHeading) {
        loginHeading.textContent = isLogin ? "Logi sisse" : "Registreeru";
      }
      showAuthMessage("");
    }

    switchRegister?.addEventListener("click", () => switchTab("register"));
    switchLogin?.addEventListener("click", () => switchTab("login"));

    loginForm?.addEventListener("submit", async (event) => {
      event.preventDefault();

      const formData = new FormData(loginForm);

      try {
        await login(formData.get("email"), formData.get("password"));
        window.location.href = redirectUrl;
      } catch (error) {
        showAuthMessage(error.message, true);
      }
    });

    registerForm?.addEventListener("submit", async (event) => {
      event.preventDefault();

      const formData = new FormData(registerForm);
      const password = formData.get("password");
      const passwordConfirm = formData.get("password_confirm");

      if (password !== passwordConfirm) {
        showAuthMessage("Paroolid ei kattu.", true);
        return;
      }

      try {
        await register(formData.get("name"), formData.get("email"), password);
        window.location.href = redirectUrl;
      } catch (error) {
        showAuthMessage(error.message, true);
      }
    });

    if (loginForm && isLoggedIn()) {
      window.location.href = redirectUrl;
    }
  }

  document.addEventListener("DOMContentLoaded", async () => {
    if (document.body.dataset.authBackend === "laravel") {
      initProfileMenu();
      return;
    }

    await seedDefaultUsers();
    updateHeader();
    initProfileMenu();

    if (document.getElementById("login-form")) {
      initLoginPage();
    }
  });

  window.TarukodaAuth = {
    register,
    login,
    logout,
    isLoggedIn,
    getCurrentUser,
    updateHeader,
  };
})();
