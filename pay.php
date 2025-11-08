<?php
session_start();  // Start the session to use session variables
// Include the Razorpay SDK and database connection
require('razorpay-php-2.9.0/Razorpay.php');
require 'dbFile/database.php'; // Include your database connection file

use Razorpay\Api\Api;

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die('Unauthorized access. Please login.');
}

// Initialize Razorpay API with your API keys (ensure these keys are secured and not hardcoded in production)
$razorpayKey = 'rzp_test_YpWMzLzMbgtqFk';
$razorpaySecret = 'lPo9E0pjKqPoCuJqoTDX7yWs';

$api = new Api($razorpayKey, $razorpaySecret);

// Get user ID from session
$username = $_SESSION['username'];
$sqlUser = "SELECT user_id FROM tblUser WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);
if (!$resultUser) {
    die('Error fetching user data: ' . mysqli_error($conn));
}
$userData = mysqli_fetch_assoc($resultUser);
echo $userId = $userData['user_id'];
$_SESSION['user_id'] = $userId;
echo $maintenance_id = $_GET['maintenance_id'];
$_SESSION["maintenance_id"] = $maintenance_id;
// Query for maintenance details
$query = "SELECT year, amount, penalty, created_at FROM tblMaintenance WHERE maintenance_id = $maintenance_id";
$result = mysqli_query($conn, $query);
if (!$result) {
    die('Error fetching maintenance data: ' . mysqli_error($conn));
}
$row = mysqli_fetch_assoc(result: $result);

if (!$row) {
    die('No maintenance record found.');
}

$amount = $row['amount'];
$currency = 'INR';

// Create Razorpay order (server-side)
$orderData = [
    'receipt' => 'order_rcptid_' . rand(1000, 9999),
    'amount' => $amount * 100, // Razorpay expects the amount in the smallest currency unit (e.g., paise for INR)
    'currency' => $currency,
    'payment_capture' => 1 // auto-capture
];

$order = $api->order->create($orderData);
$orderId = $order->id; // Get the Razorpay order ID
$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];

$conn->close();
?>

<!-- Display the Razorpay Checkout form -->
<form action="verify_payment.php" method="POST">
    <script
        src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="<?php echo $razorpayKey; ?>"
        data-amount="<?php echo $amount * 100; ?>" 
        data-currency="<?php echo $currency; ?>"
        data-order_id="<?php echo $orderId; ?>"
        data-buttontext="Pay with Razorpay"
        data-name="Apartment Manage"
        data-description="Maintenance Payment"
        data-image="https://your_logo_url"  <!-- Replace with your actual logo URL -->
        data-prefill.name="<?php echo htmlspecialchars(string: $username); ?>"
        data-prefill.email="customer@example.com" ,
        data-theme.color="#F37254"
    ></script>
    <input type="hidden" custom="Hidden Element" name="hidden">
        </form>