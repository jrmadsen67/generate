<?php

namespace jrmadsen67\MahanaGenerators;

use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {

        $source = realpath(__DIR__ . '/../config/mahana-generators.php');

        // Check if the application is a Laravel OR Lumen instance to properly merge the configuration file.
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                $source => config_path('mahana-generators.php'),
            ], 'mahana-generators');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('mahana-generators');
        }

        $this->mergeConfigFrom($source, 'mahana-generators');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerModelGenerator();
	}

	/**
	 * Register the make:model generator.
	 */
	private function registerModelGenerator() {
		$this->app->singleton('command.jrmadsen67.model', function ($app) {
			return $app['jrmadsen67\MahanaGenerators\Commands\ModelMakeCommand'];
		});

		$this->commands('command.jrmadsen67.model');
	}

}
