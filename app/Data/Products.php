<?php

namespace App\Data;

class Products
{
    private const SHARED_GALLERY = [
        ['src' => 'pildid/ChatGPT Image Jun 4, 2026, 03_18_14 PM.png', 'alt' => 'Mesilane lillel'],
        ['src' => 'pildid/ChatGPT Image Jun 4, 2026, 07_20_29 PM.png', 'alt' => 'Mesindustalu mesitarudega'],
        ['src' => 'pildid/ChatGPT Image Jun 4, 2026, 07_32_18 PM.png', 'alt' => 'Mee degusteerimine meepurkidega'],
    ];

    public static function all(): array
    {
        return [
            'niidumesi' => self::niidumesi(),
            'metsamesi' => self::metsamesi(),
            'parnamesi' => self::parnamesi(),
            'kinkekarp' => self::kinkekarp(),
            'kevademesi' => self::kevademesi(),
            'suvemesi' => self::suvemesi(),
            'sugisemesi' => self::sugisemesi(),
            'talvemesi' => self::talvemesi(),
            'tarukujuline-kuunal' => self::tarukujulineKuunal(),
            'ribikuunal' => self::ribikuunal(),
            'mesilastarukuanal' => self::mesilastarukuanal(),
            'lillkuunal' => self::lillkuunal(),
        ];
    }

    public static function find(string $slug): ?array
    {
        return self::all()[$slug] ?? null;
    }

    public static function assetPath(string $path): string
    {
        if ($path === '') {
            return '';
        }

        $parts = explode('/', $path);

        return implode('/', array_map('rawurlencode', $parts));
    }

    public static function formatPrice(float $price): string
    {
        return number_format($price, 2, ',', ' ') . ' €';
    }

    private static function niidumesi(): array
    {
        $image = 'pildid/ChatGPT Image Jun 3, 2026, 04_06_26 PM.png';

        return [
            'slug' => 'niidumesi',
            'name' => 'Niidumesi',
            'price' => 8.90,
            'category' => 'mesi',
            'origin_filter' => 'poltsamaa',
            'origin' => 'Põltsamaa, Jõgevamaa',
            'weight' => '500 g',
            'short_desc' => 'Õrn ja lilleline maitse, mis peegeldab Eesti suviseid niite.',
            'description' => 'Meie niidumesi on kogutud puhtatest mahepõldudest, kus mesilased saavad vabalt lendata lillede vahel. Mesi on kreemjas, kergelt magus ja sobib suurepäraselt tee, jogurti või värskete saiade kõrvale.',
            'image' => $image,
            'badge' => 'UUS',
            'gallery' => array_merge(
                [['src' => $image, 'alt' => 'Niidumesi purk']],
                self::SHARED_GALLERY
            ),
        ];
    }

    private static function metsamesi(): array
    {
        $image = 'pildid/ChatGPT Image Jun 3, 2026, 04_50_10 PM.png';

        return [
            'slug' => 'metsamesi',
            'name' => 'Metsamesi',
            'price' => 8.90,
            'category' => 'mesi',
            'origin_filter' => 'jogevamaa',
            'origin' => 'Jõgevamaa',
            'weight' => '500 g',
            'short_desc' => 'Tugevama iseloomuga mesi metstaimede nektarist.',
            'description' => 'Metsamesi on kogutud Eesti metsade rikkalikust taimestikust. Selle sügavam ja mahedam maitse tuleb pärna-, kase- ja teiste metstaimede nektarist. Sobib suurepäraselt leivale, juustule ja soojadele jookidele.',
            'image' => $image,
            'badge' => 'UUS',
            'gallery' => array_merge(
                [['src' => $image, 'alt' => 'Metsamesi purk']],
                self::SHARED_GALLERY
            ),
        ];
    }

    private static function parnamesi(): array
    {
        $image = 'pildid/ChatGPT Image Jun 3, 2026, 04_55_14 PM.png';

        return [
            'slug' => 'parnamesi',
            'name' => 'Pärnamesi',
            'price' => 8.90,
            'category' => 'mesi',
            'origin_filter' => 'laane',
            'origin' => 'Lääne-Eesti',
            'weight' => '500 g',
            'short_desc' => 'Sametine tekstuur ning meeldivalt aromaatne järelmaitse.',
            'description' => 'Pärnamesi on tuntud oma pehme tekstuuri ja õrna pärnaõite aroomi poolest. See mesi kristalliseerub aeglaselt ja jääb pikaks ajaks pehme. Ideaalne magustoitude ja hommikusöögivalikute kõrvale.',
            'image' => $image,
            'badge' => 'UUS',
            'gallery' => array_merge(
                [['src' => $image, 'alt' => 'Pärnamesi purk']],
                self::SHARED_GALLERY
            ),
        ];
    }

    private static function kinkekarp(): array
    {
        $image = 'pildid/ChatGPT Image Jun 3, 2026, 05_04_47 PM.png';

        return [
            'slug' => 'kinkekarp',
            'name' => 'Kinkekarp',
            'price' => 25.70,
            'category' => 'kinke',
            'origin_filter' => 'poltsamaa',
            'origin' => 'Põltsamaa',
            'weight' => '1 komplekt',
            'short_desc' => 'Kolm hoolikalt valitud meeliiki kaunis kinkepakendis.',
            'description' => 'Kinkekarp sisaldab kolme erinevat Tarukoja meeliiki – niidu-, metsa- ja pärnamesi. Kaunis pakend teeb sellest ideaalse kingituse mesisõpradele ja pereliikmetele.',
            'image' => $image,
            'badge' => 'UUS',
            'gallery' => [
                ['src' => $image, 'alt' => 'Kinkekarp'],
                ['src' => 'pildid/ChatGPT Image Jun 3, 2026, 04_06_26 PM.png', 'alt' => 'Niidumesi purk'],
                ['src' => 'pildid/ChatGPT Image Jun 3, 2026, 04_50_10 PM.png', 'alt' => 'Metsamesi purk'],
            ],
        ];
    }

    private static function kevademesi(): array
    {
        return [
            'slug' => 'kevademesi',
            'name' => 'Kevademesi',
            'price' => 8.90,
            'category' => 'hooaeg',
            'origin_filter' => 'poltsamaa',
            'origin' => 'Põltsamaa, Jõgevamaa',
            'weight' => '500 g',
            'short_desc' => 'Kerge ja õrn kevadine mesi varakevadiste õitelillede nektarist.',
            'description' => 'Kevademesi on korjatud esimesest kevadisest õitsvusest, kui mesilased külastavad soldi, võililli ja teisi varakevadiseid taimi. Mesi on hele, õrna maitsega ja kreemja tekstuuriga. Pakendis 500 g. Sobib suurepäraselt hommikusöögiks, teele ja jogurtile.',
            'image' => null,
            'badge' => null,
            'gallery' => [],
        ];
    }

    private static function suvemesi(): array
    {
        return [
            'slug' => 'suvemesi',
            'name' => 'Suvemesi',
            'price' => 8.90,
            'category' => 'hooaeg',
            'origin_filter' => 'poltsamaa',
            'origin' => 'Põltsamaa, Jõgevamaa',
            'weight' => '500 g',
            'short_desc' => 'Lilleline ja mitmekesine suvine mesi Eesti põldudelt ja niitudelt.',
            'description' => 'Suvemesi peegeldab rikkalikku suveõitsengut – mesilased koguvad nektarit rohumaadelt, põldudelt ja aiataimudelt. Mesi on kuldpruun, aromaatne ja magus. Pakendis 500 g. Ideaalne tee, smuutide ja magustoitude jaoks.',
            'image' => null,
            'badge' => null,
            'gallery' => [],
        ];
    }

    private static function sugisemesi(): array
    {
        return [
            'slug' => 'sugisemesi',
            'name' => 'Sügisemesi',
            'price' => 8.90,
            'category' => 'hooaeg',
            'origin_filter' => 'jogevamaa',
            'origin' => 'Jõgevamaa',
            'weight' => '500 g',
            'short_desc' => 'Täismoka sügisene mesi hilisema hooaja õite ja metstaimede nektarist.',
            'description' => 'Sügisemesi on kogutud hilisuvisest ja varasügise õitsvusest. Tumedamat tooni ja sügavamat maitset annavad võililled, raudrohi ja teised sügishooaja taimed. Pakendis 500 g. Sobib leivale, juustule ja soojadele jookidele.',
            'image' => null,
            'badge' => null,
            'gallery' => [],
        ];
    }

    private static function talvemesi(): array
    {
        return [
            'slug' => 'talvemesi',
            'name' => 'Talvemesi',
            'price' => 8.90,
            'category' => 'hooaeg',
            'origin_filter' => 'poltsamaa',
            'origin' => 'Põltsamaa',
            'weight' => '500 g',
            'short_desc' => 'Tugevama iseloomuga mesi, mis sobib hästi talveperioodiks.',
            'description' => 'Talvemesi on hoolikalt valitud ja küpsenud mesi, mis säilitab oma loomuliku maitse ja aroomi kauem. Tumedam toon ja pehme tekstuur teevad sellest suurepärase valiku külmemate kuude jaoks. Pakendis 500 g. Kasuta teedes, küpsetistes või lihtsalt lusikatäie kaupa.',
            'image' => null,
            'badge' => null,
            'gallery' => [],
        ];
    }

    private static function tarukujulineKuunal(): array
    {
        $image = 'pildid/tarukujulineküünal.JPG';

        return [
            'slug' => 'tarukujuline-kuunal',
            'name' => 'Tarukujuline küünal',
            'price' => 12.50,
            'category' => 'kunlad',
            'origin_filter' => 'poltsamaa',
            'origin' => 'Põltsamaa',
            'weight' => 'ca 175 g',
            'short_desc' => 'Käsitöö tarukujuline mesilasvaha küünal sooja mee aroomiga.',
            'description' => 'Tarukujuline mesilasvaha küünal on valmistatud käsitööna puhtast mesilasvahast. Selle pehme kuma ja meeldiv meearoom loovad hubase õhkkonna igasse ruumi. Küünal põleb ühtlaselt ja looduslikult.',
            'image' => $image,
            'badge' => null,
            'gallery' => [
                ['src' => $image, 'alt' => 'Tarukujuline küünal'],
                ['src' => 'pildid/tarukujulineüleval.png', 'alt' => 'Tarukujuline küünal ülevalt vaadates'],
            ],
        ];
    }

    private static function ribikuunal(): array
    {
        $image = 'pildid/ribiküünal.png';

        return [
            'slug' => 'ribikuunal',
            'name' => 'Ribiküünal',
            'price' => 12.50,
            'category' => 'kunlad',
            'origin_filter' => 'poltsamaa',
            'origin' => 'Põltsamaa',
            'weight' => 'ca 210 g',
            'short_desc' => 'Ribikujuline mesilasvaha küünal käsitööna valmistatud.',
            'description' => 'Ribiküünal on unikaalse kujuga mesilasvaha küünal, mis annab sooja valguse ja õrna mee aroomi. Sobib suurepäraselt kingituseks või koduseks hubaseks.',
            'image' => $image,
            'badge' => null,
            'gallery' => [
                ['src' => $image, 'alt' => 'Ribiküünal'],
                ['src' => 'pildid/ribiküünalüleval.png', 'alt' => 'Ribiküünal ülevalt vaadates'],
            ],
        ];
    }

    private static function mesilastarukuanal(): array
    {
        $image = 'pildid/mesilastaruküünal.JPG';

        return [
            'slug' => 'mesilastarukuanal',
            'name' => 'Mesilastaruküünal',
            'price' => 12.50,
            'category' => 'kunlad',
            'origin_filter' => 'poltsamaa',
            'origin' => 'Põltsamaa',
            'weight' => 'ca 160 g',
            'short_desc' => 'Mesilastaruga kaunistatud mesilasvaha küünal.',
            'description' => 'Mesilastaruküünal ühendab loodusliku mesilasvaha ja armsa mesilastaruga kujunduse. Küünal põleb pikalt ja ühtlaselt, täites ruumi sooja valguse ja mee aroomiga.',
            'image' => $image,
            'badge' => null,
            'gallery' => [
                ['src' => $image, 'alt' => 'Mesilastaruküünal'],
                ['src' => 'pildid/mesilastaruküünalüleval.png', 'alt' => 'Mesilastaruküünal ülevalt vaadates'],
            ],
        ];
    }

    private static function lillkuunal(): array
    {
        $image = 'pildid/lilleest.png';

        return [
            'slug' => 'lillkuunal',
            'name' => 'Lillküünal',
            'price' => 12.50,
            'category' => 'kunlad',
            'origin_filter' => 'poltsamaa',
            'origin' => 'Põltsamaa',
            'weight' => 'ca 180 g',
            'short_desc' => 'Lillekujuline mesilasvaha küünal käsitööna valmistatud.',
            'description' => 'Lillküünal on õrna lillekujuga mesilasvaha küünal, mis annab sooja valguse ja meeldiva mee aroomi. Unikaalne kujundus teeb sellest armsa kingituse või kauni koduse detaili.',
            'image' => $image,
            'badge' => null,
            'gallery' => [
                ['src' => $image, 'alt' => 'Lillküünal'],
                ['src' => 'pildid/lillüleval.png', 'alt' => 'Lillküünal ülevalt vaadates'],
            ],
        ];
    }
}
