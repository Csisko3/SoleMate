<?php
require_once "userLogic.php";

$requestHandler = new RequestHandler();
$requestHandler->handleRequest();

class RequestHandler
{
    private $userLogic;

    public function __construct() {
        $this->userLogic = new UserLogic();
    }

    
    public function handleRequest() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $resource = $_GET['resource'] ?? '';

        // Map the HTTP method and resource to the appropriate handler
        // GET, PUT, Delete coming 
        switch ($requestMethod) {

            case 'POST':
                $this->handlePostRequest($resource);
                break;
            case 'PUT':
                // logic
                break;
            case 'GET':
                //logic
                break;
            case 'DELETE':
                //logic
                break;
            default:
            $this->error(405, ["Allow: GET, POST, DELETE"], "Method not allowed");  
                break;
        }
    }

    public function handlePostRequest($resource)
    {
        switch ($resource) {
            case 'user':
                // Handle creating a new user
                $requestData = $this->getTheRequestBody();
                $this->success(201, $this->userLogic->saveUser($requestData));
                break;
            default:
                 $this->error(400, [], "Method not allowed");
                break;
        }
    }


    /** format success response and exit
     * @param mixed $data object, could be "anything"
     */
    private function success(int $code, mixed $data) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /** format error (with headers) and exit
     * @param int $code HTTP response code (4xx or 5xx)
     * @param array $headers
     * @param string $msg 
     */
    private function error(int $code, array $headers, $msg) {
        http_response_code($code);
        foreach ($headers as $hdr) {
            header($hdr);
        }    
        echo ($msg);
        exit;
    }     

    /** gets the post request body if it was json and returns it as json decoded
     * @return mixed
     */
    private function getTheRequestBody(): mixed
    {
        // Get the request body
        $requestBody = file_get_contents('php://input');
        $requestData = json_decode($requestBody, true);
        // Check if the request body is valid JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error(400, [], "Invalid request body");
        }
        return $requestData;
    }
}
