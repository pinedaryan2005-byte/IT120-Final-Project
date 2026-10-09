<?php
class UserModel {
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }


    public function registerCustomer($data) {
        $sql = "INSERT INTO users (role_id, username, password_hash, first_name, middle_name, last_name, birthdate, gender, email, phone_number, address) 
                VALUES ((SELECT role_id FROM roles WHERE role_name = 'Customer'), :username, :password, :first_name, :middle_name, :last_name, :birthdate, :gender, :email, :phone_number, :address)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':username'     => $data['username'],
            ':password'     => password_hash($data['password'], PASSWORD_DEFAULT),
            ':first_name'   => $data['first_name'],
            ':middle_name'  => $data['middle_name'] ?? null,
            ':last_name'    => $data['last_name'],
            ':birthdate'    => $data['birthdate'],
            ':gender'       => $data['gender'],
            ':email'        => $data['email'],
            ':phone_number' => $data['phone_number'],
            ':address'      => $data['address']
        ]);
    }

    public function createEmployeeAccount($data, $roleName) {
        $sql = "INSERT INTO users (role_id, username, password_hash, first_name, last_name, birthdate, gender, email, phone_number, address, department) 
                VALUES ((SELECT role_id FROM roles WHERE role_name = :role_name), :username, :password, :first_name, :last_name, :birthdate, :gender, :email, :phone_number, :address, :department)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':role_name'    => $roleName,
            ':username'     => $data['username'],
            ':password'     => password_hash($data['password'], PASSWORD_DEFAULT),
            ':first_name'   => $data['first_name'],
            ':last_name'    => $data['last_name'],
            ':birthdate'    => $data['birthdate'],
            ':gender'       => $data['gender'],
            ':email'        => $data['email'],
            ':phone_number' => $data['phone_number'],
            ':address'      => $data['address'],
            ':department'   => $data['department']
        ]);
    }


    public function authenticate($username, $password) {
        $sql = "SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.role_id WHERE u.username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }
}
?>