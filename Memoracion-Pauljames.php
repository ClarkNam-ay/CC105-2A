<?php
// dp.php
// Example: Fibonacci using Dynamic Programming (Memoization)

function fibonacci($n, &$memo = []) {
    if ($n <= 1) {
        return $n;
    }

    // Check if already computed
    if (isset($memo[$n])) {
        return $memo[$n];
    }

    // Store result in memo array
    $memo[$n] = fibonacci($n - 1, $memo) + fibonacci($n - 2, $memo);
    return $memo[$n];
}

// Test the function
$n = 10;
echo "Fibonacci of $n is: " . fibonacci($n);
?>