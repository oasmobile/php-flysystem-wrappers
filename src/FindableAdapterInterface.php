<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-01-10
 * Time: 18:25
 */

namespace Oasis\Mlib\FlysystemWrappers;

use League\Flysystem\AdapterInterface;
use Symfony\Component\Finder\Finder;

interface FindableAdapterInterface extends AdapterInterface
{
    /**
     * @param string $path child path to find in
     *
     * @return Finder
     */
    public function getFinder($path = '');

    /**
     * Returns real system path of $path, this can be absolute path on local filesystem, or s3:// prepended s3path
     *
     * @param string $path
     *
     * @return string
     */
    public function getRealpath($path);
}
