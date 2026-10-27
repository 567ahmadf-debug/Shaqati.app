<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $landlords = User::factory()
            ->landlord()
            ->count(5)
            ->create();

        foreach ($landlords as $landlord) {
            Property::factory()
                ->count(fake()->numberBetween(2, 4))
                ->for($landlord, 'landlord')
                ->create();
        }

        // عقارات معتمدة لاختبار القائمة العامة والفلاتر.
        $approvedLandlords = $landlords->take(3);

        foreach ($approvedLandlords as $landlord) {
            Property::factory()
                ->approved()
                ->count(2)
                ->for($landlord, 'landlord')
                ->create();
        }

        // عقار قيد المراجعة لاختبار عدم ظهوره للعامة.
        Property::factory()
            ->pendingReview()
            ->for($landlords->first(), 'landlord')
            ->create();

        // عقار معتمد لكنه غير متاح حاليًا.
        Property::factory()
            ->approved()
            ->unavailable()
            ->for($landlords->last(), 'landlord')
            ->create();
    }
}
