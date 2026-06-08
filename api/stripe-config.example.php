<?php

/**
 * Stripe testkeskkonna seadistus staatilisele HTML + PHP jaoks.
 *
 * 1. Kopeeri see fail nimega stripe-config.php
 * 2. Kleebi oma Stripe testvõtmed (Dashboard → Developers → API keys)
 * 3. Muuda success/cancel URL-id vastavalt, kust sa lehte avad
 */
return [
    'secret_key' => 'sk_test_your_secret_key_here',
    'success_url' => 'http://127.0.0.1:5500/tellimus-onnestus.html',
    'cancel_url' => 'http://127.0.0.1:5500/tellimus.html',
];
