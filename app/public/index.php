<?php
use Symfony\Component\HttpFoundation\Request;
use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
file_put_contents('/tmp/request_headers.log', json_encode($_SERVER)."\n", FILE_APPEND);
Request::setTrustedProxies(
    ['0.0.0.0/0', '::/0'],
    Request::HEADER_X_FORWARDED_FOR |
                        Request::HEADER_X_FORWARDED_HOST |
                        Request::HEADER_X_FORWARDED_PROTO |
                        Request::HEADER_X_FORWARDED_PORT
);

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
