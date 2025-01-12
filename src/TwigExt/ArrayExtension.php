<?php

namespace Gupalo\TwigExt;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ArrayExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('uniq', $this->uniq(...), ['is_safe' => ['html']]),
            new TwigFilter('max_value', $this->maxValue(...), ['is_safe' => ['html']]),
            new TwigFilter('max_ratio_value', $this->maxRatioValue(...), ['is_safe' => ['html']]),
            new TwigFilter('sum_value', $this->sumValue(...), ['is_safe' => ['html']]),
            new TwigFilter('array_sum', $this->arraySum(...), ['is_safe' => ['html']]),
        ];
    }

    public function uniq(array $items, bool $keepKeys = false): array
    {
        $result = array_unique($items);

        return $keepKeys ? $result : array_values($result);
    }

    public function maxValue($items, string $field)
    {
        $propertyAccessor = new PropertyAccessor();

        $result = 0;
        foreach ($items as $item) {
            if (is_array($item)) {
                $value = $item[$field] ?? 0;
            } else {
                $value = $propertyAccessor->getValue($item, $field) ?? 0;
            }

            if ($value > $result) {
                $result = $value;
            }
        }

        return $result;
    }

    public function maxRatioValue($items, string $field, string $field2): float|int
    {
        $propertyAccessor = new PropertyAccessor();

        $result = 0;
        foreach ($items as $item) {
            if (is_array($item)) {
                $value = $item[$field] ?? 0;
                $value2 = $item[$field2] ?? 0;
            } else {
                $value = $propertyAccessor->getValue($item, $field) ?? 0;
                $value2 = $propertyAccessor->getValue($item, $field2) ?? 0;
            }
            if ($value2 > 0 && $value / $value2 > $result) {
                $result = $value / $value2;
            }
        }

        return $result;
    }

    public function sumValue($items, string $field)
    {
        $propertyAccessor = new PropertyAccessor();

        $result = 0;
        foreach ($items as $item) {
            if (is_array($item)) {
                $value = $item[$field] ?? 0;
            } else {
                $value = $propertyAccessor->getValue($item, $field) ?? 0;
            }

            $result += $value;
        }

        return $result;
    }

    public function arraySum($items): float|int
    {
        return array_sum($items);
    }
}
