<?php
include 'userLogic.php';

class RequestHandler
{
    private $userLogic;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->userLogic = new UserLogic();
    }

    public function handlePostRequest($resource, $requestData)
    {
        switch ($resource) {
            case 'user':
                $result = $this->userLogic->saveUser($requestData, $this->conn);
                $this->outputJSON($result);
                break;
            default:
                $this->outputJSON(['status' => 'error', 'message' => 'Invalid resource']);
                break;
        }
    }

    private function outputJSON($response)
    {
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>
