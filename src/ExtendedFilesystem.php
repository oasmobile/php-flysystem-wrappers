<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2015-09-09
 * Time: 16:32
 */
namespace Oasis\Mlib\FlysystemWrappers;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\NotSupportedException;
use League\Flysystem\Util;
use Symfony\Component\Finder\Finder;

class ExtendedFilesystem extends Filesystem
    implements AppendableFilesystemInterface, FindableFilesystemInterface
{
    /**
     * @inheritdoc
     */
    public function __construct(AdapterInterface $adapter, $config = null)
    {
        parent::__construct($adapter, $config);
    }

    /**
     * @inheritdoc
     */
    public function append($path, $contents, array $config = [])
    {
        $path   = Util::normalizePath($path);
        $config = $this->prepareConfig($config);

        $adapter = $this->getAdapter();
        if (!$adapter instanceof AppendableAdapterInterface) {
            throw new NotSupportedException(
                "Adapter doesn't support append action. Adapter in use is: "
                . get_class($adapter)
            );
        }

        return (bool)$adapter->append($path, $contents, $config);
    }

    /**
     * @inheritdoc
     */
    public function appendStream($path)
    {
        $path = Util::normalizePath($path);

        $adapter = $this->getAdapter();
        if (!$adapter instanceof AppendableAdapterInterface) {
            throw new NotSupportedException(
                "Adapter doesn't support append action. Adapter in use is: "
                . get_class($adapter)
            );
        }

        if (!$object = $adapter->appendStream($path)) {
            return false;
        }

        return $object['stream'];
    }

    /**
     * @param string $path child path to find in
     *
     * @return Finder
     */
    public function getFinder($path = '')
    {
        $path = Util::normalizePath($path);

        $adapter = $this->getAdapter();
        if (!$adapter instanceof FindableAdapterInterface) {
            throw new NotSupportedException(
                "Adapter doesn't support getFinder action. Adapter in use is: "
                . get_class($adapter)
            );
        }

        return $adapter->getFinder($path);
    }

    /**
     * Returns real system path of $path, this can be absolute path on local filesystem, or s3:// prepended s3path
     *
     * @param string $path
     *
     * @return string
     */
    public function getRealpath($path)
    {
        $adapter = $this->getAdapter();
        if (!$adapter instanceof FindableAdapterInterface) {
            throw new NotSupportedException(
                "Adapter doesn't support getRealpath action. Adapter in use is: "
                . get_class($adapter)
            );
        }

        return $adapter->getRealpath($path);
    }
}
