<?php

namespace Meower\Tests\Http;

use Meower\Core\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    private $response;

    protected function setUp()
    {
        $this->response = new Response();
    }

    private function getHeaders()
    {
       return $this->response->getHeaders();
    }

    public function testRedirectMethod()
    {
        $redirect = '/home';
        $this->response->redirect($redirect);

        $headers = $this->getHeaders();
        $this->assertArrayHasKey('Location', $headers);
        $this->assertEquals($redirect, $headers['Location']);
        $this->assertEquals(302, $this->response->getStatusCode());
    }

    public function testHeaderMethod()
    {
        $this->response->header('Content-Length', '250');
        $this->response->header('Location', '/dashboard');

        $headers = $this->getHeaders();
        $this->assertCount(2, $headers);
        $this->assertArrayHasKey('Content-Length', $headers);
        $this->assertContains('/dashboard', $headers);
    }

    public function testWithHeadersMethod()
    {
        $this->response->withHeaders([
            'Content-Length' => '250',
            'Content-Type' => 'text/html',
            'Location' => '/home'
        ]);

        $headers = $this->getHeaders();
        $this->assertCount(3, $headers);
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertContains('text/html', $headers);
    }

    public function testWithStatusMethod()
    {
        $this->response->withStatus(201);
        $this->assertEquals(201, $this->response->getStatusCode());
    }

    public function testJsonMethod()
    {
        $array = [
            'code' => '200',
            'success' => 'true',
            'message' => 'Data was loaded'
        ];
        $this->response->json($array);

        $headers = $this->getHeaders();
        $body = $this->response->getBody();
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertEquals('application/json', $headers['Content-Type']);
        $this->assertJsonStringEqualsJsonString(json_encode($array), $body);
        $this->assertArrayHasKey('Content-Length', $headers);
        $this->assertEquals(mb_strlen(json_encode($array)), $headers['Content-Length']);
    }

    public function testBodyMethod()
    {
        $body = 'Hello World';
        $this->response->body($body);

        $headers = $this->getHeaders();
        $this->assertEquals($body, $this->response->getBody());
        $this->assertArrayHasKey('Content-Length', $headers);
        $this->assertEquals(mb_strlen($body), $headers['Content-Length']);
    }

    public function testGetHeaderLinesMethod()
    {
        $this->response->withHeaders([
            'Content-Length' => '250',
            'Content-Type' => 'text/html',
            'Location' => '/home'
        ]);

        $headers = $this->response->getHeaderLines();
        $this->assertCount(3, $headers);
        $this->assertContains('Location: /home', $headers);
    }
}
