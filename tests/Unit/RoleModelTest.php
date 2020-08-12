<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RoleModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function is_showing_humanized_types_name()
    {
        $role = $this->createRoleWithTypes(5);

        $roleTypesName = $role->types->pluck('name')->toArray();

        $this->assertEquals($this->humanizeTypeNames($roleTypesName), $role->humanizeTypeNames(true));

    }

    /**
     * @param array $types
     * @return string
     */
    protected function humanizeTypeNames(array $types): string
    {
        $types = array_slice($types, 0, 3);

        return implode(', ', $types) . '...';
    }
}
