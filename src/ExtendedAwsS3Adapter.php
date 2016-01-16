<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-01-10
 * Time: 18:29
 */

namespace Oasis\Mlib\FlysystemWrappers;

use Aws\S3\StreamWrapper;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Symfony\Component\Finder\Finder;

class ExtendedAwsS3Adapter extends AwsS3Adapter
    implements FindableAdapterInterface
{

    /**
     * protocol => registering adapter
     *
     * @var array
     */
    static protected $registeredWrappers = [];

    /**
     * @param string $path child path to find in
     *
     * @return Finder
     */
    public function getFinder($path = '')
    {
        if (($protocol = array_search($this, self::$registeredWrappers))
            === false
        ) {
            $protocol = $this->registerStreamWrapper(null);
        }

        $path   = sprintf(
            "%s://%s/%s",
            $protocol,
            $this->getBucket(),
            $this->applyPathPrefix($path)
        );
        $finder = new Finder();
        $finder->in($path);

        return $finder;
    }

    public function registerStreamWrapper($protocol = "s3")
    {
        static $count = 0;
        if ($protocol === null) {
            $count++;
            $protocol = sprintf("s3f-%d", $count);
        }

        if (isset(self::$registeredWrappers[$protocol])) {
            if (self::$registeredWrappers[$protocol] === $this) {
                return $protocol;
            }

            throw new \LogicException("Protocol $protocol:// is already registered to another s3 resource");
        }

        StreamWrapper::register($this->s3Client, $protocol);

        self::$registeredWrappers[$protocol] = $this;

        return $protocol;
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
        $path   = $this->applyPathPrefix($path);
        $s3path = sprintf("s3://%s/%s", $this->getBucket(), $path);

        return $s3path;
    }

    public function getPreSignedUrl($path, $expires = "+30 minutes")
    {
        $location  = $this->applyPathPrefix($path);
        $cmd       = $this->getClient()->getCommand(
            "GetObject",
            [
                "Bucket" => $this->getBucket(),
                "Key"    => $location,
            ]
        );
        $presigned = $this->getClient()->createPresignedRequest($cmd, $expires);

        return strval($presigned->getUri());
    }
}
