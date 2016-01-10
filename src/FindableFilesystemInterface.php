<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-01-10
 * Time: 18:22
 */

namespace Oasis\Mlib\FlysystemWrappers;

use Symfony\Component\Finder\Finder;

interface FindableFilesystemInterface
{
    /**
     * @param string $path child path to find in
     *
     * @return Finder
     */
    public function getFinder($path = '');
}
