<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class getMessagesTest extends TestCase
{
    use RefreshDatabase;

    private $user_name = 'test user';
    private $email = 'hoge@test.co.jp';
    private $password = 'hogehoge';

    private $user_name2 = 'test user2';
    private $email2 = 'hogehoge@test.co.jp';
    private $password2 = 'hogehoge';

    protected function setUp(): Void 
    {
        parent::setUp();
        // signup
        $user = User::create([
            'user_name' => $this->user_name,
            'email' => $this->email,
            'password' => $this->password,
        ]);
        $user2 = User::create([
            'user_name' => $this->user_name2,
            'email' => $this->email2,
            'password' => $this->password2,
        ]);
        $token = $user->createToken("$user->id")->plainTextToken;
        $token2 = $user2->createToken("$user2->id")->plainTextToken;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v1/messages', [
            'message' => "test message1",
        ]);
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token2,
        ])->post('/api/v1/messages', [
            'message' => "test message2",
        ]);
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token2,
        ])->post('/api/v1/messages', [
            'message' => "test message3",
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function メッセージ一覧取得成功テスト()
    {
        $response = $this->get('/api/v1/messages');

        $response->assertStatus(200)->assertJsonStructure([
            'messages'
        ]);
    }
}
