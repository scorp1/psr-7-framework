<?php
$cfg = array(
    'memcached' => array(
        'adapter' => [
            'name' => 'memcached',
            'options' => [
                'ttl' => 7200,
                'servers' => [
                    'host' => '127.0.0.1',
                    'port' => 11211,
                    'persistent' => true,
                    'weight' => 1,
                    'status' => true
                ],

            ]
        ],
        'plugins' => [
            'exception_handler' => [
                'throw_exceptions' => false
            ],
        ]
    ),
    'filesystem' => array(
        'adapter' => array(
            'name' => 'filesystem',
            'options' => array(
                'dirLevel' => 2,
                'cacheDir' => __DIR__ . '/data/cache',
                'dirPermission' => 0755,
                'filePermission' => 0666,
                'namespaceSeparator' => '-db-'
            ),
        ),
        'plugins' => [
            'exception_handler' => [
                'throw_exceptions' => false
            ],
        ]
    )
);
