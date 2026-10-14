<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\User;
use App\Policies\PropertyPolicy;
use PHPUnit\Framework\TestCase;

class PropertyPolicyTest extends TestCase
{
    public function test_landlord_can_update_own_property(): void
    {
        $user = new User();
        $user->id = 7;
        $user->role = 'landlord';

        $property = new Property();
        $property->landlord_id = 7;

        $policy = new PropertyPolicy();

        $this->assertTrue($policy->update($user, $property));
    }

    public function test_landlord_cannot_update_another_landlords_property(): void
    {
        $user = new User();
        $user->id = 7;
        $user->role = 'landlord';

        $property = new Property();
        $property->landlord_id = 12;

        $policy = new PropertyPolicy();

        $this->assertFalse($policy->update($user, $property));
    }

    public function test_tenant_cannot_update_a_property(): void
    {
        $user = new User();
        $user->id = 7;
        $user->role = 'tenant';

        $property = new Property();
        $property->landlord_id = 7;

        $policy = new PropertyPolicy();

        $this->assertFalse(
            $policy->update($user, $property)
        );
    }
}