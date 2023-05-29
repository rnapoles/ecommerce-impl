<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

/**
 * Sale
 **/
#[ORM\Entity(repositoryClass: SaleRepository::class)]
#[ORM\Table(name: 'Sale')]
class Sale implements \Stringable {

  /**
  * @var integer
  *
  */
  #[ORM\Id]
  #[ORM\Column(type: "integer")]
  #[ORM\GeneratedValue(strategy: "AUTO")]
  protected ?int $id;

  /**
   @var float
   *
   */
  #[ORM\Column(type: "decimal", name: "price", precision: 10, scale: 2, nullable: false, unique: false)]
  protected float $price;

  /**
   * Product
  */
  #[ORM\ManyToOne(targetEntity: "Product", inversedBy: "sales", fetch: "EAGER")]
  protected Product $product;

  /**
   * User
  */
  #[ORM\ManyToOne(targetEntity: "User", fetch: "EAGER")]
  protected ?User $user;

  /**
   @var DateTime
   *
   */
  #[ORM\Column(type: "datetime", name: "order_date", nullable: false, unique: false)]
  protected DateTime $orderDate;

  /**
  * @var integer
  *
  */
  #[ORM\Version]
  #[ORM\Column(type: 'datetime', name: 'lock_version')]
  protected \DateTime $lockVersion;

  /**
   * Sale Constructor
   *
  */
  public function __construct(){
    $this->id = null;
    $this->price = 0;
    $this->product = null;
    $this->user = null;
    $this->orderDate = new \DateTime('NOW');
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
   * Set product
   *
   * @param Product $product
   * @return void
  */
  public function setProduct(Product $product)
  {
    if($this->product != $product && $product != null){
      $this->product = $product;
      $this->product->addSale($this);
    }
  }

  /**
   * Get product
   *
   * @return Product
  */
  public function getProduct(): Product
  {
    return $this->product;
  }

  /**
   * Set user
   *
   * @param User $user
   * @return void
  */
  public function setUser(User $user = null)
  {
    if($this->user != $user && $user != null){
      $this->user = $user;
    }
  }

  /**
   * Get user
   *
   * @return User
  */
  public function getUser(): ?User
  {
    return $this->user;
  }

  /**
   * Set orderDate
   *
   * @param DateTime orderDate
   * @return void
  */
  public function setOrderDate(DateTime $orderDate)
  {
    if($this->orderDate != $orderDate){
      $this->orderDate = $orderDate;
    }
  }

  /**
   * Get orderDate
   *
   * @return DateTime
  */
  public function getOrderDate(): DateTime
  {
    return $this->orderDate;
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
    $obj->price = $this->price;

    if($this->product != null){
      $obj->product = (string) $this->product;
      $obj->idProduct = $this->product->getId();
    } else {
      $obj->product = '-';
      $obj->idProduct = -1;
    }


    if($this->user != null){
      $obj->user = (string) $this->user;
      $obj->idUser = $this->user->getId();
    } else {
      $obj->user = '-';
      $obj->idUser = -1;
    }

    $obj->orderDate = $this->orderDate->format('d/m/Y g:i:s');

    return $obj;
  }

  /**
   * __toString()
   * @return string
  */
  public function __toString(){
    return (String) $this->price;
  }

}
