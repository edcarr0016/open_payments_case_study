<?php namespace App\ElasticSearch\Interfaces;

interface Stretchable {

	/**
	 * The class which implements this interface should define
	 * the index name that it will be using in elasticsearch
	 * @return String The name of the index the class is using
	 *
	 */
	public function getIndexName();


	/**
	 * The class which implements this interface should define
	 * the type name that it will be using in elasticsearch
	 * @return String The name of the type the class is using
	 *
	 */
	public function getTypeName();


	/**
	 * Should return the mapping for the fields used in the class.
	 * @return Array[String] An array of strings containing the Elasticsearch mapping for fields used by class
	 */
	public function getMappings();


	/**
	 * Return the data which will be indexed
	 * @return Array[String]
	 */
	public function getData();


	/**
	 * @return Array[String] Should return an array with all the search query DSL set
	 */
	public function getSearchParams();
}