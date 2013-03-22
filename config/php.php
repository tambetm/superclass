<?php

// error handling
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// email configuration
ini_set('SMTP', 'mail.neti.ee');
ini_set('sendmail_from', 'tambet@korteriyhistu.net');

// charsets
ini_set('default_charset', 'UTF-8');
ini_set('mbstring.internal_encoding', ini_get('default_charset'));

// locale
setlocale(LC_ALL, ''); // use '' in Windows, 'et_EE' in Linux

// session
ini_set('session.cache_limiter', '');
