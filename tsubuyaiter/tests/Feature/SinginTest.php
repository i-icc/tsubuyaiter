<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\DatabaseRule;
use Tests\TestCase;

class SigninTest extends TestCase
{
    use RefreshDatabase;

    private $correct_user_name = 'test user';
    private $correct_email = 'hoge@test.co.jp';
    private $correct_password = 'hogehoge';

    private $incorrect_email = 'hoge at test.co.jp';
    private $incorrect_password = 'hogehogehoge';

    /**
     * @test
     * @return void
     */
    public function サインイン成功テスト()
    {
        $this->post('/api/v1/signup', [
            'user_name' => $this->correct_user_name,
            'email' => $this->correct_email,
            'password' => $this->correct_password,
        ]);
        $response = $this->post('/api/v1/signin', [
            'email' => $this->correct_email,
            'password' => $this->correct_password,
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     * @return void
     */
    public function サインイン失敗テスト_メールアドレス形式ミス()
    {
        $response = $this->post('/api/v1/signin', [
            'email' => $this->incorrect_email,
            'password' => $this->correct_password,
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
     * @return void
     */
    public function サインイン失敗テスト_パラメータ不足()
    {
        $response = $this->post('/api/v1/signin', [
            'password' => $this->correct_password,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "email" => ["The email field is required."],
                ]
            ]);
    }

    /**
     * @test
     * @return void
     */
    public function サインイン失敗テスト_間違ったパスワード()
    {
        $response = $this->post('/api/v1/signin', [
            'email' => $this->correct_email,
            'password' => $this->incorrect_password,
        ]);

        $response->assertStatus(400);
    }
}
