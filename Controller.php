<?php

function createCheckoutSession() {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paymongo.com/v1/checkout_sessions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            "data" => [
                "attributes" => [
                    "amount" => 50000,  // Amount in centavos (e.g., 50000 = PHP 500.00)
                    "currency" => "PHP",
                    "description" => "Sample transaction",
                    "line_items" => [
                        [
                            "name" => "Sample Product",
                            "amount" => 50000,
                            "currency" => "PHP",
                            "quantity" => 1
                        ]
                    ],
                    "payment_method_types" => ["card", "paymaya"], 
                    "success_url" => "https://yourdomain.com/success", 
                    "cancel_url" => "https://yourdomain.com/cancel" 
                ]
            ]
        ]),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Authorization: Basic c2tfdGVzdF9IdVgxMU4zdkdjSjZkTXpmN3RLcVU5ZXM6", 
            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo "cURL Error #: " . curl_error($curl);
        curl_close($curl);
        return null;
    }

    curl_close($curl);

    $decoded = json_decode($response, true);

    if (isset($decoded['data']['attributes']['checkout_url'])) {
        return $decoded['data']['attributes']['checkout_url'];
    }

    echo "Failed to get checkout URL from response.";
    return null;
}

$checkoutUrl = createCheckoutSession();
if ($checkoutUrl) {
    header("Location: $checkoutUrl");
    exit();
}
?>
