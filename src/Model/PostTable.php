<?php
namespace App\Model;
use Framework\Database\PaginatedQuery;
use Framework\Database\Table;
use Framework\Database\Query;
use Pagerfanta\Pagerfanta;
use App\Model\Entity\Post;


class PostTable extends Table {

protected $entity = Post::class;

protected $table = 'post';



public function findAll(){
  $category = new CategoryTable($this->pdo);

  return $this->makeQuery()->join($category->getTable() . ' as l', 'l.id = p.location_id')
    ->select('p.* , l.name_locality , l.id as l_id')
    ->order('date DESC')
    ->order('time DESC');

}

public function findPublic(): Query{

  return $this->findAll()
    ->where('p.visible = 1');

}

public function findAllByAjax(array $params){

  $statement = $this->pdo
    ->prepare("SELECT id,slug,latitude,longitude,location_id,name_place FROM {$this->table} WHERE  location_id = :locationId AND visible = 1 ");
  $statement->execute($params);
  $results = $statement->fetchAll(\PDO::FETCH_NUM);

  $list = [];
  foreach ($results as $result)
  {

    $list[$result[0]]= [
      'id' => $result[0],
      'slug' => $result[1],
      'latitude' => $result[2],
      'longitude' => $result[3],
      'locationId' => $result[4],
      'namePlace' => $result[5]
      ];
  }

  return $list;
  }
  public  $latitude;

  public  $longitude;

  public  $visible;

  public  $locationId;

  public  $namePlace;



}
