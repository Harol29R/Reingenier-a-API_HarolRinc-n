<?php
class UserController {
    private $_method;
    private $_complement;
    private $_data;

    function __construct($method, $complement, $data) {
        $this->_method = $method;
        $this->_complement = $complement == null ? 0 : $complement;
        $this->_data = $data != 0 ? $data : "";
    }

    public function index() {
        switch ($this->_method) {
            case "GET":
                $this->handleGet();
                break;
            case "POST":
                $this->handlePost();
                break;
            case "PUT":
                $this->handlePut();
                break;
            case "DELETE":
                $this->handleDelete();
                break;
            case "PATCH":
                $this->handlePatch();
                break;
            default:
                $json = array("ruta" => "not found");
                echo json_encode($json, true);
                return;
        }
    }

    private function handleGet() {
        $user = ($this->_complement == 0) ? UserModel::getUsers(0) : UserModel::getUsers($this->_complement);
        $json = $user;
        echo json_encode($json);
    }

    private function handlePost() {
        $createUser = UserModel::createUser($this->generateSalting());
        $json = array("response" => $createUser);
        echo json_encode($json, true);
    }

    private function handlePut() {
        $updateData = json_decode($this->_data, true);
        $updateData['use_id'] = $this->_complement;
        $updateUser = UserModel::updateUser($updateData);
        $json = array("response" => $updateUser);
        echo json_encode($json, true);
    }

    private function handleDelete() {
        $deleteUser = UserModel::deleteUser($this->_complement);
        $json = array("response" => $deleteUser);
        echo json_encode($json, true);
    }

    private function handlePatch() {
        $activateUser = UserModel::activateUser($this->_complement);
        $json = array("response" => $activateUser);
        echo json_encode($json, true);
    }

    private function generateSalting() {
        $trimmed_data = array_map('trim', $this->_data);

        if (!empty($trimmed_data)) {
            $trimmed_data['use_pss'] = md5($trimmed_data['use_pss']);
            $identifier = str_replace("$", 'y78', crypt($trimmed_data['use_mail'], 'ser3478'));
            $key = str_replace("$", 'ERT', crypt($trimmed_data['use_pss'], '$uniempresarial2024'));
            $trimmed_data['use_identifier'] = $identifier;
            $trimmed_data['us_key'] = $key;
            return $trimmed_data;
        }
        return [];
    }
}

?>