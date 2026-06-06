<?php

namespace App\Data;

class Products
{
    private const SHARED_GALLERY = [
        ['src' => 'pildid/ChatGPT Image Jun 4, 2026, 03_18_14 PM.png', 'alt' => 'Mesilane lillel'],
        ['src' => 'pildid/ChatGPT Image Jun 4, 2026, 07_20_29 PM.png', 'alt' => 'Mesindustalu mesitarudega'],
        ['src' => 'pildid/ChatGPT Image Jun 4, 2026, 07_32_18 PM.png', 'alt' => 'Meedegusteerimine meepurkidega'],
    ];

    public static function all(): array
    {
        return [
            'niidumesi' => self::niidumesi(),
            'metsamesi' => self::metsamesi(),
            'parnamesi' => self::parnamesi(),
            'kinkekarp' => self::kinkekarp(),
            'mesilasvaha-kuunal' => self::mesilasvahaKuunal(),
            'hooajaline-mesi' => self::hooajalineMesi(),
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

    private static function mesilasvahaKuunal(): array
    {
        $image = 'pildid/ChatGPT Image Jun 3, 2026, 04_50_10 PM.png';

        return [
            'slug' => 'mesilasvaha-kuunal',
            'name' => 'Mesilasvaha küünal',
            'price' => 12.50,
            'category' => 'kunlad',
            'origin_filter' => 'poltsamaa',
            'origin' => 'Põltsamaa',
            'weight' => '1 tk',
            'short_desc' => 'Käsitööna valmistatud looduslik vahaküünal mee aroomiga.',
            'description' => 'Mesilasvaha küünal on valmistatud puhtast mesilasvahast ja annab sooja, meeldiva mee aroomi. Küünal põleb ühtlaselt ja looduslikult, luues hubase atmosfääri igasse ruumi.',
            'image' => $image,
            'badge' => null,
            'gallery' => [
                ['src' => $image, 'alt' => 'Mesilasvaha küünal'],
                ['src' => 'pildid/ChatGPT Image Jun 4, 2026, 07_32_18 PM.png', 'alt' => 'Meedegusteerimine meepurkidega'],
            ],
        ];
    }

    private static function hooajalineMesi(): array
    {
        $image = 'pildid/ChatGPT Image Jun 3, 2026, 04_06_26 PM.png';

        return [
            'slug' => 'hooajaline-mesi',
            'name' => 'Hooajaline mesi',
            'price' => 9.90,
            'category' => 'hooaeg',
            'origin_filter' => 'jogevamaa',
            'origin' => 'Jõgevamaa',
            'weight' => '500 g',
            'short_desc' => 'Piiratud koguses erimaitsega mesi vastavalt hooajale.',
            'description' => 'Hooajaline mesi on piiratud koguses saadaval erimaitsega sort, mis peegeldab konkreetse korjehooaja lillede ja taimede mitmekesisust. Iga parti on ainulaadne.',
            'image' => $image,
            'badge' => null,
            'gallery' => array_merge(
                [['src' => $image, 'alt' => 'Hooajaline mesi purk']],
                self::SHARED_GALLERY
            ),
        ];
    }
}
