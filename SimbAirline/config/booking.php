<?php
return [
    'booking_route_prefix'=>env("BOOKING_ROUTER_PREFIX",'booking'),
    'services'=>[
        'tour'=>Modules\Tour\Models\Tour::class
    ],
    'payment_gateways'=>[
        'offline_payment'=>Modules\Booking\Gateways\OfflinePaymentGateway::class,
        'limopay'=>Modules\Booking\Gateways\LimoPayGateway::class,
        'directpay'=>Modules\Booking\Gateways\DirectPayGateway::class,
        'paypal'=>Modules\Booking\Gateways\PaypalGateway::class,
        'stripe'=>Modules\Booking\Gateways\StripeGateway::class
    ],
    'statuses'=>[
        'completed',
        'processing',
        'confirmed',
        'cancelled',
        'paid',
        'unpaid',
        'partial_payment',
    ]
];