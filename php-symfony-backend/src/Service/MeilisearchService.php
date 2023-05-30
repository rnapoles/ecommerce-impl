<?php

declare(strict_types=1);

namespace App\Service;

use App\Contracts\ISearchService;
use App\DTO\PaginateResponseObject;
use App\DTO\Product\CreateProduct;
use Meilisearch\Client;
use Meilisearch\Exceptions\ApiException;

class MeilisearchService implements ISearchService
{

  private Client $client;

  private string $indexName = 'e-commerce';

  private array $filterables = [
    'id', 'name', 'price', 'unitsInStock', 'category', 'price'
    , 'valoration', 'tags'
  ];

  private array $searchables = [
    'id', 'name', 'price', 'unitsInStock', 'category', 'price', 'valoration',
    'tags', 'description', 'aditionalInfo', 'price', 'unitsInStock',
    'category', 'price'
  ];

  function __construct(){
    $this->client = new Client('http://127.0.0.1:9700');
  }

  public function setup(): void
  {
    
    $client = $this->client;

    if(!$this->indexExist()){

      $response = $client->createIndex($this->indexName, ['primaryKey' => 'id']);
      $taskId = $response['taskUid'];

      $statusTypes = ['failed', 'canceled', 'succeeded'];
      while(true){
        
        $task = $client->getTask($taskId);
        $status = $task['status'];
        if(in_array($status, $statusTypes)){
          break;
        }
        
      }

      $index = $client->index($this->indexName);
      $index->updateFilterableAttributes($this->filterables);
      $index->updateSearchableAttributes($this->searchables);
      //$index->updateSortableAttributes(['id']);
      
    }

  }

  public function down(): void
  {
    $client->deleteIndex($this->indexName);
  }

  public function indexProduct(CreateProduct $product): void
  {
      //['id' => 1,  'title' => 'Carol', 'genres' => ['Romance', 'Drama']],
      $index = $this->client->index($this->indexName);
      
      $doc = [
        'id' => $product->id,
        'name' => $product->name,
        'price' =>  $product->price,
        'unitsInStock' =>  $product->unitsInStock,
        'category' =>  $product->category,
        'description' => $product->description,
        'valoration' =>  $product->valoration,
        'aditionalInfo' =>  $product->aditionalInfo,
        'sku' => $product->sku,
        'tags' =>  $product->tags,
        'images' => $product->images 
      ];
      
      $index->addDocuments([$doc]);
  }

  public function deleteProduct(int $id): void
  {
    $this
      ->client
      ->index('movies')
      ->deleteDocument($id)
    ;
  }

  public function search(string $query, int $start = 0, int $totalItems = 10): PaginateResponseObject
  {
    

    $isSimpleQuery = true;
    $keywords = [
      '=', '!=', '>', '>=', '<',
      '<=', 'TO', 'EXISTS', 'IN',
      'NOT', 'AND', 'OR'
      //'NOT IN', 'NOT EXISTS'
    ];


    $split = explode(' ', $query);
    if(count($split) >= 3){
      $split = array_map('strtoupper', $split);
      $filterables = array_map('strtoupper', $this->filterables);
      $intersect1 = array_intersect($split, $keywords);
      $intersect2 = array_intersect($split, $filterables);
      $intersect = [...$intersect1, ...$intersect2];
      $isSimpleQuery = count($intersect) < 2;
    }

    $index = $this->client->index($this->indexName);
    
    if($isSimpleQuery){

      $response = $index->search(
        $query,
        [
          'offset' => $start, 
          'limit' => $totalItems
        ]
      );
    } else {
      $response = $index->search(
        '',
        [
          'offset' => $start, 
          'limit' => $totalItems,
          'filter' => [$query]
        ]
      );

    }

    $result = new PaginateResponseObject();
    $result->payload = $response->getHits();
    $result->total = $response->getEstimatedTotalHits();
    
    return $result;
  }

  private function indexExist(): bool
  {
    try {
      $response = $this->client->index($this->indexName)->fetchRawInfo();
      
      return true;
    } catch (\ApiException $ex){
      if($ex->getCode() === 404){
        return false;
      } else {
        throw $ex;
      }
    }
  }

}
