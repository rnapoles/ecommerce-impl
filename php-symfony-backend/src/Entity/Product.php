<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * Product
 **/
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[UniqueEntity("name")]
#[UniqueEntity("sku")]
#[ORM\Table(name: "Product",
   indexes: [
     new ORM\Index(
       columns: ["name"]
     ),
     new ORM\Index(
       columns: ["sku"]
     )
   ]
 )]
class Product implements \Stringable {

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
  #[ORM\Column(type: "string", name: "name", length: 50, nullable: false, unique: true)]
  protected string $name;

  /**
   @var float
   *
   */
  #[ORM\Column(type: "decimal", name: "price", precision: 17, scale: 17, nullable: false, unique: false)]
  protected float $price;

  /**
   @var int
   *
   */
  #[ORM\Column(type: "integer", name: "unit_in_stock", nullable: false, unique: false)]
  protected int $unitsInStock;

  /**
   * Category
  */
  #[Assert\NotNull]
  #[ORM\ManyToOne(targetEntity: "Category", inversedBy: "products", fetch: "EAGER")]
  protected ?Category $category = null;

  /**
   @var string
   *
   */
  #[Assert\NotNull]
  #[ORM\Column(type: "simple_array", name: "tags", length: 500, nullable: false, unique: false)]
  protected array $tags;

  /**
   @var string
   *
   */
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[ORM\Column(type: "text", name: "description", nullable: false, unique: false)]
  protected string $description;

  /**
   @var string
   *
   */
  #[ORM\Column(type: "text", name: "aditional_info", nullable: true, unique: false)]
  protected ?string $aditionalInfo;

  /**
   @var int
   *
   */
  #[ORM\Column(type: "integer", name: "valoration", nullable: false, unique: false)]
  protected int $valoration;

  /**
   @var string
   *
   */
  #[Assert\NotNull]
  #[Assert\NotBlank]
  #[ORM\Column(type: "string", name: "sku", length: 50, nullable: false, unique: true)]
  protected string $sku;

  /**
   @var string
   *
   */
  #[Assert\NotNull]
  #[ORM\Column(type: "simple_array", name: "image", nullable: false, unique: false)]
  protected array $images;

  /**
   * Sale
  */
  #[Ignore]
  #[ORM\OneToMany(targetEntity: "Sale", mappedBy: "product", fetch: "LAZY", cascade: ["persist", "remove"])]
  protected Collection $sales;

  /**
  * @var integer
  *
  */
  #[ORM\Version]
  #[ORM\Column(type: 'datetime', name: 'lock_version')]
  public \DateTime $lockVersion;

  /**
   * Product Constructor
   *
  */
  public function __construct(){
    $this->id = null;
    $this->name = '';
    $this->price = 0;
    $this->unitsInStock = 0;
    $this->tags = [];
    $this->description = '';
    $this->aditionalInfo = '';
    $this->valoration = 0;
    $this->sku = Uuid::v4()->toRfc4122();
    $this->images = [];
    $this->sales = new ArrayCollection();
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
   * Set price
   *
   * @param float price
   * @return void
  */
  public function setPrice(float $price)
  {
    if($this->price != $price){
      $this->price = $price;
    }
  }

  /**
   * Get price
   *
   * @return float
  */
  public function getPrice(): float
  {
    return $this->price;
  }

  /**
   * Set unitsInStock
   *
   * @param int unitsInStock
   * @return void
  */
  public function setUnitsInStock(int $unitsInStock)
  {
    if($this->unitsInStock != $unitsInStock){
      $this->unitsInStock = $unitsInStock;
    }
  }

  /**
   * Get unitsInStock
   *
   * @return int
  */
  public function getUnitsInStock(): int
  {
    return $this->unitsInStock;
  }

  /**
   * Set category
   *
   * @param Category $category
   * @return void
  */
  public function setCategory(Category $category)
  {
    if($this->category != $category && $category != null){
      $this->category = $category;
      $this->category->addProduct($this);
    }
  }

  /**
   * Get category
   *
   * @return Category
  */
  public function getCategory(): Category
  {
    return $this->category;
  }

  /**
   * Set tags
   *
   * @param array tags
   * @return void
  */
  public function setTags(array $tags)
  {
    if($this->tags != $tags){
      $this->tags = $tags;
    }
  }

  /**
   * Get tags
   *
   * @return array
  */
  public function getTags(): array
  {
    return $this->tags;
  }

  /**
   * Set description
   *
   * @param string description
   * @return void
  */
  public function setDescription(string $description)
  {
    if($this->description != $description){
      $this->description = $description;
    }
  }

  /**
   * Get description
   *
   * @return string
  */
  public function getDescription(): string
  {
    return $this->description;
  }

  /**
   * Set aditionalInfo
   *
   * @param string aditionalInfo
   * @return void
  */
  public function setAditionalInfo(string $aditionalInfo)
  {
    if($this->aditionalInfo != $aditionalInfo){
      $this->aditionalInfo = $aditionalInfo;
    }
  }

  /**
   * Get aditionalInfo
   *
   * @return string
  */
  public function getAditionalInfo(): string
  {
    return $this->aditionalInfo;
  }

  /**
   * Set valoration
   *
   * @param int valoration
   * @return void
  */
  public function setValoration(int $valoration)
  {
    if($this->valoration != $valoration){
      $this->valoration = $valoration;
    }
  }

  /**
   * Get valoration
   *
   * @return int
  */
  public function getValoration(): int
  {
    return $this->valoration;
  }

  /**
   * Set sku
   *
   * @param string sku
   * @return void
  */
  public function setSku(string $sku)
  {
    if($this->sku != $sku){
      $this->sku = $sku;
    }
  }

  /**
   * Get sku
   *
   * @return string
  */
  public function getSku(): string
  {
    return $this->sku;
  }

  /**
   * Set images
   *
   * @param array images
   * @return void
  */
  public function setImages(array $images)
  {
    if($this->images != $images){
      $this->images = $images;
    }
  }

  /**
   * Get images
   *
   * @return array
  */
  public function getImages(): array
  {
    return $this->images;
  }

  /**
   * Add new Sale to collection
   *
   * @param Sale sale
  */
  public function addSale(Sale $sale)
  {
    if(!$this->sales->contains($sale) && $sale != null){
      $this->sales->add($sale);
      $sale->setProduct($this);
    }
  }

  /**
   * Remove a item from collection
   *
   * @param Sale sale
  */
  public function removeSale(Sale $sale)
  {
    if($this->sales->contains($sale)){
      $this->sales->remove($sale);
      $sale->setProduct(null);
    }
  }

  /**
   * Set sales collection
   *
   * @param Set<Sale> $sale
   * @return void
  */
  public function setSales(ArrayCollection $sales)
  {
    if($this->sales != $sales){
      $this->sales = $sales;

      foreach($sales as $sale) {
        $sale->setProduct($this);
      }
    }
  }

  /**
   * Get sales
   *
   * @return Set<Sale>
  */
  public function getSales(): Collection
  {
    return $this->sales;
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
    $obj->price = $this->price;
    $obj->unitsInStock = $this->unitsInStock;

    if($this->category != null){
      $obj->category = (string) $this->category;
      $obj->idCategory = $this->category->getId();
    } else {
      $obj->category = '-';
      $obj->idCategory = -1;
    }

    $obj->tags = $this->tags;
    $obj->description = $this->description;
    $obj->aditionalInfo = $this->aditionalInfo;
    $obj->valoration = $this->valoration;
    $obj->sku = $this->sku;
    $obj->images = $this->images;

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
