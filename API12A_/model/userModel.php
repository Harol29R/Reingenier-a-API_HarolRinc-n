<?php
require_once "ConDB.php";

class UserModel {

    private static function getConnection() {
        return Connection::connection();
    }

    private static function prepareQuery($query) {
        return self::getConnection()->prepare($query);
    }

    static public function createUser($data) {
        $cantMail = self::getMail($data["use_mail"]);

        if ($cantMail == 0) {
            $query = "INSERT INTO users (use_id, use_mail, use_pss, use_dateCreate, us_identifier, us_key, us_status) 
                      VALUES (NULL, :use_mail, :use_pss, :use_dateCreate, :us_identifier, :us_key, :us_status)";
            $statement = self::prepareQuery($query);
            $statement->bindParam(":use_mail", $data["use_mail"], PDO::PARAM_STR);
            $statement->bindParam(":use_pss", $data["use_pss"], PDO::PARAM_STR);
            $statement->bindParam(":use_dateCreate", $data["use_dateCreate"], PDO::PARAM_STR);
            $statement->bindParam(":us_identifier", $data["us_identifier"], PDO::PARAM_STR);
            $statement->bindParam(":us_key", $data["us_key"], PDO::PARAM_STR);
            $statement->bindParam(":us_status", $data["us_status"], PDO::PARAM_STR);
            $message = $statement->execute() ? "ok" : self::getConnection()->errorInfo();
            $statement->closeCursor();

            return $message;
        } else {
            return "El usuario ya existe";
        }
    }

    static private function getMail($mail) {
        $query = "SELECT use_mail FROM users WHERE use_mail = :mail";
        $statement = self::prepareQuery($query);
        $statement->bindParam(":mail", $mail, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->rowCount();

        return $result;
    }

    static public function updateUser($data) {
        $query = "UPDATE users 
                  SET use_mail = :use_mail, use_pss = :use_pss, use_dateCreate = :use_dateCreate, 
                      us_identifier = :us_identifier, us_key = :us_key, us_status = :us_status
                  WHERE use_id = :use_id";
        $statement = self::prepareQuery($query);
        $statement->bindParam(":use_mail", $data["use_mail"], PDO::PARAM_STR);
        $statement->bindParam(":use_pss", $data["use_pss"], PDO::PARAM_STR);
        $statement->bindParam(":use_dateCreate", $data["use_dateCreate"], PDO::PARAM_STR);
        $statement->bindParam(":us_identifier", $data["us_identifier"], PDO::PARAM_STR);
        $statement->bindParam(":us_key", $data["us_key"], PDO::PARAM_STR);
        $statement->bindParam(":us_status", $data["us_status"], PDO::PARAM_STR);
        $statement->bindParam(":use_id", $data["use_id"], PDO::PARAM_INT);
        $message = $statement->execute() ? "ok" : self::getConnection()->errorInfo();
        $statement->closeCursor();

        return $message;
    }

    static public function deleteUser($userId) {
        $query = "DELETE FROM users WHERE use_id = :use_id";
        $statement = self::prepareQuery($query);
        $statement->bindParam(":use_id", $userId, PDO::PARAM_INT);
        $message = $statement->execute() ? "ok" : self::getConnection()->errorInfo();
        $statement->closeCursor();

        return $message;
    }

    static public function activateUser($userId) {
        $query = "UPDATE users SET us_status = '1' WHERE use_id = :use_id";
        $statement = self::prepareQuery($query);
        $statement->bindParam(":use_id", $userId, PDO::PARAM_INT);
        $message = $statement->execute() ? "ok" : self::getConnection()->errorInfo();
        $statement->closeCursor();

        return $message;
    }

    static public function getUsers($parametro) {
        $param = is_numeric($parametro) ? $parametro : 0;
        $query = "SELECT use_id, use_mail, use_dateCreate FROM users";
        $query .= ($param > 0) ? " WHERE users.use_id = :param AND us_status = '1';" : " WHERE us_status = '1';";

        $statement = self::prepareQuery($query);
        if ($param > 0) {
            $statement->bindParam(":param", $param, PDO::PARAM_INT);
        }
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    static public function login($data) {
        $user = $data['use_mail'];
        $pass = md5($data['use_pss']);

        if (!empty($user) && !empty($pass)) {
            $query = "SELECT us_identifier, us_key, use_id FROM users WHERE use_mail = :user AND use_pss = :pass AND us_status = '1'";
            $statement = self::prepareQuery($query);
            $statement->bindParam(":user", $user, PDO::PARAM_STR);
            $statement->bindParam(":pass", $pass, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else {
            return "NO TIENE CREDENCIALES";
        }
    }
}
?>