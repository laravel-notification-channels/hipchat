<?php

namespace NotificationChannels\HipChat\Test;

use NotificationChannels\HipChat\HipChatFile;

class HipChatFileTest extends TestCase
{
    protected $filePath = 'file.txt';

    public function setUp()
    {
        parent::setUp();

        chdir(__DIR__.'/stubs');
    }

    public function test_it_can_be_instantiated()
    {
        $file = new HipChatFile;

        $this->assertInstanceOf(HipChatFile::class, $file);
    }

    public function test_it_accepts_file_path_when_created()
    {
        $file = new HipChatFile($this->filePath);

        $this->assertTrue(is_resource($file->fileContent));
        $this->assertEquals($this->filePath, $this->pathFromResource($file->fileContent));
    }

    public function test_it_supports_create_method()
    {
        $file = HipChatFile::create($this->filePath);

        $this->assertInstanceOf(HipChatFile::class, $file);
        $this->assertTrue(is_resource($file->fileContent));

        $this->assertEquals($this->filePath, $this->pathFromResource($file->fileContent));
    }

    public function test_it_sets_room()
    {
        $file = HipChatFile::create()
            ->room('Room');

        $this->assertEquals('Room', $file->room);

        $file->room(1234567890);

        $this->assertEquals('1234567890', $file->room);
    }

    public function test_it_sets_path()
    {
        $file = HipChatFile::create()
            ->path($this->filePath);

        $this->assertTrue(is_resource($file->fileContent));
        $this->assertEquals($this->filePath, $this->pathFromResource($file->fileContent));
        $this->assertEquals(basename($this->filePath), $file->fileName);
        $this->assertEquals('text/plain', $file->fileType);
    }

    public function test_it_sets_message_content()
    {
        $file = HipChatFile::create()
            ->content('File sent.');

        $this->assertEquals('File sent.', $file->content);
    }

    public function test_it_trims_message_content()
    {
        $file = HipChatFile::create()
            ->content("\t File sent.\n");

        $this->assertEquals('File sent.', $file->content);
    }

    public function test_it_sets_message_text()
    {
        $file = HipChatFile::create()
            ->text('File sent.');

        $this->assertEquals('File sent.', $file->content);
    }

    public function test_it_sets_file_content()
    {
        $file = HipChatFile::create()
            ->fileContent('foo bar');

        $this->assertEquals('foo bar', $file->fileContent);

        $file->fileContent(fopen('data://text/plain,foo bar', 'r'));

        $this->assertTrue(is_resource($file->fileContent));
        $this->assertEquals('data://text/plain,foo bar', $this->pathFromResource($file->fileContent));
        $this->assertEquals('text/plain', $file->fileType);

        $file->fileContent(fopen($this->filePath, 'r'));

        $this->assertTrue(is_resource($file->fileContent));
        $this->assertEquals($this->filePath, $this->pathFromResource($file->fileContent));
        $this->assertEquals('text/plain', $file->fileType);
    }

    public function test_it_sets_filename()
    {
        $file = HipChatFile::create()
            ->fileName('baz.txt');

        $this->assertEquals('baz.txt', $file->fileName);
    }

    public function test_it_sets_file_type()
    {
        $file = HipChatFile::create()
            ->fileType('image/png');

        $this->assertEquals('image/png', $file->fileType);
    }

    public function test_it_transforms_to_array()
    {
        $file = HipChatFile::create()
            ->path($this->filePath)
            ->fileName('new.txt')
            ->fileType('text/plain')
            ->content('File sent.')
            ->toArray();

        $this->assertArraySubset([
            'filename' => 'new.txt',
            'file_type' => 'text/plain',
            'message' => 'File sent.',
        ], $file);

        $this->assertTrue(is_resource($file['content']));
        $this->assertEquals($this->filePath, $this->pathFromResource($file['content']));
    }

    public function test_it_allows_falsey_values_in_attributes()
    {
        $file = HipChatFile::create()
            ->path('0')
            ->fileName('0')
            ->text('0');

        $this->assertArraySubset([
            'filename' => '0',
            'message' => '0',
        ], $file->toArray());

        $this->assertTrue(is_resource($file->fileContent));
        $this->assertEquals('0', $this->pathFromResource($file->fileContent));
    }

    public function test_it_allows_falsey_values_in_create()
    {
        $file = HipChatFile::create('0');

        $this->assertArraySubset([
            'filename' => '0',
        ], $file->toArray());

        $this->assertTrue(is_resource($file->fileContent));
        $this->assertEquals('0', $this->pathFromResource($file->fileContent));
    }

    protected function pathFromResource($resource)
    {
        $meta = stream_get_meta_data($resource);

        return isset($meta['uri']) ? $meta['uri'] : null;
    }
}
