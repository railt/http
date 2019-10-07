<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

/**
 * Interface DataProviderInterface
 */
interface DataProviderInterface
{
    /**
     * @var string
     */
    public const FIELD_DATA = 'data';

    /**
     * @return array|null
     */
    public function getData(): ?array;
}
