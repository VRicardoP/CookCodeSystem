<?php

class RecuperacionPass {
    private int $id;
    private string $email;
    private string $token;

    // Constructor
    public function __construct(int $id = 0, string $email = "", string $token = "") {
        $this->id = $id;
        $this->email = $email;
        $this->token = $token;
    }

    // Getter para ID
    public function getId(): int {
        return $this->id;
    }

    // Setter para ID
    public function setId(int $id): void {
        $this->id = $id;
    }

    // Getter para email
    public function getEmail(): string {
        return $this->email;
    }

    // Setter para email
    public function setEmail(string $email): void {
        $this->email = $email;
    }

    // Getter para token
    public function getToken(): string {
        return $this->token;
    }

    // Setter para token
    public function setToken(string $token): void {
        $this->token = $token;
    }
}

?>
