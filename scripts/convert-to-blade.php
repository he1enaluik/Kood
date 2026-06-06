<?php

function convertHtmlToBlade(string $sourceFile, string $targetFile, string $title, string $viewName): void
{
    $html = file_get_contents($sourceFile);

    if (! preg_match('/<\/header>\s*(.*?)\s*<footer/s', $html, $matches)) {
        throw new RuntimeException("Could not extract content from {$sourceFile}");
    }

    $content = trim($matches[1]);

    // Extract trailing scripts before footer if present in content... scripts are before footer in full file
    $scripts = '';
    if (preg_match('/<script>.*?<\/script>\s*$/s', $content, $scriptMatch)) {
        $scripts = trim($scriptMatch[0]);
        $content = trim(substr($content, 0, -strlen($scripts)));
    }

    $replacements = [
        'href="index.html"' => 'href="{{ route(\'home\') }}"',
        'href="tooted.html"' => 'href="{{ route(\'products\') }}"',
        'href="kontakt.html"' => 'href="{{ route(\'contact\') }}"',
        'href="tellimus.html"' => 'href="{{ route(\'order\') }}"',
        'href="toote-detail.html"' => 'href="{{ route(\'product.show\') }}"',
        'href="#our-mission"' => 'href="{{ route(\'home\') }}#our-mission"',
        'src="Ikoonid/' => 'src="{{ asset(\'Ikoonid/',
        'src="pildid/' => 'src="{{ asset(\'pildid/',
        'src="Designi%20elemendid/' => 'src="{{ asset(\'Designi%20elemendid/',
        'src="Designi elemendid/' => 'src="{{ asset(\'Designi elemendid/',
    ];

    $content = str_replace(array_keys($replacements), array_values($replacements), $content);

    // Close asset() for src attributes
    $content = preg_replace('/src="\{\{ asset\(\'([^\']+)\'/','src="{{ asset(\'$1\'', $content);
    $content = preg_replace('/(src="\{\{ asset\([^)]+\))(?!\})/', '$1) }}"', $content);

    // Fix double closing - simpler approach: line by line fix for src
    $content = preg_replace_callback(
        '/src="\{\{ asset\(\'([^\']+)\'\) \}\}"/',
        fn ($m) => 'src="{{ asset(\''.$m[1].'\') }}"',
        $content
    );

    // Manual fix for unclosed asset tags
    $content = str_replace(".svg\"", ".svg') }}\"", $content);
    $content = str_replace(".png\"", ".png') }}\"", $content);
    $content = str_replace(".PM.png') }}') }}\"", ".PM.png') }}\"", $content);

    $blade = "@extends('layouts.app')\n\n";
    $blade .= "@section('title', '{$title}')\n\n";
    $blade .= "@section('content')\n";
    $blade .= $content."\n";
    $blade .= "@endsection\n";

    if ($scripts !== '') {
        $blade .= "\n@push('scripts')\n{$scripts}\n@endpush\n";
    }

    file_put_contents($targetFile, $blade);
    echo "Created {$targetFile}\n";
}

$base = dirname(__DIR__);

$pages = [
    ['index.html', 'pages/home.blade.php', 'Tarukoda'],
    ['tooted.html', 'pages/products.blade.php', 'Tooted | Tarukoda'],
    ['toote-detail.html', 'pages/product-show.blade.php', 'Niidumesi | Tarukoda'],
    ['kontakt.html', 'pages/contact.blade.php', 'Kontakt | Tarukoda'],
    ['tellimus.html', 'pages/order.blade.php', 'Tellimus | Tarukoda'],
];

foreach ($pages as [$source, $target, $title]) {
    convertHtmlToBlade(
        $base.DIRECTORY_SEPARATOR.$source,
        $base.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$target,
        $title,
        $target
    );
}
