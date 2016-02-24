<?php namespace App\ElasticSearch;

use App\ElasticSearch\Exceptions\ElasticsearchServerUnavailableException;
use App\ElasticSearch\Interfaces\Stretchable;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Curl\CouldNotConnectToHost;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Elasticsearch\Common\Exceptions\ElasticsearchException;

final class ElasticSearch{

	const ID = 'id';
	const INDEX = 'index';
	const TYPE = 'type';
	const BODY = 'body';
	const QUERY = 'query';
	const MAP = 'mappings';
	const SETTING = 'settings';

	private $client;

	public function __construct(Client $client){
		$this->client = $client;

		try{
			$this->client->ping();
		}catch(CouldNotConnectToHost $e){
			//Need to start server;
			echo "The Elasticsearch Server needs to be started";
		}catch(NoNodesAvailableException $e){
			echo "ElasticSearch functionality is not enabled";
		}
	}

	public function getClient(){
		return $this->client;
	}

	public function upsert(Stretchable $item){
		return $this->client->index($this->arrangeParams($item));
	}

	public function delete(Stretchable $item){
		$params = $this->arrangeParams($item, false);
		return $this->client->delete($params);
	}

	public function search(Stretchable $item, $query){
		$params = $this->arrangeParams($item, false, false);
		$search = $item->getSearchParams();
		// This will return the search array in a one dimensional array. Key depth
		// will be shown using . notation Ex: 'match_phrase_prefix.detailed_name.query'

		$flattened = array_dot($search);
		//For each item in the flattened array, I want to check if the Key string contains the word "query"
		foreach ($flattened as $key => $value) {
			if(str_contains($key, '.'.self::QUERY) && is_null($value)){
				//Where I find the word "query" I go back to the original search array, and set it's value
				//to the query which was passed to this function.
				array_set($search, $key, $query);
			}
		}
		$params[self::BODY] = [self::QUERY => $search];
		return $this->client->search($params);
	}

	private function arrangeParams(Stretchable $item, $includeBody = true, $includeId = true){
		$params[self::INDEX] = $item->getIndexName();
		$params[self::TYPE] = $item->getTypeName();
		!$includeId ? :$params[self::ID] = $item->id;
		!$includeBody ? :$params[self::BODY] = $item->getData();

		return $params;
	}
}