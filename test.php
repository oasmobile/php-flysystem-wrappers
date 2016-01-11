#! /usr/local/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-01-12
 * Time: 00:25
 */

use Oasis\Mlib\FlysystemWrappers\ExtendedFilesystem;
use Oasis\Mlib\FlysystemWrappers\ExtendedLocal;

require_once 'vendor/autoload.php';

$local = new ExtendedLocal('/tmp/');
$fs    = new ExtendedFilesystem($local);
$fs->delete('test/.aaa');
