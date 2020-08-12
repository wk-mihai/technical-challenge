<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Routing\Router;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Router $router */
        $router = $this->app->get('router');

        // Register a fake web route
        $router->middleware('admin')->attribute('as', 'test-route')->post('_', function () {
            return response()->noContent();
        });
    }

    /** @test */
    public function is_redirecting_to_login_page_if_non_authenticate()
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function is_aborting_if_is_not_admin_user()
    {
        $this->actingAs($this->createUser())
            ->get(route('admin.trainings.index'))
            ->assertForbidden();

        $this->actingAs($this->createUser())
            ->post(route('test-route'), [], ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
            ->assertForbidden();
    }
}
