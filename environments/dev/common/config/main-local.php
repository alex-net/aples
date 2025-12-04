<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            // 'dsn' => 'sqlite:@backend/runtime/data.db',
            'dsn' => 'mysql:host=db;dbname=db',
            'username' => 'db',
            'password' => 'db',
            'charset' => 'utf8',
        ],
        // 'mailer' => [
        //     'class' => 'yii\swiftmailer\Mailer',
        //     'viewPath' => '@common/mail',
        //     // send all mails to a file by default. You have to set
        //     // 'useFileTransport' to false and configure a transport
        //     // for the mailer to send real emails.
        //     'useFileTransport' => true,
        // ],
    ],
];
