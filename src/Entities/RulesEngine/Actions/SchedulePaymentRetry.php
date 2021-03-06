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

namespace Rebilly\Entities\RulesEngine\Actions;

use DomainException;
use Rebilly\Entities\PaymentRetryAttempt;
use Rebilly\Entities\RulesEngine\RuleAction;

/**
 * Class SchedulePaymentRetry.
 */
final class SchedulePaymentRetry extends RuleAction
{
    public const UNEXPECTED_POLICY = 'Unexpected policy. Only %s policies are supported';

    public const POLICY_NONE = 'none';

    public const POLICY_CHANGE_RENEWAL_TIME = 'change-subscription-renewal-time';

    public const POLICY_CANCEL_SUBSCRIPTION = 'cancel-subscription';

    /**
     * @return string[]|array
     */
    public static function afterAttemptPolicies(): array
    {
        return [
            self::POLICY_NONE,
            self::POLICY_CHANGE_RENEWAL_TIME,
        ];
    }

    /**
     * @return string[]|array
     */
    public static function afterRetryEndPolicies(): array
    {
        return [
            self::POLICY_NONE,
            self::POLICY_CANCEL_SUBSCRIPTION,
        ];
    }

    /**
     * @return PaymentRetryAttempt[]|array
     */
    public function getAttempts(): array
    {
        return $this->getAttribute('attempts');
    }

    /**
     * @param PaymentRetryAttempt[]|array $value
     *
     * @return $this
     */
    public function setAttempts($value): self
    {
        return $this->setAttribute('attempts', $value);
    }

    /**
     * @param PaymentRetryAttempt[]|array $value
     *
     * @return PaymentRetryAttempt[]|array
     */
    public function createAttempts($value): array
    {
        return array_map(function ($data) {
            if ($data instanceof PaymentRetryAttempt) {
                return $data;
            }

            return new PaymentRetryAttempt((array) $data);
        }, $value);
    }

    /**
     * @return string
     */
    public function getAfterAttemptPolicy(): string
    {
        return $this->getAttribute('afterAttemptPolicy');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setAfterAttemptPolicy($value): self
    {
        if (!in_array($value, self::afterAttemptPolicies(), true)) {
            throw new DomainException(sprintf(self::UNEXPECTED_POLICY, implode(', ', self::afterAttemptPolicies())));
        }

        return $this->setAttribute('afterAttemptPolicy', $value);
    }

    /**
     * @return string
     */
    public function getAfterRetryEndPolicy(): string
    {
        return $this->getAttribute('afterRetryEndPolicy');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setAfterRetryEndPolicy($value): self
    {
        if (!in_array($value, self::afterRetryEndPolicies(), true)) {
            throw new DomainException(sprintf(self::UNEXPECTED_POLICY, implode(', ', self::afterRetryEndPolicies())));
        }

        return $this->setAttribute('afterRetryEndPolicy', $value);
    }

    /**
     * @inheritdoc
     */
    public function actionName(): string
    {
        return self::NAME_SCHEDULE_PAYMENT_RETRY;
    }
}
