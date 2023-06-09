<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * User
 **/
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: "name")]
#[UniqueEntity(fields: "email")]
#[ORM\Table(name: "User",
   indexes: [
     new ORM\Index(
       columns: ["name"]
     ),
     new ORM\Index(
       columns: ["email"]
     )
   ]
 )]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable {

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
  #[ORM\Column(type: "string", name: "name", length: 255, nullable: false, unique: true)]
  protected string $name;

  /**
   @var string
   *
   */
  #[Assert\NotBlank]
  #[ORM\Column(type: "string", name: "email", length: 255, nullable: false, unique: true)]
  protected string $email;

  /**
   @var string
   *
   */
  #[Ignore]
  #[Assert\NotBlank]
  #[ORM\Column(type: "string", name: "password", length: 255, nullable: false, unique: false)]
  protected string $password;

  /**
   * Group
  */
  #[Ignore]
  #[ORM\ManyToMany(targetEntity: "Group", inversedBy: "users", fetch: "LAZY")]
  #[ORM\JoinTable(
    name: "UserGroup",
    joinColumns: 
      new ORM\JoinColumn(name: "user", referencedColumnName: "id"),
    inverseJoinColumns:
      new ORM\JoinColumn(name: "group", referencedColumnName: "id")
  )]
  protected ?Collection $groups;

  /**
  * @var integer
  *
  */
  #[ORM\Version]
  #[ORM\Column(type: 'datetime', name: 'lock_version')]
  public \DateTime $lockVersion;

  /**
   * User Constructor
   *
  */
  public function __construct(){
    $this->id = null;
    $this->name = '';
    $this->email = '';
    $this->password = '';
    $this->groups = new ArrayCollection();
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
   * Set email
   *
   * @param string email
   * @return void
  */
  public function setEmail(string $email): void
  {
    if($this->email != $email){
      $parts = explode('@',$email);
      $this->setName($parts[0]);
      $this->email = $email;
    }
  }

  /**
   * Get email
   *
   * @return string
  */
  public function getEmail(): string
  {
    return $this->email;
  }

  /**
   * Set password
   *
   * @param string password
   * @return void
  */
  public function setPassword(string $password)
  {
    if($this->password != $password){
      $this->password = $password;
    }
  }

  /**
   * Get password
   *
   * @return string
  */
  public function getPassword(): string
  {
    return $this->password;
  }

  /**
   * Add new Group to collection
   *
   * @param Group group
  */
  public function addGroup(Group $group)
  {
    if(!$this->groups->contains($group) && $group != null){
      $this->groups->add($group);
    }
  }

  /**
   * Remove a item from collection
   *
   * @param Group group
  */
  public function removeGroup(Group $group)
  {
    if($this->groups->contains($group)){
      $this->groups->remove($group);
    }
  }

  /**
   * Set groups collection
   *
   * @param Set<Group> $group
   * @return void
  */
  public function setGroups(ArrayCollection $groups)
  {
    if($this->groups != $groups){
      $this->groups = $groups;
    }
  }

  /**
   * Get groups
   *
   * @return Set<Group>
  */
  public function getGroups(): Collection
  {
    return $this->groups;
  }

  /**
   * toObject()
   * @return stdClass object
  */
  public function toObject(){

    $obj = new \stdClass();
    $obj->id = $this->id;
    $obj->name = $this->name;
    $obj->email = $this->email;
    $obj->password = $this->password;

    return $obj;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string
  {
      return (string) $this->email;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {

      // guarantee every user at least has ROLE_USER
      $roles[] = 'ROLE_USER';
      foreach ($this->groups as $role) {
        $roles[] = $role->getName();
      }

      return array_unique($roles);
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
      // If you store any temporary, sensitive data on the user, clear it here
      // $this->plainPassword = null;
  }

  /**
   * __toString()
   * @return string
  */
  public function __toString(){
    return $this->name;
  }

}
