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
        var_dump($this->token);
    }

    /**
     * @test
     * @return void
     */
    public function メッセージ投稿成功テスト()
    {
        $response = $this->post('/api/v1/messages', [
            'Authorization' => 'Bearer ' . $this->token,
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
        $response = $this->post('/api/v1/messages', [
            'Authorization' => 'Bearer test',
            'message' => $this->message,
        ]);

        $response->assertStatus(302);
    }
}
