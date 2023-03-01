<?php

namespace RoboGen\Wireform\Providers;

use Illuminate\Support\ServiceProvider;

class WireformServiceProvider extends ServiceProvider {

    public function boot() {
        $this->loadViewsFrom(__DIR__ . '/../../views', 'wireform');
    }
}
