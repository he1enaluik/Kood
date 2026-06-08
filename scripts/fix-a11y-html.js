const fs = require("fs");

const htmlFiles = fs.readdirSync(".").filter((f) => f.endsWith(".html"));

const skipLink = '  <a class="skip-link" href="#main-content">Liigu põhisisu juurde</a>\n';

for (const file of htmlFiles) {
  let html = fs.readFileSync(file, "utf8");
  let changed = false;

  if (!html.includes('class="skip-link"')) {
    html = html.replace("<body>", `<body>\n${skipLink}`);
    changed = true;
  }

  html = html.replace(/aria-label="Tarukoda avaleht"/g, 'aria-label="Avaleht"');

  html = html.replace(
    /\s*role="combobox"/g,
    ""
  );
  html = html.replace(
    /id="header-search"([^>]*?)\s*aria-controls="header-search-results"\s*aria-expanded="false"\s*aria-autocomplete="list"/g,
    'id="header-search"$1'
  );
  html = html.replace(
    /<div class="header__search-dropdown" id="header-search-results" role="listbox"[^>]*hidden><\/div>/g,
    '<div class="header__search-dropdown" id="header-search-results" role="region" aria-label="Otsingu tulemused" hidden></div>'
  );

  html = html.replace(
    /<a href="#" class="footer__social-link" aria-label="Facebook">/g,
    '<a href="https://www.facebook.com/" class="footer__social-link" aria-label="Facebook" rel="noopener noreferrer" target="_blank">'
  );
  html = html.replace(
    /<a href="#" class="footer__social-link" aria-label="Instagram">/g,
    '<a href="https://www.instagram.com/" class="footer__social-link" aria-label="Instagram" rel="noopener noreferrer" target="_blank">'
  );
  html = html.replace(
    /<a href="#" class="footer__social-link" aria-label="TikTok">/g,
    '<a href="https://www.tiktok.com/" class="footer__social-link" aria-label="TikTok" rel="noopener noreferrer" target="_blank">'
  );

  if (file === "tooted.html") {
    html = html.replace(
      /<li><a href="tooted\.html">Tooted<\/a><\/li>/g,
      '<li><a href="tooted.html" aria-current="page">Tooted</a></li>'
    );
  }

  if (file === "index.html" && !html.includes("<main")) {
    html = html.replace(
      /<section class="hero" id="main-content"/,
      '<main id="main-content">\n  <section class="hero"'
    );
    html = html.replace(/\n  <footer class="footer">/, "\n  </main>\n\n  <footer class=\"footer\">");
    changed = true;
  }

  fs.writeFileSync(file, html);
  console.log(changed ? `Updated ${file}` : `Patched ${file}`);
}
