@extends('layouts.app')

@section('title', 'Autoriõigused | Tarukoda')

@section('content')
<main class="legal-page" aria-labelledby="legal-heading">
    <div class="container">
      <a href="{{ route('home') }}" class="legal-page__back">← Tagasi avalehele</a>

      <div class="legal-page__box">
        <h1 id="legal-heading" class="legal-page__heading">Autoriõigused ja kasutustingimused</h1>
        <p class="legal-page__intro">Siin lehel on kirjas Tarukoda veebilehe, brändi, toodete ja mesindusalase sisu õigused. Palun loe need tingimused enne meie sisu kasutamist või jagamist.</p>

        <section class="legal-page__section">
          <h2>Autoriõigused</h2>
          <p>Kõik selle veebilehe tekstid, fotod, illustratsioonid, tootepildid, logo, disainielemendid ja muu visuaalne sisu kuuluvad Tarukoda OÜ-le või on kasutusel litsentsi alusel. Sisu on kaitstud autoriõiguse seaduse ja rahvusvaheliste lepingutega.</p>
          <p>Ilma Tarukoda OÜ kirjaliku loata on keelatud:</p>
          <ul>
            <li>veebilehe sisu kopeerimine, levitamine või avaldamine;</li>
            <li>tootefotode ja -kirjelduste kasutamine teistes e-poodides või reklaamides;</li>
            <li>brändi, logo või mesindusega seotud materjalide muutmine ja edasiandmine.</li>
          </ul>
        </section>

        <section class="legal-page__section">
          <h2>Mesi ja toodete info</h2>
          <p>Meie mesi, mesilasvaha tooted ja kinkekomplektid on Tarukoda toodang. Tootekirjeldused, päritoluandmed, mahe sertifikaadi viited ja hinnad on informatiivsed ega anna õigust neid teiste ärinimede all müüa või esitleda.</p>
          <p>Mesi kvaliteet, maitse ja värvus võivad hooajaliselt erineda, kuid iga toote juures esitatud info peegeldab meie tegelikku tootmis- ja pakendamisprotsessi.</p>
        </section>

        <section class="legal-page__section">
          <h2>Kaubamärgid</h2>
          <p>Nimi <strong>Tarukoda</strong>, logo ja sellega seotud visuaalsed elemendid on ettevõtte tunnused. Nende kasutamine ilma loata on keelatud, sh kolmandate osapoolte toodete, sündmuste või veebilehtede juures.</p>
        </section>

        <section class="legal-page__section">
          <h2>Mahe sertifikaat</h2>
          <p>Tarukoda mahetooted vastavad kehtivale sertifitseerimise korrale. Sertifikaadi number <strong>EE-MAH-001</strong> viitab meie mahepõllumajanduslikule tegevusele. Sertifikaadi märke ei tohi kasutada teiste toodete või ettevõtete juures.</p>
        </section>

        <section class="legal-page__section">
          <h2>Vastutuse piirang</h2>
          <p>Püüame hoida veebilehe info ajakohane ja täpne, kuid ei vastuta võimalike tehniliste vigade, hinnamuutuste ega ajutise info puudumise eest. Tooted müüakse kehtiva hinnakirja ja laoseisu alusel.</p>
        </section>

        <section class="legal-page__section">
          <h2>Kontakt autoriõiguste küsimustes</h2>
          <p>Kui soovid kasutada meie sisu, pildistada talu või küsida luba brändi kasutamiseks, võta ühendust aadressil <a href="mailto:info@tarukoda.ee">info@tarukoda.ee</a> või <a href="{{ route('contact') }}">kontaktivormi</a> kaudu.</p>
        </section>
      </div>
    </div>
  </main>
@endsection
