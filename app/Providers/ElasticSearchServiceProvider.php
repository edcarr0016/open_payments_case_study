<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Elasticsearch\ClientBuilder;

use App\ElasticSearch\ElasticSearch;

class ElasticSearchServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('ElasticSearch', function(){
            return new ElasticSearch(ClientBuilder::fromConfig([]));
        });
    }
}
