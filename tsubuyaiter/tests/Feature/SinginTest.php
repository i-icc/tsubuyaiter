<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SigninTest extends TestCase
{
    use RefreshDatabase;

    private $correct_user_name = 'test user';
    private $correct_email = 'hoge@test.co.jp';
    private $correct_password = 'hogehoge';

    private $incorrect_email = 'hoge at test.co.jp';
    private $incorrect_password = 'hogehogehoge';
    private $zenkaku_password = 'hおげほげ';

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

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token'
            ]);
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

        $data = [
            "status" => 400,
            "errors" => [
                "email" =>
                [
                    "The email must be a valid email address."
                ]
            ]
        ];

        $response->assertStatus(400)
            ->assertExactJson($data);
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

        $data = [
            "status" => 400,
            "errors" => [
                "email" =>
                [
                    "The email field is required."
                ]
            ]
        ];

        $response->assertStatus(400)
            ->assertExactJson($data);
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

        $data = [
            "error" => "The email or password will be different."
        ];

        $response->assertStatus(400)
            ->assertExactJson($data);
    }

    /**
     * @test
     * @return void
     */
    public function サインイン失敗テスト_全角パスワード()
    {
        $response = $this->post('/api/v1/signin', [
            'email' => $this->correct_email,
            'password' => $this->zenkaku_password,
        ]);

        $data = [
            "status" => 400,
            "errors" => [
                "password" => [
                    "Please enter your password in half-width characters."
                ]
            ]
        ];

        $response->assertStatus(400)
            ->assertExactJson($data);
    }
}
