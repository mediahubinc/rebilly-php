<?php
/**
 * This source file is proprietary and part of Rebilly.
 *
 * (c) Rebilly SRL
 *     Rebilly Ltd.
 *     Rebilly Inc.
 *
 * @see https://www.rebilly.com
 */

namespace Rebilly\Entities;

use DomainException;
use Rebilly\Entities\PaymentInstruments\AchInstrument;
use Rebilly\Entities\PaymentInstruments\AlternativeInstrument;
use Rebilly\Entities\PaymentInstruments\CashInstrument;
use Rebilly\Entities\PaymentInstruments\CheckInstrument;
use Rebilly\Entities\PaymentInstruments\PaymentCardInstrument;
use Rebilly\Entities\PaymentInstruments\PayPalInstrument;
use Rebilly\Rest\Resource;

/**
 * Class PaymentMethodInstrument.
 */
abstract class PaymentMethodInstrument extends Resource
{
    public const MSG_UNSUPPORTED_METHOD = 'Unexpected method. Only %s methods are supported';

    public const MSG_REQUIRED_METHOD = 'Method is required';

    protected static $supportedCommonMethods = [
        PaymentMethod::METHOD_ACH,
        PaymentMethod::METHOD_CASH,
        PaymentMethod::METHOD_CHECK,
        PaymentMethod::METHOD_PAYMENT_CARD,
        PaymentMethod::METHOD_PAYPAL,
    ];

    protected static $supportedAlternativeMethods = [
        PaymentMethod::METHOD_IDEAL,
        PaymentMethod::METHOD_KLARNA,
        PaymentMethod::METHOD_INTERAC,
        PaymentMethod::METHOD_ONLINEUEBERWEISEN,
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data = [])
    {
        parent::__construct(['method' => $this->methodName()] + $data);
    }

    /**
     * @param array $data
     *
     * @return PaymentMethodInstrument
     */
    public static function createFromData(array $data)
    {
        if (!isset($data['method'])) {
            throw new DomainException(self::MSG_REQUIRED_METHOD);
        }

        switch ($data['method']) {
            case PaymentMethod::METHOD_ACH:
                return new AchInstrument($data);
            case PaymentMethod::METHOD_CASH:
                return new CashInstrument($data);
            case PaymentMethod::METHOD_CHECK:
                return new CheckInstrument($data);
            case PaymentMethod::METHOD_PAYMENT_CARD:
                return new PaymentCardInstrument($data);
            case PaymentMethod::METHOD_PAYPAL:
                return new PayPalInstrument($data);
            default:
                if (in_array($data['method'], self::$supportedAlternativeMethods, true)) {
                    return new AlternativeInstrument($data);
                }
        }

        throw new DomainException(
            sprintf(
                self::MSG_UNSUPPORTED_METHOD,
                implode(',', array_merge(self::$supportedCommonMethods, self::$supportedAlternativeMethods))
            )
        );
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->getAttribute('method');
    }

    /**
     * @return string
     */
    abstract protected function methodName();
}
