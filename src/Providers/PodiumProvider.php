<?php

namespace Juanparati\Podium\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Juanparati\Podium\Auths\AppAuth;
use Juanparati\Podium\Podium;

/**
 * Podium service provider
 */
class PodiumProvider extends ServiceProvider
{

    /**
     * Bootstrap service.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/podium.php' => config_path('podium.php'),
        ]);
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/podium.php', 'podium'
        );


        $this->app->bind(Podium::class, function($app, array $params) {
            $podiumConf = $app->make('config')->get('podium');
            $session = $params['session'] ?? 'default';
            $podiumSession = is_array($session) ? $session : $podiumConf['sessions'][$session];

            $podium =  new Podium(
                $session,
                $podiumSession['client_id'],
                $podiumSession['client_secret'],
                Cache::store($podiumConf['cache']['store'])->getStore(),
                Log::channel($podiumConf['log']['channel'])
            );

            $auth = match ($podiumSession['grant_type']) {
                // @ToDo: Implement other authentication methods
                default => new AppAuth($podiumSession['app_id'], $podiumSession['app_token'])
            };

            $podium->authenticate($auth);

            return $podium;
        });
    }

}