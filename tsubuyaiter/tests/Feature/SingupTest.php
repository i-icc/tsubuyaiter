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
    public function サインアップ成功テスト()
    {
        $response = $this->post('/api/v1/signup', [
            'password' => 'hogehoge',
            'email' => 'hoge@hoge.co.jp',
            'user_name' => 'hoge',
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     * A basic test example.
     *
     * @return void
     */
    public function サインアップ失敗テスト()
    {
        $response = $this->post('/api/v1/signup', [
            'password' => 'hogehoge',
            'user_name' => 'hoge',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "email" => ["The email field is required."]
                ]
            ]);
    }

    /**
     * @test
     * A basic test example.
     *
     * @return void
     */
    public function サインアップ失敗テスト2()
    {
        $this->post('/api/v1/signup', [
            'password' => 'hogehoge',
            'email' => 'hoge@hoge.co.jp',
            'user_name' => 'hoge',
        ]);
        $response = $this->post('/api/v1/signup', [
            'password' => 'hogehoge',
            'email' => 'hoge@hoge.co.jp',
            'user_name' => 'hoge',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "email" => ["The email has already been taken."]
                ]
            ]);
    }

    /**
     * @test
     * A basic test example.
     *
     * @return void
     */
    public function サインアップ失敗テスト3()
    {
        $response = $this->post('/api/v1/signup', [
            'password' => 'hogehoge',
            'user_name' => 'hogehogehogehogehgoehogehgoehogehogehoge',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "email" => ["The email field is required."],
                    "user_name" => [
                        "The user name must not be greater than 20 characters."
                      ]
                ]
            ]);
    }
}
