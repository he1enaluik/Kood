(function () {
  const USERS_KEY = "tarukoda-users";
  const SESSION_KEY = "tarukoda-session";

  async function hashPassword(password) {
    const data = new TextEncoder().encode(password);
    const hash = await crypto.subtle.digest("SHA-256", data);

    return Array.from(new Uint8Array(hash))
      .map((byte) => byte.toString(16).padStart(2, "0"))
      .join("");
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

  async function seedDemoUser() {
    if (getUsers().length > 0) {
      return;
    }

    saveUsers([
      {
        name: "Demo Kasutaja",
        email: "demo@tarukoda.ee",
        passwordHash: await hashPassword("tarukoda123"),
      },
    ]);
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
    const loginTab = document.getElementById("auth-tab-login");
    const registerTab = document.getElementById("auth-tab-register");
    const redirectUrl = new URLSearchParams(window.location.search).get("redirect") || "index.html";

    function switchTab(tab) {
      const isLogin = tab === "login";
      loginForm?.classList.toggle("login-page__panel--active", isLogin);
      registerForm?.classList.toggle("login-page__panel--active", !isLogin);
      loginTab?.classList.toggle("login-page__tab--active", isLogin);
      registerTab?.classList.toggle("login-page__tab--active", !isLogin);
      showAuthMessage("");
    }

    loginTab?.addEventListener("click", () => switchTab("login"));
    registerTab?.addEventListener("click", () => switchTab("register"));

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

    await seedDemoUser();
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
