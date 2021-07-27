<?php
namespace NeosRulez\Shop\Mollie\Domain\Service\Payment;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Mollie
 *
 * @Flow\Scope("singleton")
 */
class Mollie
{

    /**
     * @Flow\InjectConfiguration(package="NeosRulez.Shop")
     * @var array
     */
    protected $settings = [];

    /**
     * @param array $payment
     * @param array $args
     * @param string $success_uri
     * @param string $failure_uri
     * @return string
     */
    public function execute($payment, $args, $success_uri, $failure_uri):string
    {
        $amount = number_format($args['summary']['total'], 2, '.', ',');

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($this->settings['Payment']['mollie']['args']['apiKey']);

        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => $amount
            ],
            "description" => 'Order: ' . $args['order_number'],
            "redirectUrl" => $success_uri
        ]);

        return $payment->getCheckoutUrl();

    }

}
