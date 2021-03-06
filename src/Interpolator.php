<?php

namespace Messenger\People;

use InvalidArgumentException;

class Interpolator
{
    public const LEFT_DELIMITER = 'leftDelimiter';
    public const RIGHT_DELIMITER = 'rightDelimiter';

    public function interpolate(string $str, array $replacement, array $options): string
    {
        if (!$this->areOptionsValid($options)) {
            throw new InvalidArgumentException('Options are not valid');
        }

        if (!$this->isReplacementValid($replacement)) {
            throw new InvalidArgumentException('Replacement is not valid');
        }

        $replaceKeys = $this->buildReplacementKeys($replacement, $options);
        $replaceValues = array_values($replacement);

        return str_replace($replaceKeys, $replaceValues, $str);
    }

    private function buildReplacementKeys(array $replacement, array $delimiters): array
    {
        $replaceKeys = [];

        foreach ($replacement as $key => $val) {
            $placeholder = sprintf(
                "%s%s%s",
                $delimiters[self::LEFT_DELIMITER],
                $key,
                $delimiters[self::RIGHT_DELIMITER]
            );

            array_push($replaceKeys, $placeholder);
        }

        return $replaceKeys;
    }

    private function areOptionsValid(array $options): bool
    {
        if (!$this->isAssoc($options)) {
            return false;
        }

        if (
            !array_key_exists(self::LEFT_DELIMITER, $options)
            || !array_key_exists(self::RIGHT_DELIMITER, $options)
        ) {
            return false;
        }

        return true;
    }

    private function isReplacementValid(array $replacement): bool
    {
        if (!$this->isAssoc($replacement)) {
            return false;
        }

        return true;
    }

    /**
     * Check is array Associative
     *
     * @param array $arr
     * @return bool
     */
    private function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
