Uus tellimus - Tarukoda

KLIENDI ANDMED
---------------
Nimi: {{ $data['firstname'] }} {{ $data['lastname'] }}
E-post: {{ $data['email'] }}
Telefon: {{ $data['phone'] }}
Aadress: {{ $data['address'] }}, {{ $data['postcode'] }} {{ $data['city'] }}
@if(!empty($data['notes']))
Märkused: {{ $data['notes'] }}
@endif

TOOTED
------
@foreach($data['cart'] as $item)
- {{ $item['name'] }} × {{ $item['quantity'] }} = {{ number_format($item['lineTotal'], 2, ',', ' ') }} €
@endforeach

Vahesumma: {{ number_format($data['subtotal'], 2, ',', ' ') }} €
Tarne: {{ number_format($data['shipping'], 2, ',', ' ') }} €
Kokku: {{ number_format($data['total'], 2, ',', ' ') }} €

---
Saadetud: {{ now()->format('d.m.Y H:i') }}
