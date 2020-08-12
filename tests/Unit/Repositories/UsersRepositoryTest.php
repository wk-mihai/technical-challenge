<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UsersRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UsersRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return UsersRepository
     */
    protected function getRepository(): UsersRepository
    {
        return new UsersRepository(resolve(User::class));
    }

    /** @test */
    public function is_unsetting_password_on_call_update_method()
    {
        $user = $this->createUser();

        $oldPassword = $user->getAuthPassword();

        $this->getRepository()->update($user, ['password' => null]);

        $passwordAfterUpdating = $user->getAuthPassword();

        $this->assertTrue($oldPassword === $passwordAfterUpdating);
    }
}
