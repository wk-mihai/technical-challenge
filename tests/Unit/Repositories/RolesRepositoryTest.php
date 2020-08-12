<?php

namespace Tests\Unit\Repositories;

use App\Models\Role;
use App\Repositories\RolesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RolesRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return RolesRepository
     */
    protected function getRepository(): RolesRepository
    {
        return new RolesRepository(resolve(Role::class));
    }

    /** @test */
    public function is_unsetting_slug_on_call_update_method()
    {
        $role = Role::firstWhere('slug', 'admin');

        $this->getRepository()->update($role, ['slug' => 'test-slug']);

        $this->assertTrue($role->slug === 'admin');
    }
}
