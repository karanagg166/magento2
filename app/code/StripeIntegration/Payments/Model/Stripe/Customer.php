<?php

namespace StripeIntegration\Payments\Model\Stripe;

#[\AllowDynamicProperties]
class Customer extends StripeObject
{
    protected $objectSpace = 'customers';
}
