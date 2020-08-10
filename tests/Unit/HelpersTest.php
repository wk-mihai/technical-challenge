<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Routing\Router;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Router $router */
        $router = $this->app->get('router');

        // Register a fake web route
        $router->middleware('web')->attribute('as', 'test-route')->get('_', function () {
            return response()->noContent();
        });
    }

    /** @test */
    public function is_determine_if_auth_user_is_admin()
    {
        $user = $this->createAdministrator();

        $this->actingAs($user)
            ->assertTrue(isAdmin());
    }

    /** @test */
    public function it_create_link_to_route()
    {
        $expectedHtml = "<a href='http://localhost/_'  class='test'>Test</a>";

        $this->assertEquals($expectedHtml, linkToRoute('test-route', 'Test', [], ['class' => 'test']));
    }
}
