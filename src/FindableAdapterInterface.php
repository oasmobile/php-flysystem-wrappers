<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-01-10
 * Time: 18:25
 */

namespace Oasis\Mlib\FlysystemWrappers;

use Symfony\Component\Finder\Finder;

interface FindableAdapterInterface
{
    /**
     * @param string $path child path to find in
     *
     * @return Finder
     */
    public function getFinder($path = '');
}