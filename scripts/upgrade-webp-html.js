const fs = require("fs");
const path = require("path");

const file = process.argv[2];
if (!file) {
  console.error("Usage: node scripts/upgrade-webp-html.js <html-file>");
  process.exit(1);
}

let html = fs.readFileSync(file, "utf8");

html = html.replace(/<picture>\s*<source[^>]*>\s*/gi, "");
html = html.replace(/<\/picture>/gi, "");

let count = 0;

html = html.replace(/<img\b([^>]*?)\bsrc="([^"]+)"([^>]*)>/gi, (match, before, src, after) => {
  const decoded = decodeURIComponent(src);
  if (!/\.(png|jpe?g)(\?|$)/i.test(decoded)) {
    return match;
  }

  const webpSrc = src.replace(/\.(png|jpe?g)/i, ".webp");
  const webpPath = decodeURIComponent(webpSrc.split("?")[0]);
  const diskPath = path.join(path.dirname(file), webpPath);

  if (!fs.existsSync(diskPath)) {
    return match;
  }

  count += 1;
  return `<picture><source srcset="${webpSrc}" type="image/webp"><img${before}src="${src}"${after}></picture>`;
});

fs.writeFileSync(file, html);
console.log(`Updated ${count} images in ${file}`);
