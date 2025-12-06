<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade; // Added for completeness, although not strictly needed for a global function

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Use Bootstrap pagination styling
        Paginator::useBootstrap();

        // Custom global helper function to convert USD to Cambodian Riel (KHR)
        if (!function_exists('toRiel')) {
            /**
             * Converts USD to KHR and formats the output.
             * Uses a fixed rate for display approximation ($1 USD = 4,000 KHR).
             *
             * @param float $usdAmount
             * @return string
             */
            function toRiel(float $usdAmount): string
            {
                // Exchange rate used in the original Blade template
                $rielRate = 4000; 
                
                // Calculate and round to a whole number for Riel
                $rielAmount = round($usdAmount * $rielRate); 
                
                // Format with thousands separator and the Riel symbol (៛)
                // Note: The original logic in the Blade template did not include the '៛' symbol 
                // in the function output, but this version adds it for better formatting consistency 
                // with the original code's goal.
                return number_format($rielAmount, 0) . ' ៛'; 
            }
        }
    }
}
