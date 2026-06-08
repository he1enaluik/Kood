const fs = require("fs");
const path = require("path");
const { execSync } = require("child_process");

const htmlFiles = fs
  .readdirSync(".")
  .filter((file) => file.endsWith(".html"));

for (const html of htmlFiles) {
  execSync(`node scripts/upgrade-webp-html.js ${html}`, { stdio: "inherit" });
}

console.log("WebP HTML upgrade complete for", htmlFiles.length, "pages.");
