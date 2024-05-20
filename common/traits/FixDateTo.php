<?php

namespace common\traits;


use common\components\DateHelper;
use common\components\RuleHelper;

/**
 * Usage:
 * add this code to your search model:
 * public string|null $date_to = null;
 * public string|null $date_to_fixed = null;
 *
 * public function rules(): array
 * {
 * $model = new self();
 *
 * return [
 * RuleHelper::date($model, 'date_to'),
 * ];
 * }
 *
 * and add this code to your search function (after load params):
 * $this->fixDateTo();
 */
trait FixDateTo
{
    /**
     * 2023-01-01 === 2023-01-01 00:00:00, but we need 2023-01-01 23:59:59
     * then we set end of the day
     */
    private function fixDateTo(): void
    {
        if (!empty($this->date_to)) {
            $this->date_to_fixed = DateHelper::getEndOfTheDay($this->date_to);
        } else {
            $this->date_to_fixed = null;
        }
    }
}