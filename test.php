#! /usr/local/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-01-12
 * Time: 00:25
 */

use Aws\S3\S3Client;
use Oasis\Mlib\FlysystemWrappers\ExtendedAwsS3Adapter;
use Oasis\Mlib\FlysystemWrappers\ExtendedFilesystem;

require_once 'vendor/autoload.php';

$adapter = new ExtendedAwsS3Adapter(
    new S3Client(
        [
            'profile' => 'dmp-user',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]
    ), 'brotsoft-dmp', 'test'
);
$fs = new ExtendedFilesystem($adapter);

$fs->put('haha', 'lol');

$uri = $adapter->getPreSignedUrl('haha');

var_dump($uri);
