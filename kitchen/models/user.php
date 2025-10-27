<?php

declare(strict_types=1);

class User
{

    //Private properties
    private int $id;
    private string $email;
    private string $name;
    private string $password;

    private int $grupo_id;
    private ?string $phone;
    private ?string $image;

  
    private ?string $surname;
    private ?string $address;
    private ?string $city;
    private ?string $cp;
    private ?string $country;
    private ?string $province;

    // Constructor
    public function __construct(
        int $id = 0,
        string $email = "",
        string $name = "",
        string $password = "",
        int $grupo_id = 0,
        string $phone = "",
        string $image = null,
        string $surname = "",
        string $address = "",
        string $city = "",
        string $cp = "",
        string $country = "",
        string $province = ""
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->grupo_id = $grupo_id;
        $this->phone = $phone;
        $this->image = $image;
        $this->surname = $surname;
        $this->address = $address;
        $this->city = $city;
        $this->cp = $cp;
        $this->country = $country;
        $this->province = $province;
    }

    // Get methods
    public function getId(): int
    {
        return $this->id;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getGrupo_id(): int
    {
        return $this->grupo_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }

    // Métodos getter y setter para los campos adicionales
    public function getSurname(): ?string
    {
        return $this->surname;
    }
    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }


    public function getProvince(): ?string
    {
        return $this->province;
    }

   
    
   
   
  

    // Set methods
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function setGrupo_id(int $grupo_id): void
    {
        $this->grupo_id = $grupo_id;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }
    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }
    
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }
    
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }
    
    public function setCp(?string $cp): void
    {
        $this->cp = $cp;
    }
    
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }
    
    public function setProvince(?string $province): void
    {
        $this->province = $province;
    }
    //ToString
    public function __toString(): string
    {
        return $this->getId() . ' ' . $this->getEmail() . ' ' . $this->getPassword() . ' ' . $this->getGrupo_id(). ' ' . $this->getName(). ' ' . $this->getPhone();
    }

    // Método para validar los campos
    public function validate(): array {
        $errors = [];

        // Check email
        if(empty(trim($this->email))) {
            $errors['email'] = 'Email is required';
        }

        // Check password
        if(empty($this->password)) {
            $errors['password'] = 'Password is required';
        } elseif(strlen($this->password) < 8){
            $errors['password'] = 'Password must be at least 8 characters long';
        }

        return $errors;
    }

}
