<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\DatabaseRule;
use Tests\TestCase;

class SignupTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * A basic test example.
     *
     * @return void
     */
    public function サインアップテスト()
    {
        $response = $this->post('/api/v1/signup',[
            'password' => 'hogehoge',
            'email' => 'hoge@hoge.co.jp',
            'user_name' => 'hoge',
        ]);

        $response->assertStatus(200);
    }
}
