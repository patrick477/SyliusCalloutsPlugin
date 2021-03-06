<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutsPlugin\Checker\Eligibility;

use Setono\SyliusCalloutsPlugin\Checker\Rule\ProductCalloutRuleCheckerInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutRuleInterface;
use Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class CalloutRulesEligibilityChecker implements CalloutEligibilityCheckerInterface
{
    /** @var ServiceRegistryInterface */
    private $ruleRegistry;

    public function __construct(ServiceRegistryInterface $ruleRegistry)
    {
        $this->ruleRegistry = $ruleRegistry;
    }

    public function isEligible(CalloutsAwareInterface $product, CalloutInterface $callout): bool
    {
        if (!$callout->hasRules()) {
            return false;
        }

        foreach ($callout->getRules() as $rule) {
            if (!$this->isEligibleToRule($product, $rule)) {
                return false;
            }
        }

        return true;
    }

    private function isEligibleToRule(CalloutsAwareInterface $product, CalloutRuleInterface $rule): bool
    {
        /** @var ProductCalloutRuleCheckerInterface $checker */
        $checker = $this->ruleRegistry->get($rule->getType());

        return $checker->isEligible($product, $rule->getConfiguration());
    }
}
