<?php

require_once(__DIR__ . '/AbstractContainerFields.php');
require_once(__DIR__ . '/API.php');
require_once(__DIR__ . '/DefaultCache.php');
require_once(__DIR__ . '/LogLevelSelectField.php');
require_once(__DIR__ . '/TextField.php');
require_once(__DIR__ . '/RepeatableField.php');

class SouinConfiguration extends AbstractContainerFields
{
    public function __construct($initialValue = null)
    {
        $base = 'configuration';
        parent::__construct([
            new API($initialValue ? $initialValue->api : null),
            new DefaultCache($initialValue ? $initialValue->default_cache : null),
            new LogLevelSelectField('log_level', 'Log level', $base, $initialValue ? $initialValue->log_level : null),
            new TextField(\sprintf('%s[reverse_proxy_url]', $base), 'Reverse proxy URL (e.g. http://your-domain or http://localhost:8000)', $initialValue ? $initialValue->reverse_proxy_url : null),
            new RepeatableField(\sprintf('%s[ykeys]', $base), 'Caching keys', $initialValue ? $initialValue->ykeys : null, [
                new TextField(\sprintf('%s[ykeys][][name]', $base), 'Cache key name'),
                new TextField(\sprintf('%s[ykeys][][url]', $base), 'URL regex to tag as using this one'),
                //new RepeatableField(\sprintf('%s[ykeys][][headers]', $base), 'Headers to restrict tag', null, [
                //    new TextField(\sprintf('%s[ykeys][][headers][]', $base), 'Header to tag as using this one'),
                //])
            ]),
        ], 'souin_configuration', 'Souin Configuration');
    }
}
