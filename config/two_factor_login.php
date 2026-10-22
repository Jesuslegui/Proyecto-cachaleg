<?php

return [
    'max_emails' => (int) env('TWO_FACTOR_LOGIN_MAX_EMAILS', 3),
    'rate_limit_seconds' => (int) env('TWO_FACTOR_LOGIN_RATE_LIMIT_SECONDS', 600),
    'max_attempts' => (int) env('TWO_FACTOR_LOGIN_MAX_ATTEMPTS', 5),
];
