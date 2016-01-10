<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2015-09-14
 * Time: 17:30
 */
namespace Oasis\Mlib\UnitTesting;

use Oasis\Mlib\FlysystemWrappers\ExtendedFilesystem;
use Oasis\Mlib\FlysystemWrappers\ExtendedLocal;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Filesystem\Filesystem;

class AppendableFilesystemTest extends PHPUnit_Framework_TestCase
{
    public $tempdir = '';

    protected function setUp()
    {
        $this->tempdir = sys_get_temp_dir() . "/mlib-test/logs";
    }

    protected function tearDown()
    {
        $symfony_fs = new Filesystem();
        if ($symfony_fs->exists($this->tempdir)) {
            $symfony_fs->remove($this->tempdir);
        }
    }

    public function testAppendableFilesystemCreation()
    {
        $adapter = new ExtendedLocal($this->tempdir);
        $fs      = new ExtendedFilesystem($adapter);

        return $fs;
    }

    /**
     * @depends testAppendableFilesystemCreation
     *
     * @param ExtendedFilesystem $fs
     *
     * @return ExtendedFilesystem
     */
    public function testAppendStreamOnNewFile(ExtendedFilesystem $fs)
    {
        $newFile = "new_file.txt";

        $str = <<<STRING
A quick brown fox jumps over a lazy dog.
STRING;

        $fh = $fs->appendStream($newFile);
        fwrite($fh, $str);
        fclose($fh);

        $this->assertTrue($fs->has($newFile));
        $this->assertEquals($str, $fs->read($newFile));

        return $fs;
    }

    /**
     * @depends testAppendStreamOnNewFile
     *
     * @param ExtendedFilesystem $fs
     *
     * @return ExtendedFilesystem
     */
    public function testAppendStreamOnExistingFile(ExtendedFilesystem $fs)
    {
        $newFile = "new_file.txt";

        $orig = <<<STRING
abcdefghijklmn
STRING;
        $fs->write($newFile, $orig);

        $str = <<<STRING
opqrst
STRING;

        $fh = $fs->appendStream($newFile);
        fwrite($fh, $str);
        fclose($fh);

        $this->assertContains($orig . $str, $fs->read($newFile));

        $str2 = <<<STRING
uvwxyz
STRING;
        $fs->append($newFile, $str2);

        $this->assertContains($orig . $str . $str2, $fs->read($newFile));

        return $fs;
    }

    /**
     * @depends testAppendStreamOnNewFile
     *
     * @param ExtendedFilesystem $fs
     *
     * @return ExtendedFilesystem
     */
    public function testFinder(ExtendedFilesystem $fs)
    {
        $fs->put('a/b/c.txt', 'aaa');
        $fs->put('a/b/d.txt', 'aaa');
        $fs->put('a/b/d.jpg', 'aaa');
        $fs->put('a/b/x.txt', 'aaa');
        $fs->put('a/c/e.txt', 'aaa');

        $finder = $fs->getFinder('a');
        $finder->path('#b/[cd]\\.txt#');
        $this->assertEquals(count($finder), 2);
    }
}
