<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2015-09-09
 * Time: 16:25
 */
namespace Oasis\Mlib\FlysystemWrappers;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Config;
use League\Flysystem\Util;

class AppendableLocal extends Local
    implements AppendableAdapterInterface
{
    /**
     * @inheritdoc
     */
    public function __construct($root, $writeFlags = LOCK_EX, $linkHandling = self::DISALLOW_LINKS)
    {
        parent::__construct($root, $writeFlags, $linkHandling);
    }

    /**
     * @inheritdoc
     */
    public function append($path, $contents, Config $config)
    {
        if (!$this->has($path)) {
            return $this->write($path, $contents, $config);
        }

        $steam_obj = $this->appendStream($path);
        $fh        = $steam_obj['stream'];

        if (($size = fwrite($fh, $contents)) === false) {
            return false;
        };
        fclose($fh);

        $type   = 'file';
        $result = compact('type', 'size', 'path');

        if ($visibility = $config->get('visibility')) {
            $result['visibility'] = $visibility;
            $this->setVisibility($path, $visibility);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function appendStream($path)
    {
        $location = $this->applyPathPrefix($path);
        $this->ensureDirectory(dirname($location));
        $stream = fopen($location, 'a');

        if (!is_resource($stream)) {
            return false;
        }

        return compact('stream', 'path');
    }
}
