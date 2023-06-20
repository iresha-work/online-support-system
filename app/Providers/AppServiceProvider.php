<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * @var Illuminate\Support\Str
     */
    public const VALIDATION_ERROR_TITLE = "Validation error occured";

    /**
     * @var Illuminate\Support\Str
     */
    public const ERROR_TITLE =  "The process cannot be completed. (error occured)";

    /**
     * @var Illuminate\Support\Str
     */
    public const TICKET_OK_TITLE =  "Ticket submitted.Please check given email address to more info.";

    /**
     * @var Illuminate\Support\Str
     */
    public const REPLY_OK_TITLE =  "Reply submitted.Please check given email address to more info.";

    /**
     * ticket status array
     */
    public const TICKET_STATUS = ['Pending', 'Process'  , 'Solved' , 'Closed'];
   

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
