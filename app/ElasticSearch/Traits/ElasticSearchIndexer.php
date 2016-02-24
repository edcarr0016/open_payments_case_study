<?php namespace App\ElasticSearch\Traits;

use App;
use App\ElasticSearch\ElasticSearch;
use App\ElasticSearch\Interfaces\Stretchable;

trait ElasticSearchIndexer{

	protected static function boot(){
		parent::boot();

		static::created(function ($model){
			if($model instanceof Stretchable){
				$elastic = App::make('ElasticSearch');
				$elastic->upsert($model);
			}
		});

		static::updating(function ($model){
			if($model instanceof Stretchable){
				$elastic = App::make('ElasticSearch');
				$elastic->delete($model);
			}
		});

		static::updated(function ($model){
			if($model instanceof Stretchable){
				$elastic = App::make('ElasticSearch');
				$elastic->upsert($model);
			}
		});

		static::deleted(function ($model){
			if($model instanceof Stretchable){
				$elastic = App::make('ElasticSearch');
				$elastic->delete($model);
			}
		});
	}
}