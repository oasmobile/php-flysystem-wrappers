<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-01-10
 * Time: 18:22
 */

namespace Oasis\Mlib\FlysystemWrappers;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\Finder\Finder;

interface FindableFilesystemInterface extends FilesystemInterface
{
    /**
     * @param string $path child path to find in
     *
     * @return Finder
     */
    public function getFinder($path = '');
}
