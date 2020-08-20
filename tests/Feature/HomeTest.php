<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomePage()
    {
        $response = $this->get('/home');

        $response->assertSeeText('Home page');
        $response->assertSeeText('Learn laravel 7');
    }

    public function testAboutPage(){
        $response= $this->get('/about');
        $response->assertSeeText('bout home');
    }
}
