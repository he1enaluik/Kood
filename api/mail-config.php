<?php

/**
 * Legacy PHP API mail recipient — loaded from environment variables.
 * Copy api/mail-config.example.php locally if needed.
 */
return [
    'to' => getenv('MAIL_TO') ?: 'info@tarukoda.ee',
];
