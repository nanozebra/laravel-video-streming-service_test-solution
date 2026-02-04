<?php

return [
    'allowed_domains' => [
        env('APP_URL', 'https://crispy-space-sniffle-7vpw75vqw5p63x75-8000.app.github.dev/'),
        // Додаткові дозволені домени
    ],

    'session' => [
        'lifetime_minutes' => 30,
        'max_plays' => 3,
    ],

    'security' => [
        'enable_ip_validation' => false, // Disabled for testing
        'enable_user_agent_validation' => false, // Disabled for testing
        'enable_referer_validation' => false, // Disabled for testing
    ],
];