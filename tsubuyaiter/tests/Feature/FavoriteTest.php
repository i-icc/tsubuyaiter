<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Message;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    private $correct_user_name = 'test user';
    private $correct_email = 'hoge@test.co.jp';
    private $correct_password = 'hogehoge';

    private $token = null;
    private $incorrect_token = '1234567890';
    private $correct_message_id = null;
    private $incorrect_message_id = null;

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
        $message = Message::create([
            'user_id' =>  $user->id,
            'message' => "test message",
        ]);
        $this->correct_message_id = $message->id;
        $this->incorrect_message_id = $message->id + 10;
    }

    /**
     * @test
     * @return void
     */
    public function いいね付与成功テスト()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post('/api/v1/' . $this->correct_message_id . '/fav');

        $response->assertStatus(200);
    }

    /**
     * @test
     * @return void
     */
    public function いいね付与失敗テスト_アクセストークンミス()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token . 'hoge',
        ])->post('/api/v1/' . $this->correct_message_id . '/fav');

        $response->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function いいね付与失敗テスト_存在しないメッセージへのいいね()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post('/api/v1/' . $this->incorrect_message_id . '/fav');

        $response->assertStatus(500)
        ->assertJsonStructure([
            'error'
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function いいね付与失敗テスト_すでにいいね済みのメッセージへのいいね()
    {
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post('/api/v1/' . $this->correct_message_id . '/fav');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post('/api/v1/' . $this->correct_message_id . '/fav');

        $data = [
            "error" => "I have already given this message a favorite."
        ];

        $response->assertStatus(500)
            ->assertExactJson($data);
    }
}
