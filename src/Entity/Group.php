<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Group
 **/
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: 'Group')]
class Group implements \Stringable {

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
  #[Assert\NotBlank]
  #[ORM\Column(type: "string", name: "name", length: 255, nullable: false, unique: false)]
  protected string $name;

  /**
   * User
  */
  #[ORM\ManyToMany(targetEntity: "User", mappedBy: "groups", fetch: "LAZY", cascade: ["persist", "remove"])]
  protected ?Collection $users;

  /**
  * @var integer
  *
  */
  #[ORM\Version]
  #[ORM\Column(type: 'datetime', name: 'lock_version')]
  protected \DateTime $lockVersion;

  /**
   * Group Constructor
   *
  */
  public function __construct(){
    $this->id = null;
    $this->name = '';
    $this->users = new ArrayCollection();
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
   * Add new User to collection
   *
   * @param User user
  */
  public function addUser(User $user)
  {
    if(!$this->users->contains($user) && $user != null){
      $this->users->add($user);
      $user->addGroup($this);
    }
  }

  /**
   * Remove a item from collection
   *
   * @param User user
  */
  public function removeUser(User $user)
  {
    if($this->users->contains($user)){
      $this->users->remove($user);
      $user->removeGroup(this);
    }
  }

  /**
   * Set users collection
   *
   * @param Set<User> $user
   * @return void
  */
  public function setUsers(ArrayCollection $users)
  {
    if($this->users != $users){
      $this->users = $users;

      foreach($users as $user) {
        $user->addGroup($this);
      }
    }
  }

  /**
   * Get users
   *
   * @return Set<User>
  */
  public function getUsers(): Collection
  {
    return $this->users;
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
