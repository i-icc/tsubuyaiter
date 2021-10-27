<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\DatabaseRule;
use Tests\TestCase;

class SigninTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * A basic test example.
     *
     * @return void
     */
    public function サインイン成功テスト()
    {
        $this->post('/api/v1/signup', [
            'password' => 'hogehoge',
            'email' => 'hoge@hoge.co.jp',
            'user_name' => 'hoge',
        ]);
        $response = $this->post('/api/v1/signin', [
            'password' => 'hogehoge',
            'email' => 'hoge@hoge.co.jp',
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     * A basic test example.
     *
     * @return void
     */
    public function サインイン失敗テスト()
    {
        $response = $this->post('/api/v1/signin', [
            'password' => 'hogehoge',
            'email' => 'hoge at hoge.co.jp',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "email" => [
                        "The email must be a valid email address."
                    ]
                ]
            ]);
    }

    /**
     * @test
     * A basic test example.
     *
     * @return void
     */
    public function サインイン失敗テスト2()
    {
        $response = $this->post('/api/v1/signin', [
            'password' => 'hogehoge',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "email" => ["The email field is required."],
                ]
            ]);
    }
}
