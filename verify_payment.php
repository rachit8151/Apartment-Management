<?php

session_start();  // Start the session to use session variables
// Include the Razorpay SDK and database connection
require('razorpay-php-2.9.0/Razorpay.php');
require 'dbFile/database.php';  // Make sure this file contains your DB connection

use Razorpay\Api\Api;

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die('Unauthorized access. Please login.');
}

// Initialize Razorpay API with your API keys (ensure these keys are secured and not hardcoded in production)
$razorpayKey = 'rzp_test_YpWMzLzMbgtqFk';
$razorpaySecret = 'lPo9E0pjKqPoCuJqoTDX7yWs';

$api = new Api($razorpayKey, $razorpaySecret);
echo $user_id = $_SESSION['user_id'];  // Assuming the user ID is stored in session
echo $maintenance_id = $_SESSION["maintenance_id"];  // Assuming maintenance ID is stored in session
// Get the payment details from the Razorpay callback
$paymentId = $_POST['razorpay_payment_id'];
$orderId = $_POST['razorpay_order_id'];
$signature = $_POST['razorpay_signature'];

// Verify the payment signature to ensure the payment was legitimate
$attributes = array(
    'razorpay_order_id' => $orderId,
    'razorpay_payment_id' => $paymentId,
    'razorpay_signature' => $signature
);

try {
    // Verify the payment signature using Razorpay's utility method
    $api->utility->verifyPaymentSignature($attributes);

    // Payment verified, now update the payment status in the database
    $updateQuery = "UPDATE tblPayment 
                    SET payment_status = 'paid' 
                    WHERE user_id = '$user_id' 
                    AND maintenance_id = '$maintenance_id'";

    if (mysqli_query($conn, $updateQuery)) {
        echo "Payment verified and payment status updated successfully.";
    } else {
        echo "Error updating payment status: " . mysqli_error($conn);
    }
} catch (Exception $e) {
    // Handle error if the signature verification fails
    echo "Payment verification failed: " . $e->getMessage();
}

$conn->close(); // Close the database connection
?>

<script>
    setInterval(() => {
        window.location = 'dashboard.php'; // Redirect to dashboard after a brief delay
    }, 3000);
</script>
<?php /*

  // Include the Razorpay SDK
  require('razorpay-php-2.9.0/Razorpay.php');
  require 'dbFile/database.php'; // Include your database connection file

  use Razorpay\Api\Api;
  use Razorpay\Api\Errors\Error;

  // Initialize Razorpay API with your API keys (ensure these keys are secured and not hardcoded in production)
  $keyId = 'rzp_test_YpWMzLzMbgtqFk';
  $keySecret = 'lPo9E0pjKqPoCuJqoTDX7yWs';

  $api = new Api($keyId, $keySecret);

  $attributes = array(
  'razorpay_order_id' => $_POST['razorpay_order_id'],
  'razorpay_payment_id' => $_POST['razorpay_payment_id'],
  'razorpay_signature' => $_POST['razorpay_signature']
  );


  try {
  $api->utility->verifyPaymentSignature($attributes);
  // Payment successful, update your database
  echo "Payment successful!";
  echo $_POST['razorpay_payment_id'];
  } catch (Error $e) {
  // Payment failed
  echo "Payment failed! " . $e;
  } */
?>