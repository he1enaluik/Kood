# Tarukoda

Tarukoja mesindustalu veebileht — toodete tutvustus, tellimine, kontaktivorm ja administraatori haldus.

---

## Vajalik tarkvara

| Tarkvara | Versioon | Otstarve |
|----------|----------|----------|
| [PHP](https://www.php.net/downloads) | 8.2+ | Back-end, legacy API, arendusserver |
| PHP laiendid | — | `mbstring`, `pdo_sqlite`, `sqlite3`, `gd`, `intl`, `zip`, `openssl`, `curl` |
| [Composer](https://getcomposer.org/) | 2.x | Laravel sõltuvused |
| [Node.js](https://nodejs.org/) | 18+ (LTS) | Build-skriptid, Surge deploy (valikuline) |
| [Git](https://git-scm.com/) | uusim | Repositooriumi kloonimine |
| [Docker Desktop](https://www.docker.com/products/docker-desktop/) | uusim | Valikuline — konteinerites käivitamine |

**Valikuline (tootmine / integratsioonid):**
- Stripe konto — testvõtmed maksete jaoks
- SMTP seadistus — e-kirjade saatmiseks (või `MAIL_MAILER=log` arenduses)

---

## Paigaldusjuhend

### 1. Repositooriumi allalaadimine

```bash
git clone <repo-url>
cd MM23Eksam
```

### 2. PHP sõltuvused (Laravel back-end)

```bash
composer install
```

### 3. Keskkonnamuutujad

```bash
cp .env.example .env
php artisan key:generate
```

Täida `.env` failis vajadusel:
- `STRIPE_KEY` ja `STRIPE_SECRET` — Stripe testvõtmed
- `JWT_SECRET` — juhuslik salajane string (min 32 tähemärki)
- `MAIL_*` — e-posti seadistus (arenduses võib jätta `MAIL_MAILER=log`)

### 4. Andmebaas

```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed
```

### 5. Legacy API (valikuline — statiline leht + PHP vormid)

Kui kasutad `api/contact.php` ja `api/order.php` otse (mitte Laraveli marsruute):

```bash
cp api/mail-config.example.php api/mail-config.php
cp api/stripe-config.example.php api/stripe-config.php
```

Täida loodud failides e-posti ja Stripe andmed.

### 6. Node.js skriptid (valikuline)

```bash
npm install
```

Kasutatakse piltide WebP konverteerimiseks, SEO injectimiseks ja Surge deploy'ks.

### 7. Projekt käima

**Lihtne arendus (statiline leht + legacy API):**

```bash
php -S 127.0.0.1:8080
```

Ava brauseris `http://localhost:8080/`.

**Laravel back-end eraldi (API, admin, Blade):**

```bash
php artisan serve
```

Vaikimisi: `http://localhost:8000`

**Docker (Laravel + Nginx + Mailpit):**

```bash
docker compose up --build
```

- Veeb: `http://localhost:8080`
- Mailpit (e-kirjad): `http://localhost:8025`

### Kontaktivorm ja Stripe (kohalikult)

Topeltklõps **`serve.bat`** või terminalis:

```bash
php -S 127.0.0.1:8080
```

Ava brauseris:
- Kontakt: `http://127.0.0.1:8080/kontakt.html`
- Tellimus + Stripe: `http://127.0.0.1:8080/tellimus.html`

Stripe võtmed on failides `api/stripe-config.php` ja `js/site-config.local.js` (mõlemad gitignore'is).

Kontakti e-kirjad saadetakse **Web3Forms** kaudu otse postkasti **helena.luik2007@gmail.com**.

1. Mine [web3forms.com](https://web3forms.com) → sisesta `helena.luik2007@gmail.com` → kopeeri **Access Key**
2. Kleebi võti faili `js/site-config.local.js` → `web3formsKey`
3. Värskenda leht (**Ctrl+F5**) ja testi uuesti

> Web3Forms ei luba tasuta paketis serveripoolset PHP päringut — e-kiri saadetakse brauserist.

Kohalikult salvestatakse sõnumid kausta `api/inbox/` isegi kui e-post ei lähe välja.

**Surge / Live Server** — vt allpool (vajab Web3Forms võtit).
```bash
copy js\site-config.local.js.example js\site-config.local.js
```
1. Registreeru [web3forms.com](https://web3forms.com) → kopeeri **Access Key** → `web3formsKey`
2. Stripe Dashboard → **publishable key** (`pk_test_...`) → `publishableKey`
3. Stripe **secret key** → `api/stripe-config.php` (kohalik PHP server maksete jaoks)

Live Server (port 5500) puhul käivita lisaks: `php -S 127.0.0.1:8080` (Stripe API jaoks).

---

```bash
npm install
npm run deploy
```

Leht avalikustatakse aadressil **https://tarukoda.surge.sh** (`CNAME` fail).

Eel-deploy käivitab SEO injectimise ja WebP piltide kontrolli. Back-end (Laravel, `api/`) Surge'ile ei lähe — `.surgeignore` jätab serveripoole failid välja.

---

## Käivitamine (lühidalt)

```bash
php -S 127.0.0.1:8080
```

Ava brauseris `http://localhost:8080/`.

---

## Vali keel/raamistik ja põhjenda (back-end)

Tarukoja serveripoole jaoks valiti **PHP 8.2+** koos **Laravel 11** raamistikuga. PHP on laialt levinud veebimajutuses ja sobib hästi vormide, e-kirjade ja maksete töötlemiseks. Laravel pakub valmis lahendusi marsruutimiseks, valideerimiseks, autentimiseks, migratsioonideks ja testimiseks — see kiirendab turvalise API ja admin-funktsionaalsuse loomist ilma, et peaks alustama nullist.

**Eelised:**
- Laraveli ökosüsteem: Eloquent ORM, Form Request valideerimine, middleware, PHPUnit testid;
- hea dokumentatsioon ja suur kogukond;
- lihtne integreerida Stripe, JWT, SQLite ja Docker;
- sama keelt (PHP) saab kasutada nii legacy `api/` skriptide kui Laraveli marsruutide jaoks;
- sobib väikeettevõtte skaaliga — ei nõua eraldi mikroteenuste taristut.

**Miinused:**
- Laravel on lihtsamate CRUD-ülesannete jaoks üle mõõdetud — väikse projekti puhul on osa loogikast ka otse PHP skriptides;
- PHP built-in server (`php -S`) sobib ainult arenduseks, tootmises on vaja Nginx/Apache + PHP-FPM;
- Laraveli õppimiskõver on suurem kui puhas PHP — projekti alguses kulub aega konfiguratsioonile (`.env`, migratsioonid, middleware).

**Alternatiivid:** Node.js (Express/NestJS) tooks kaasa teise keele ja tihedama sidumise front-endiga; puhas PHP ilma raamistikuta looks vähem boilerplate'i, kuid nõuaks rohkem käsitsi turvalisuse, valideerimise ja testide implementeerimist. Laravel pakub Tarukoja tellimuste, autentimise ja admin-API jaoks parimat tasakaalu turvalisuse ja arenduskiiruse vahel.

---

## Tehnoloogiad (back-end)

| Komponent | Valik |
|-----------|--------|
| Keel | PHP 8.2+ |
| Raamistik | Laravel 11 |
| Andmebaas | SQLite |
| Autentimine | JWT, Laravel Auth |
| Makse | Stripe |
| Deploy | Docker |
