const fs = require("fs");

const BASE = "https://tarukoda.surge.sh";
const OG_IMAGE = `${BASE}/pildid/hero-taust1.webp`;

const pages = [
  { file: "index.html", path: "/", title: "Tarukoda", description: "Tarukoda pakub ehtsat mahemett Eesti puhtast loodusest. Vaata mesi, mesilasvaha küünlad ja kinkekomplekte." },
  { file: "tooted.html", path: "/tooted.html", title: "Tooted | Tarukoda", description: "Vaata Tarukoja mahemett, mesilasvaha küünlad, kinkekomplekte ja hooajatooteid. Tellimine ja kohaletoimetamine üle Eesti." },
  { file: "kontakt.html", path: "/kontakt.html", title: "Kontakt | Tarukoda", description: "Võta Tarukodaga ühendust. Küsimused meie mahemee, tellimiste või koostöö kohta – vastame esimesel võimalusel." },
  { file: "tellimus.html", path: "/tellimus.html", title: "Tellimus | Tarukoda", description: "Esita Tarukoja toodete tellimus. Täida andmed ja saadame mahemee ja mesindustooted Sinu aadressile." },
  { file: "tellimus-onnestus.html", path: "/tellimus-onnestus.html", title: "Tellimus edukas | Tarukoda", description: "Tarukoja tellimus on vastu võetud. Täname ostu eest!" },
  { file: "login.html", path: "/login.html", title: "Logi sisse | Tarukoda", description: "Logi sisse Tarukoja kontole või registreeru uueks kasutajaks." },
  { file: "toote-detail.html", path: "/toote-detail.html", title: "Toode | Tarukoda", description: "Tarukoja toote detailid – mahe mesi ja mesindustooted Eesti loodusest." },
];

function seoBlock(page) {
  const url = `${BASE}${page.path}`;
  return `<meta name="robots" content="index, follow">
  <meta name="theme-color" content="#ffe6d0">
  <link rel="canonical" href="${url}">
  <meta property="og:title" content="${page.title}">
  <meta property="og:description" content="${page.description}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="${url}">
  <meta property="og:locale" content="et_EE">
  <meta property="og:image" content="${OG_IMAGE}">
  <meta name="twitter:card" content="summary_large_image">`;
}

const jsonLd = `<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Tarukoda OÜ",
    "url": "${BASE}/",
    "description": "Mahemesi ja mesindustooted Eesti puhtast loodusest.",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Niidu tn 6",
      "addressLocality": "Põltsamaa",
      "addressCountry": "EE"
    },
    "email": "info@tarukoda.ee"
  }
  </script>`;

for (const page of pages) {
  let html = fs.readFileSync(page.file, "utf8");

  html = html.replace(/\s*<meta name="theme-color"[^>]*>/gi, "");
  html = html.replace(/\s*<link rel="canonical"[^>]*>/gi, "");
  html = html.replace(/\s*<meta name="robots"[^>]*>/gi, "");
  html = html.replace(/\s*<meta property="og:[^"]+"[^>]*>/gi, "");
  html = html.replace(/\s*<meta name="twitter:card"[^>]*>/gi, "");
  html = html.replace(/\s*<script type="application\/ld\+json">[\s\S]*?<\/script>/gi, "");

  const insert = seoBlock(page) + (page.file === "index.html" ? `\n  ${jsonLd}` : "");

  if (html.includes("<title>")) {
    html = html.replace(/<title>[^<]*<\/title>/, (match) => `${match}\n  ${insert}`);
  }

  fs.writeFileSync(page.file, html);
  console.log("SEO injected:", page.file);
}
