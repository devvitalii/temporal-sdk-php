<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App;

class SimpleActivity
{
    #[\Temporal\Client\Activity\ActivityMethod]
    public function echo($value)
    {
        return $value;
    }

    #[\Temporal\Client\Activity\ActivityMethod]
    public function asyncActivity(): int
    {
        Activity::doNotCompleteOnReturn();

        return 0xDEAD_BEEF;
    }
}
