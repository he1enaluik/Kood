# Tarukoda + Laravel

Laravel on integreeritud otse sellesse projekti (eraldi projekti ei loodud).

## Projekti struktuur

```
app/Http/Controllers/   → Laravel controllerid
resources/views/      → Blade mallid (HTML lehed)
routes/web.php        → URL marsruudid
public/               → Veebijuur (CSS, pildid, index.php)
```

## Marsruudid

| URL | Leht |
|-----|------|
| `/` | Avaleht |
| `/tooted` | Tooted |
| `/toode` | Toote detail |
| `/kontakt` | Kontakt |
| `/tellimus` | Ostukorv / tellimus |

## Käivitamine

**Vajalik:** PHP 8.2+ ja [Composer](https://getcomposer.org/)

1. Paigalda PHP (nt [Laragon](https://laragon.org/) või `winget install PHP.PHP`)

2. Projektikaustas:
   ```bash
   composer install
   copy .env.example .env
   php artisan key:generate
   php artisan serve
   ```

3. Ava brauseris: **http://localhost:8000**

## Märkused

- Staatilised `.html` failid juurkaustas on alles varuversioonina — Laravel kasutab `resources/views/` Blade faile.
- CSS ja pildid on `public/` kaustas (sh `style.css`, `pildid/`, `Ikoonid/`).
- Pärast CSS muudatusi uuenda ka `public/style.css` või kopeeri juurest: `copy style.css public\style.css`
