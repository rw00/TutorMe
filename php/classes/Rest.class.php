<?php

abstract class REST
{
    protected $request_args = array(); # arguments in the request uri
    protected $request_method = "";
    protected $content_type = "application/json";
    protected $response_code = 200;

    public function __construct()
    {
        $this->inputs();
    }

    private function inputs()
    {
        $this->request_method = $this->getRequestMethod();
        switch ($this->request_method) {
            case "POST":
                $this->request_args = $this->cleanInputs($_POST);
                break;
            case "GET":
                $this->request_args = $this->cleanInputs($_GET);
                break;
            case "DELETE":
                parse_str(file_get_contents("php://input"), $this->request_args);
                $this->request_args = $this->cleanInputs($this->request_args);
                break;
            case "PUT":
                parse_str(file_get_contents("php://input"), $this->request_args);
                $this->request_args = $this->cleanInputs($this->request_args);
                break;
            default:
                $this->response('Method Not Allowed', 405);
                break;
        }
    }

    # HTTP codes list: http://en.wikipedia.org/wiki/List_of_HTTP_status_codes

    protected function getRequestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        //	if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
        //		if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
        //			$method = 'DELETE';
        //		} else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
        //			$method = 'PUT';
        //		} else {
        //			throw new Exception("Unexpected Header");
        //		}
        //	}
        return $method;
    }

    private function cleanInputs($data)
    {
        $clean_input = array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->cleanInputs($v);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $data = trim(stripslashes($data));
            }
            $data = strip_tags($data);
            $clean_input = trim($data);
        }
        return $clean_input;
    }

    protected function response($data, $status = 200)
    {
        $this->response_code = $status;
        $this->setHeaders();
        if (is_array($data))
            echo json_encode($data, JSON_PRETTY_PRINT);
        else if ($data == '')
            echo json_encode($this->getStatus($this->response_code));
        else echo json_encode($data);
        exit;
    }

    private function setHeaders()
    {
        header("HTTP/1.1 " . $this->response_code . " " . $this->getStatus($this->response_code));
        header("Content-Type:" . $this->content_type);
    }

    protected function getStatus($code)
    {
        $status = array(
            200 => 'OK',
            204 => 'No Content',
            404 => 'Not Found',
            405 => 'request_method Not Allowed',
            406 => 'Not Acceptable',
            500 => 'Internal Server Error');
        return ($status[$code]) ? $status[$code] : $status[500];
    }
}

?>
