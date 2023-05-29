<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 **/
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'Category')]
class Category implements \Stringable {

  /**
  * @var integer
  *
  */
  #[ORM\Id]
  #[ORM\Column(type: "integer")]
  #[ORM\GeneratedValue(strategy: "AUTO")]
  protected ?int $id;

  /**
   @var string
   *
   */
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[ORM\Column(type: "string", name: "name", length: 50, nullable: false, unique: false)]
  protected string $name;

  /**
   * Product
  */
  #[ORM\OneToMany(targetEntity: "Product", mappedBy: "category", fetch: "LAZY", cascade: ["persist", "remove"])]
  protected Collection $products;

  /**
  * @var integer
  *
  */
  #[ORM\Version]
  #[ORM\Column(type: 'datetime', name: 'lock_version')]
  protected \DateTime $lockVersion;

  /**
   * Category Constructor
   *
  */
  public function __construct(){
    $this->id = null;
    $this->name = '';
    $this->products = new ArrayCollection();
  }

  /**
   * Get id
   *
   * @return integer 
  */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Set name
   *
   * @param string name
   * @return void
  */
  public function setName(string $name)
  {
    if($this->name != $name){
      $this->name = $name;
    }
  }

  /**
   * Get name
   *
   * @return string
  */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Add new Product to collection
   *
   * @param Product product
  */
  public function addProduct(Product $product)
  {
    if(!$this->products->contains($product) && $product != null){
      $this->products->add($product);
      $product->setCategory($this);
    }
  }

  /**
   * Remove a item from collection
   *
   * @param Product product
  */
  public function removeProduct(Product $product)
  {
    if($this->products->contains($product)){
      $this->products->remove($product);
      $product->setCategory(null);
    }
  }

  /**
   * Set products collection
   *
   * @param Set<Product> $product
   * @return void
  */
  public function setProducts(ArrayCollection $products)
  {
    if($this->products != $products){
      $this->products = $products;

      foreach($products as $product) {
        $product->setCategory($this);
      }
    }
  }

  /**
   * Get products
   *
   * @return Set<Product>
  */
  public function getProducts(): Collection
  {
    return $this->products;
  }

  /**
   * toJson()
   * @return string
  */
  public function toJson(){
    $obj = $this->toObject();
    return json_encode($obj);
  }

  /**
   * toObject()
   * @return stdClass object
  */
  public function toObject(){

    $obj = new \stdClass();
    $obj->id = $this->id;
    $obj->name = $this->name;

    return $obj;
  }

  /**
   * __toString()
   * @return string
  */
  public function __toString(){
    return $this->name;
  }

}
