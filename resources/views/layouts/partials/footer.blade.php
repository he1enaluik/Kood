<footer class="footer">
  <div class="container">
    <div class="footer__grid">
      <div class="footer__brand">
        <a href="{{ route('home') }}" class="footer__logo" aria-label="Tarukoda avaleht">
          <span class="footer__logo-icon" aria-hidden="true"></span>
          <span class="footer__logo-text">TARUKODA</span>
        </a>
        <p class="footer__desc">Pakume ehtsat mahemett Eesti puhtast loodusest, järgides jätkusuutliku mesinduse põhimõtteid ja hoolitsedes oma mesilasperede heaolu eest.</p>
        <div class="footer__social">
          <a href="#" class="footer__social-link" aria-label="Facebook">
            <img src="{{ asset('Ikoonid/facebook.svg') }}" alt="" width="16" height="16">
          </a>
          <a href="#" class="footer__social-link" aria-label="Instagram">
            <img src="{{ asset('Ikoonid/instagram.svg') }}" alt="" width="16" height="16">
          </a>
          <a href="#" class="footer__social-link" aria-label="TikTok">
            <img src="{{ asset('Ikoonid/tiktok.svg') }}" alt="" width="16" height="16">
          </a>
        </div>
      </div>

      <div class="footer__col">
        <h3 class="footer__heading">Lehed</h3>
        <ul class="footer__links">
          <li><a href="{{ route('home') }}">Avaleht</a></li>
          <li><a href="{{ route('products') }}">Tooted</a></li>
          <li><a href="{{ route('contact') }}">Kontakt</a></li>
          <li><a href="{{ route('home') }}#our-mission">Meist</a></li>
        </ul>
      </div>

      <div class="footer__col">
        <h3 class="footer__heading">Tooted</h3>
        <ul class="footer__links">
          <li><a href="{{ route('products') }}">Mesi</a></li>
          <li><a href="{{ route('products') }}">Mesilasvaha küünlad</a></li>
          <li><a href="{{ route('products') }}">Kinkekomplektid</a></li>
          <li><a href="{{ route('products') }}">Hooajatooted</a></li>
        </ul>
      </div>

      <div class="footer__col">
        <h3 class="footer__heading">Kontakt</h3>
        <ul class="footer__contact">
          <li>
            <span class="footer__contact-icon footer__contact-icon--location" aria-hidden="true"></span>
            Niidu tn 6, Põltsamaa
          </li>
          <li>
            <span class="footer__contact-icon footer__contact-icon--phone" aria-hidden="true"></span>
            +372 567 8901
          </li>
          <li>
            <span class="footer__contact-icon footer__contact-icon--mail" aria-hidden="true"></span>
            info@tarukoda.ee
          </li>
          <li>
            <span class="footer__contact-icon footer__contact-icon--clock" aria-hidden="true"></span>
            E-R 9:00-17:00
          </li>
        </ul>
      </div>
    </div>

    <div class="footer__divider" aria-hidden="true"></div>

    <div class="footer__bottom">
      <p>© 2026 Tarukoda OÜ • Reg. 12345678</p>
      <p>Kõik õigused kaitstud • Mahe sertifikaat EE-MAH-001</p>
    </div>
  </div>
</footer>
