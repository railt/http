<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use Railt\Http\RequestInterface;
use Railt\Tests\Http\Mocks\NativeRequest;

/**
 * Class NativeRequestTestCase
 */
class NativeRequestTestCase extends TestCase
{
    /**
     * @param string $body
     * @param bool $makeJson
     * @return RequestInterface
     */
    protected function request(string $body, bool $makeJson = true): RequestInterface
    {
        return new NativeRequest($body, $makeJson);
    }
}
