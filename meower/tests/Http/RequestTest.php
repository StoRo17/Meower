<?php

namespace Core\Tests\Http;

use Meower\Core\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    private $request;

    protected function setUp()
    {
        $this->request = new Request();
    }

    public function testMethodMethod()
    {
        $this->request->server['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('GET', $this->request->method());

        $this->request->server['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('POST', $this->request->method());

        $this->request->post['_method'] = 'PUT';
        $this->assertEquals('PUT', $this->request->method());
    }

    private function requestInput($key, $default = "")
    {
        return $this->request->input($key, $default);
    }

    public function testInputMethodWithoutArrayValues()
    {
        $this->request->post = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'number' => '123456789'
        ];

        $this->assertEquals('John', $this->requestInput('first_name'));
        $this->assertNotEquals('default', $this->requestInput('last_name', 'default'));
        $this->assertEquals('default', $this->requestInput('phone', 'default'));
    }

    public function testInputMethodWithArrayValues()
    {
        $this->request->post = [
            'category' => 'Pizza',
            'products' => [
                'Pepperoni',
                'Mozzarella',
                'Palermo'
            ],
            'objects' => [
                'obj1' => [
                    'value1',
                    'value2'
                ],
                'obj2' => 'value3'
            ]
        ];

        $this->assertEquals('Pizza', $this->requestInput('category'));
        $this->assertInternalType('array', $this->requestInput('products'));
        $this->assertCount(3, $this->requestInput('products'));
        $this->assertEquals('Mozzarella', $this->requestInput('products.1'));
        $this->assertInternalType('array', $this->requestInput('objects.obj1'));
        $this->assertEquals('value1', $this->requestInput('objects.obj1.0'));
        $this->assertEquals('value3', $this->requestInput('objects.obj2'));
    }

    public function testNormalizeFilesArrayMethod()
    {
        $class = new \ReflectionClass($this->request);
        $method = $class->getMethod('normalizeFilesArray');
        $method->setAccessible('true');
        $output = $method->invoke($this->request, [
            'document' => [
                'name' => 'file.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => '/tmp/path/something',
                'error' => 0,
                'size' => 0
            ],

            'documents' => [
                'name' => [
                    'file1.jpg',
                    'file2.jpg'
                ],
                'type' => [
                    'image/jpeg',
                    'image/jpeg'
                ],
                'tmp_name' => [
                    '/tmp/path/smth1',
                    '/tmp/path/smth2'
                ],
                'error' => [
                    0,
                    0
                ],
                'size' => [
                    0,
                    0
                ]
            ]
        ]);

        $this->assertEquals([
                'document' => [
                    'name' => 'file.jpg',
                    'type' => 'image/jpeg',
                    'tmp_name' => '/tmp/path/something',
                    'error' => 0,
                    'size' => 0
                ],
                'documents' => [
                    [
                        'name' => 'file1.jpg',
                        'type' => 'image/jpeg',
                        'tmp_name' => '/tmp/path/smth1',
                        'error' => 0,
                        'size' => 0
                    ], [
                        'name' => 'file2.jpg',
                        'type' => 'image/jpeg',
                        'tmp_name' => '/tmp/path/smth2',
                        'error' => 0,
                        'size' => 0
                    ]
                ]
            ], $output);
    }
}
