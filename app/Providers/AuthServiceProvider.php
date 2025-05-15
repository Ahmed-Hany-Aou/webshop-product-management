<?php

// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Product; // Import the Product model
use App\Policies\ProductPolicy; // Import the ProductPolicy

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class, // Correct mapping
        // 'App\Models\Model' => 'App\Policies\ModelPolicy', // You can comment out or remove this line if you don't have other policies
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // You can define Gates here if needed.  
    }
}
