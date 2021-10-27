<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PostMessageTest extends TestCase
{
    use RefreshDatabase;

    private $correct_user_name = 'test user';
    private $correct_email = 'hoge@test.co.jp';
    private $correct_password = 'hogehoge';

    private $message = 'test message';
    private $token = null;
    private $incorrect_token = '1234567890';

    protected function setUp(): Void // ※ Voidが必要
    {
        parent::setUp();
        // signup
        $user = User::create([
            'user_name' => $this->correct_user_name,
            'email' => $this->correct_email,
            'password' => $this->correct_password,
        ]);
        $user->tokens()->delete();
        $this->token = $user->createToken("$user->id")->plainTextToken;
    }

    /**
     * @test
     * @return void
     */
    public function 認証成功テスト()
    {
        $response = $this->get('/api/user',  [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     * @return void
     */
    public function メッセージ投稿成功テスト()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post('/api/v1/messages', [
            'message' => $this->message,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'message_id'
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function メッセージ投稿失敗テスト_アクセストークンミス()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->incorrect_token,
        ])->post('/api/v1/messages', [
            'message' => $this->message,
        ]);

        $response->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function メッセージ投稿失敗テスト_パラメータ不足()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post('/api/v1/messages');

        $data = [
            "status" => 400,
            "errors" => [
                "message" => [
                    "The message field is required."
                ]
            ]
        ];

        $response->assertStatus(400)
            ->assertExactJson($data);
    }
}
