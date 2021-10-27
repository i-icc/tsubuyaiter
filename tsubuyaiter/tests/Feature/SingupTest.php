<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignupTest extends TestCase
{
    use RefreshDatabase;

    private $correct_user_name = 'test user';
    private $correct_email = 'hoge@test.co.jp';
    private $correct_password = 'hogehoge';

    private $too_long_user_name = 'hogehogehogehogehogehogehogehogehogehogehogehogehogehogehogehogehoge';
    private $incorrect_email = 'hoge at test.co.jp';
    private $zenkaku_password = '12３４ああ';

    /**
     * @test
     * @return void
     */
    public function サインアップ成功テスト()
    {
        $response = $this->post('/api/v1/signup', [
            'user_name' => $this->correct_user_name,
            'email' => $this->correct_email,
            'password' => $this->correct_password,
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     * @return void
     */
    public function サインアップ失敗テスト_パラメータ不足()
    {
        $response = $this->post('/api/v1/signup', [
            'user_name' => $this->correct_user_name,
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
    public function サインアップ失敗テスト_既存email使用()
    {
        $this->post('/api/v1/signup', [
            'user_name' => $this->correct_user_name,
            'email' => $this->correct_email,
            'password' => $this->correct_password,
        ]);
        $response = $this->post('/api/v1/signup', [
            'user_name' => $this->correct_user_name,
            'email' => $this->correct_email,
            'password' => $this->correct_password,
        ]);

        $data = [
            "status" => 400,
            "errors" => [
                "email" =>
                [
                    "The email has already been taken."
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
    public function サインアップ失敗テスト_文字数オーバー()
    {
        $response = $this->post('/api/v1/signup', [
            'user_name' => $this->too_long_user_name,
            'email' => $this->correct_email,
            'password' => $this->correct_password,
        ]);

        $data = [
            "status" => 400,
            "errors" => [
                "user_name" =>
                [
                    "The user name must not be greater than 20 characters."
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
    public function サインアップ失敗テスト_メール形式でないemail()
    {
        $response = $this->post('/api/v1/signup', [
            'user_name' => $this->correct_user_name,
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
    public function サインアップ失敗テスト_全角パスワード()
    {
        $response = $this->post('/api/v1/signup', [
            'user_name' => $this->correct_user_name,
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
