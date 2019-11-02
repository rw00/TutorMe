<?php
require_once("php/classes/Rest.class.php");
require_once("php/classes/DBManager.php");

class API extends REST
{
    public $data = "";

    public function processApi()
    {
        $endpoint = null;
        if (isset($_REQUEST["x"])) {
            $endpoint = trim(str_replace("/", "", $_REQUEST["x"])); //strtolower()
        } else {
            //$this->request_args = explode('/', trim($_SERVER["PATH_INFO"], '/'));
            //echo $_SERVER["PATH_INFO"];
            //print_r($this->request_args);
            //$endpoint = array_shift($this->request_args);
        }

        //echo $endpoint;
        //print_r($this->request_args);
        if (method_exists($this, $endpoint))
            $this->$endpoint();
        else
            $this->response('No Endpoint', 404); // If the method does not exist within this class, then "Error 404: Not Found".
    }

    private function login()
    {
        if ($this->request_method != "POST") {
            $this->methodNotAllowed();
        }
        if (!isset($this->request_args["inputEmail"]) || !isset($this->request_args["inputPassword"])) {
            $this->missingParameters();
        }
        $email = $this->request_args["inputEmail"];
        $password = $this->request_args["inputPassword"];

        $conn = DBManager::getDBFactory()->getConnection();
        $stmt = $conn->prepare("SELECT * FROM USER WHERE Email = :email AND Password = :password");
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":password", md5($password));
        $stmt->execute();
        $this->data = $stmt->fetchAll();
        if (empty($this->data)) {
            $this->response('Incorrect email address or password!', 200);
        }
        $this->response($this->data);
    }

    private function methodNotAllowed()
    {
        $this->response('', 405);
    }

    private function missingParameters()
    {
        $this->response('Missing or Invalid Parameters', 404);
    }

    private function user()
    {
        if (!isset($this->request_args["id"])) {
            $this->missingParameters();
        }
        $conn = DBManager::getDBFactory()->getConnection();
        if ($this->request_method == "GET") {
            $stmt = $conn->prepare("SELECT * FROM USER WHERE UserId = :id");
            $stmt->bindValue(":id", $this->request_args["id"]);
            $stmt->execute();
            $this->data = $stmt->fetchAll();
            if (empty($this->data)) {
                $this->response('Not Found', 404);
            }
            $this->response($this->data);
        } else if ($this->request_method == "DELETE") {
            $stmt = $conn->prepare("DELETE FROM USER WHERE UserId = :id");
            $stmt->bindValue(":id", $this->request_args["id"]);
            if ($stmt->execute()) {
                $this->data = "Success";
            } else {
                $this->data = "Failed";
            }
            $this->response($this->data);
        }
        if (!isset($this->request_args["email"], $this->request_args["password"], $this->request_args["firstName"], $this->request_args["lastName"], $this->request_args["gender"])) {
            $this->missingParameters();
        }
        if ($this->request_method == "POST") {
            $stmt = $conn->prepare("INSERT INTO USER (Email, Password, FirstName, LastName)");
            $stmt->bindValue(":email", $this->request_args["email"]);
            if ($stmt->execute()) {
                $this->data = "Success";
            } else {
                $this->data = "Failed";
            }
            $this->response($this->data);
        } else if ($this->request_method == "PUT") {
            $stmt = $conn->prepare("UPDATE USER SET Email = :email");
            $stmt->bindValue(":email", $this->request_args["email"]);
            if ($stmt->execute()) {
                $this->data = "Success";
            } else {
                $this->data = "Failed";
            }
            $this->response($this->data);
        }
    }

    private function users()
    {
        if ($this->request_method != "GET") {
            $this->methodNotAllowed();
        }
        $conn = DBManager::getDBFactory()->getConnection();
        $stmt = $conn->prepare("SELECT * FROM USER");
        $stmt->execute();
        $this->data = $stmt->fetchAll();
        $this->checkEmpty();
    }

    private function checkEmpty()
    {
        if (empty($this->data)) {
            $this->response('', 404);
        } else {
            $this->response($this->data);
        }
    }

    private function courses()
    {
        if ($this->request_method != "GET") {
            $this->methodNotAllowed();
        }
        $conn = DBManager::getDBFactory()->getConnection();
        $stmt = $conn->prepare("SELECT * FROM COURSE");
        $stmt->execute();
        $this->data = $stmt->fetchAll();
        $this->checkEmpty();
    }

    private function subjects()
    {
        if ($this->request_method != "GET") {
            $this->methodNotAllowed();
        }
        $conn = DBManager::getDBFactory()->getConnection();
        $stmt = $conn->prepare("SELECT * FROM SUBJECT");
        $stmt->execute();
        $this->data = $stmt->fetchAll();
        $this->checkEmpty();
    }

    private function pageNotFound()
    {
        $this->response('', 404);
    }
}

$api = new API;
$api->processApi();
?>
