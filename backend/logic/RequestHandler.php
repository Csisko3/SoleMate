<?php

require_once "userLogic.php";
require_once "userLogin.php";
require_once "productLogic.php";
require_once "cartLogic.php";
require_once "couponLogic.php";


$requestHandler = new RequestHandler();
$requestHandler->handleRequest();

class RequestHandler
{
    private $userLogic;
    private $userLogin;
    private $productLogic;
    private $cartLogic;
    private $couponLogic;

    public function __construct()
    {
        $this->userLogic = new UserLogic();
        $this->userLogin = new UserLogin();
        $this->productLogic = new ProductLogic();
        $this->cartLogic = new CartLogic();
        $this->couponLogic = new CouponLogic();
        //$this->adminProduct = new AdminProduct();
    }


    public function handleRequest()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $resource = $_GET['resource'] ?? '';
        $params = $_GET['params'] ?? [];

        // Map the HTTP method and resource to the appropriate handler
        // GET, PUT, Delete coming 
        switch ($requestMethod) {

            case 'POST':
                $this->handlePostRequest($resource);
                break;
            case 'PUT':
                $this->handlePutRequest($resource);
                break;
            case 'GET':
                $this->handleGetRequest($resource, $params);
                break;
            case 'DELETE':
                $this->handleDeleteRequest($resource);
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
                $requestData = $this->getTheRequestBody();
                $this->success(201, $this->userLogic->saveUser($requestData));
                break;
            case 'login':
                $requestData = $this->getTheRequestBody();
                $this->success(200, $this->userLogin->loginUser($requestData));
                break;
            case 'update_profile':
                $requestData = $this->getTheRequestBody();
                $this->success(200, $this->userLogic->updateProfile($_SESSION['user_id'], $requestData));
                break;
            case 'add_product':
                $this->success(200, $this->productLogic->addProduct());
                break;
            case 'edit_product':
                $productId = $_GET['id'] ?? 0;
                if ($productId > 0)
                    $this->success(200, $this->productLogic->editProduct($productId));
            case 'add_cart':
                $requestData = $this->getTheRequestBody();
                if (!isset($_SESSION['user_id'])) {
                    $this->error(401, [], "User not logged in");
                }
                $this->success(200, $this->cartLogic->addToCart($_SESSION['user_id'], $requestData['product_id']));
                break;
            case 'change_customer_status':
                $requestData = $this->getTheRequestBody();
                $customerId = $requestData['id'] ?? 0;
                $action = $requestData['action'] ?? '';
                if ($customerId > 0 && in_array($action, ['activate', 'deactivate']))
                    $this->success(200, $this->userLogic->changeCustomerStatus($customerId, $action));
                break;
            case 'remove_order_item':
                $requestData = $this->getTheRequestBody();
                $userId = $_SESSION['user_id'];
                $orderId = $requestData['order_id'];
                $productId = $requestData['product_id'];
                $this->success(200, $this->cartLogic->removeOrderItem($userId, $orderId, $productId));
                break;
            case 'update_cart_item':
                $requestData = $this->getTheRequestBody();
                $this->success(200, $this->cartLogic->updateCartItem($_SESSION['user_id'], $requestData));
                break;
            case 'place_order':
                $requestData = $this->getTheRequestBody();
                $userId = $_SESSION['user_id'];
                $this->success(200, $this->cartLogic->placeOrder($userId, $requestData));
                break;
            case 'coupon':
                $requestData = $this->getTheRequestBody();
                $coupon = $this->couponLogic->saveCoupon($requestData);
                if ($coupon)
                    $this->success(201, $coupon);
                break;
            default:
                $this->error(400, [], "Method not allowed");
                break;
        }
    }

    public function handlePutRequest($resource)
    {
        switch ($resource) {
            default:
                $this->error(400, [], "Method not allowed");
                break;
        }
    }

    public function handleGetRequest($resource, $params)
    {
        switch ($resource) {
            case 'autoLogin':
                $requestData = [];
                $this->success(201, $this->userLogic->autoLogin($requestData));
                break;
            case 'load_products':
                $category = $params['category'] ?? '';
                $this->success(200, $this->productLogic->load_products($category));
                break;
            case 'admin_load_products':
                $this->success(200, $this->productLogic->load_products());
                break;
            case 'search_products':
                $query = $_GET['query'] ?? '';
                $this->success(200, $this->productLogic->searchProducts($query));
                break;
            case 'checkLoginStatus':
                $this->success(200, $this->userLogic->checkLoginStatus());
                break;
            case 'load_profile':
                $this->success(200, $this->userLogic->loadProfile($_SESSION['user_id']));
                break;
            case 'get_product':
                $productId = $_GET['id'] ?? 0;  // Use $_GET to fetch the product ID from the query string
                if ($productId > 0) {
                    $productData = $this->productLogic->getProduct($productId);
                    if ($productData)
                        $this->success(200, $productData);
                }
                break;
            case 'get_cart':
                $this->success(200, $this->cartLogic->getCart($_SESSION['user_id']));
                break;
            case 'get_orders':  // Added case for fetching orders
                $this->success(200, $this->cartLogic->getOrders($_SESSION['user_id']));
                break;
            case 'get_orders_customer':  // Added case for fetching orders
                $customerId = $_GET['customer_id'] ?? 0;
                if ($customerId > 0)
                    $this->success(200, $this->cartLogic->getOrdersCustomer($customerId));
                break;
            case 'get_order_details':  // Added case for fetching order details
                $orderId = $_GET['order_id'] ?? 0;
                if ($orderId > 0)
                    $this->success(200, $this->cartLogic->getOrderDetails($_SESSION['user_id'], $orderId));
                break;
            case 'print_invoice':
                $orderId = $_GET['order_id'] ?? 0;
                if ($orderId > 0)
                    require 'print_invoice.php';
                break;
            case 'loadCustomers':
                $this->success(200, $this->userLogic->loadCustomers());
                break;
            case 'load_coupons':
                $this->success(200, $this->couponLogic->getAllCoupons());
                break;
            default:
                $this->error(400, [], "Method not allowed");
                break;
        }
    }

    public function handleDeleteRequest($resource)
    {
        switch ($resource) {
            case 'delete_product':
                $requestData = $this->getTheRequestBody();
                $productId = $requestData['id'] ?? 0;
                if ($productId > 0)
                    $this->success(200, $this->productLogic->deleteProduct($productId));
                break;
            default:
                $this->error(400, [], "Method not allowed");
                break;
        }
    }


    /** format success response and exit
     * @param mixed $data object, could be "anything"
     */
    private function success(int $code, mixed $data)
    {
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
    private function error(int $code, array $headers, $msg)
    {
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
