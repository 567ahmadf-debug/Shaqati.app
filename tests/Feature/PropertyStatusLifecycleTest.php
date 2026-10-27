<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyStatusLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_landlord_can_submit_draft_for_review(): void
    {
        $landlord = User::factory()->landlord()->create();

        $property = Property::factory()
            ->for($landlord, 'landlord')
            ->create([
                'status' => Property::STATUS_DRAFT,
            ]);

        $response = $this->actingAs($landlord, 'sanctum')
            ->postJson(
                "/api/properties/{$property->id}/submit-for-review"
            );

        $response->assertOk()
            ->assertJsonPath(
                'data.status',
                Property::STATUS_PENDING_REVIEW
            );

        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'status' => Property::STATUS_PENDING_REVIEW,
        ]);
    }

    public function test_landlord_cannot_submit_already_pending_property(): void
    {
        $landlord = User::factory()->landlord()->create();

        $property = Property::factory()
            ->for($landlord, 'landlord')
            ->create([
                'status' => Property::STATUS_PENDING_REVIEW,
            ]);

        $response = $this->actingAs($landlord, 'sanctum')
            ->postJson(
                "/api/properties/{$property->id}/submit-for-review"
            );

        $response->assertUnprocessable();

        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'status' => Property::STATUS_PENDING_REVIEW,
        ]);
    }

    public function test_landlord_cannot_submit_another_landlords_property(): void
    {
        $owner = User::factory()->landlord()->create();
        $anotherLandlord = User::factory()->landlord()->create();

        $property = Property::factory()
            ->for($owner, 'landlord')
            ->create([
                'status' => Property::STATUS_DRAFT,
            ]);

        $response = $this->actingAs($anotherLandlord, 'sanctum')
            ->postJson(
                "/api/properties/{$property->id}/submit-for-review"
            );

        $response->assertForbidden();

        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'status' => Property::STATUS_DRAFT,
        ]);
    }
}
