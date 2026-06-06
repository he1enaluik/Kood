$base = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)

function Convert-Page($source, $target, $title) {
  $html = Get-Content (Join-Path $base $source) -Raw -Encoding UTF8
  if ($html -match '(?s)</header>\s*(.*?)\s*<footer') { $content = $matches[1].Trim() } else { throw "fail $source" }
  $scripts = ''
  if ($content -match '(?s)(<script>.*?</script>)\s*$') {
    $scripts = $matches[1].Trim()
    $content = $content.Substring(0, $content.Length - $scripts.Length).Trim()
  }
  $content = $content.Replace('href="index.html"', 'href="{{ route(''home'') }}"')
  $content = $content.Replace('href="tooted.html"', 'href="{{ route(''products'') }}"')
  $content = $content.Replace('href="kontakt.html"', 'href="{{ route(''contact'') }}"')
  $content = $content.Replace('href="tellimus.html"', 'href="{{ route(''order'') }}"')
  $content = $content.Replace('href="toote-detail.html"', 'href="{{ route(''product.show'') }}"')
  $content = $content.Replace('href="#our-mission"', 'href="{{ route(''home'') }}#our-mission"')
  $content = $content.Replace('src="Ikoonid/', 'src="{{ asset(''Ikoonid/')
  $content = $content.Replace('src="pildid/', 'src="{{ asset(''pildid/')
  $content = $content.Replace('src="Designi%20elemendid/', 'src="{{ asset(''Designi%20elemendid/')
  $content = $content.Replace('.svg"', '.svg'') }}"')
  $content = $content.Replace('.png"', '.png'') }}"')
  $blade = "@extends('layouts.app')`r`n`r`n@section('title', '$title')`r`n`r`n@section('content')`r`n$content`r`n@endsection`r`n"
  if ($scripts) { $blade += "`r`n@push('scripts')`r`n$scripts`r`n@endpush`r`n" }
  $out = Join-Path $base $target
  [IO.File]::WriteAllText($out, $blade, [Text.UTF8Encoding]::new($false))
  Write-Output "OK $target"
}

Convert-Page 'index.html' 'resources\views\pages\home.blade.php' 'Tarukoda'
Convert-Page 'tooted.html' 'resources\views\pages\products.blade.php' 'Tooted | Tarukoda'
Convert-Page 'toote-detail.html' 'resources\views\pages\product-show.blade.php' 'Niidumesi | Tarukoda'
Convert-Page 'kontakt.html' 'resources\views\pages\contact.blade.php' 'Kontakt | Tarukoda'
Convert-Page 'tellimus.html' 'resources\views\pages\order.blade.php' 'Tellimus | Tarukoda'
