<?php
return [
    'host'       => 'smtp.gmail.com',
    'username'   => $_ENV['SMTP_USER'],
    'password'   => $_ENV['SMTP_PASS'],
    'port'       => 587,
    'encryption' => 'tls',
    'from_email' => $_ENV['SMTP_USER'],
    'from_name'  => 'Barbearia Luiz',
    'app_url'    => 'http://localhost/barbearia'
];
