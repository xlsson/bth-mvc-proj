<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\YatzyController;
use App\Models\Yatzy\Yatzy;
use App\Models\User;

class YatzyControllerTest extends TestCase
{

    /**
     * Test that instantiated object is instance of YatzyController
     */
    public function testInstantiateYatzyController()
    {
        $controller = new YatzyController();

        $this->assertInstanceOf("App\Http\Controllers\YatzyController", $controller);
    }

    /**
     * Check that YatzyController class extends Controller
     */
    public function testYatzyControllerExtendsController()
    {
        $controller = new YatzyController();

        $this->assertInstanceOf("App\Http\Controllers\Controller", $controller);
    }

    /**
     * Test that gamemode route renders an OK HTTP response (200),
     * and that the response contains a string from the page title
     *
     * @return void
     */
    public function testGamemode()
    {
        $controller = new YatzyController();
        $usersObject = new User();

        auth()->attempt(['name' => 'admin', 'password' => 'admin']);

        $users = $usersObject->getAllUsers();
        $coins = $usersObject->getCoins(auth()->user()->id);

        $response = $this->get('/gamemode', [
            'users' => $users,
            'coinsCurrentUser' => $coins
        ]);

        $response->assertStatus(200);
        $response->assertSee('Choose mode');
    }

    /**
     * Test that the post route /yatzystart in single game mode renders a
     * view containing the expected string "Live"
     *
     * @return void
     */
    public function testStartSingle()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/yatzystart', [
            'mode' => 'single',
            'bet' => '0',
            'opponent' => '0',
            'challengeId' => '0'
        ]);

        $response->assertStatus(200);
        $response->assertSee('Live');
    }

    /**
     * Test that the post route /yatzystart in challenge mode renders a
     * view containing the expected string "Live"
     *
     * @return void
     */
    public function testStartChallenge()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/yatzystart', [
            'mode' => 'challenge',
            'bet' => '0',
            'opponent' => '9',
            'challengeId' => '0'
        ]);

        $response->assertStatus(200);
        $response->assertSee('Live');
    }

    /**
     * Test that the post route /yatzy renders a view containing the
     * expected string "Live"
     *
     * @return void
     */
    public function testPlay()
    {
        auth()->attempt(['name' => 'admin', 'password' => 'admin']);

        $yatzyObject = new Yatzy("single", "100", "1", "", "1");
        $yatzyObject->startNewRound();
        session()->put('yatzy', $yatzyObject);

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/yatzy', [
            '1' => 'selected',
            'roll' => 'Roll!'
        ]);

        $response->assertStatus(200);
        $response->assertSee('Live');
    }
}
