<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AkunController;

class AkunControllerTest extends TestCase
{
    public function test_login_credentials()
    {
        // Mocking Query Builder
        $mockQuery = Mockery::mock('Illuminate\Database\Query\Builder');
        $mockQuery->shouldReceive('where')
            ->with('username', 'wronguser')
            ->andReturnSelf()
            ->shouldReceive('first')
            ->andReturn(null);

        // Mock DB facade to return query builder mock
        DB::shouldReceive('table')
            ->with('akuns')
            ->andReturn($mockQuery);

        // Mocking Request
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'u' => 'wronguser',
            'p' => 'wrongpassword',
        ]);

        // Instantiate controller
        $controller = new AkunController();
        $response = $controller->login($request);

        // Assert response contains login error
        $this->assertStringContainsString('Username atau password salah.', session('errors')->first('loginError'));
    }
}
