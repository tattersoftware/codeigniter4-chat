<?php

use Tatter\Chat\Entities\Message;
use Tests\Support\ModuleTestCase;

/**
 * @internal
 */
final class MessageTest extends ModuleTestCase
{
    /**
     * @dataProvider contentProvider
     */
    public function testGetContent(string $format, string $content, string $expected): void
    {
        $message = new Message(['content' => $content]);
        $result  = $message->getContent($format);

        $this->assertSame($expected, $result);
    }

    /**
     * @return array<string,string[]>
     */
    public function contentProvider(): array
    {
        return [
            'html' => [
                'html',
                "hello\nworld",
                "hello<br />\nworld",
            ],
            'json' => [
                'json',
                'hello world',
                json_encode('hello world'),
            ],
            'markdown' => [
                'markdown',
                '# hello world',
                '<h1>hello world</h1>' . PHP_EOL,
            ],
            'raw' => [
                'raw',
                'hello world',
                'hello world',
            ],
            'default' => [
                'foo',
                'hello world',
                'hello world',
            ],
        ];
    }
}
