<?php

$GoogleConfig = [
  'RECAPTCHA_SECRET' => '6LfJvSETAAAAAASjhZW5h28ooMw5d6b6whX8-6Ul',
  'SITE_KEY' => '6LfJvSETAAAAAMl3Mk3kRHkLOlKBSO7siUbKC7v9'
];
$GoogleConfig['RECAPTCHA_ENDPOINT'] = 'https://www.google.com/recaptcha/api/siteverify?secret='.$GoogleConfig['RECAPTCHA_SECRET'];

return $GoogleConfig;
