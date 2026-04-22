<?php
// Function to calculate total with default tax
function calculateTotal($prices = [], $taxRate = 0.05) {
    $total = 0;

    foreach ($prices as $price) {
        if (!is_numeric($price) || $price < 0) {
            continue;
        }
        $total += $price;
    }

    return $total + ($total * $taxRate);
}

// Function to apply discount
function applyDiscount($total, $isMember = false, $coupon = false) {

    if ($total >= 50 && $total <= 500) {
        $discount = 0.10;
    } elseif ($total > 500 || $isMember) {
        $discount = 0.15;
    } else {
        $discount = 0;
    }

    // XOR condition
    if ($isMember xor $coupon) {
        $discount += 0.05;
    }

    // Ternary operator
    $discount = ($discount > 0) ? $discount : 0;

    $final = $total - ($total * $discount);

    return [$final, $discount];
}

// Switch for order type
function orderTypeMessage($type) {
    switch ($type) {
        case "dine-in":
            return "Dine-In selected. Enjoy your meal!";
        case "takeaway":
            return "Takeaway selected. Packed for you!";
        case "delivery":
            return "Delivery selected. On the way!";
        default:
            return "Invalid order type!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Food Ordering System</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f4f4f4; }
        form { background: #fff; padding: 20px; border-radius: 10px; width: 400px; }
        input, select { width: 100%; padding: 8px; margin: 8px 0; }
        .result { margin-top: 20px; padding: 15px; background: #dff0d8; border-radius: 10px; }
    </style>
</head>
<body>

<h2>Online Food Order</h2>

<form method="post">
    <label>Enter Item Prices (comma separated):</label>
    <input type="text" name="prices" placeholder="e.g. 100,200,50" required>

    <label>Order Type:</label>
    <select name="order_type">
        <option value="dine-in">Dine-In</option>
        <option value="takeaway">Takeaway</option>
        <option value="delivery">Delivery</option>
    </select>

    <label>
        <input type="checkbox" name="member"> Member
    </label>

    <label>
        <input type="checkbox" name="coupon"> Coupon Applied
    </label>

    <input type="submit" name="submit" value="Place Order">
</form>

<?php
if (isset($_POST['submit'])) {

    // Get form data
    $priceInput = $_POST['prices'];
    $prices = explode(",", $priceInput);

    $orderType = $_POST['order_type'];
    $isMember = isset($_POST['member']);
    $coupon = isset($_POST['coupon']);

    // Calculate total
    $total = calculateTotal($prices);

    // Apply discount
    list($finalAmount, $discount) = applyDiscount($total, $isMember, $coupon);

    echo "<div class='result'>";
    echo "<h3>Order Summary</h3>";

    echo "Order Type: $orderType <br>";
    echo orderTypeMessage($orderType) . "<br><br>";

    echo "Total (with tax): $" . number_format($total, 2) . "<br>";
    echo "Discount: " . ($discount * 100) . "%<br>";
    echo "Final Amount: $" . number_format($finalAmount, 2) . "<br>";

    // Logical NOT example
    if (!$isMember && !$coupon) {
        echo "<br><b>No extra benefits applied.</b>";
    }

    echo "</div>";
}
?>

</body>
</html>