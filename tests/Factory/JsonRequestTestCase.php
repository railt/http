<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http\Factory;

use Railt\Component\Http\Provider\DataProvider;
use Railt\Component\Http\Provider\ProviderInterface;
use Railt\Component\Http\Request;

/**
 * Class JsonRequestTestCase
 */
class JsonRequestTestCase extends FactoryTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    protected function getRequests(): array
    {
        return [
            Request::create($this->string(0), ['a' => $this->string(1)], $this->string(2)),
        ];
    }

    /**
     * @return ProviderInterface
     * @throws \Exception
     */
    protected function getProvider(): ProviderInterface
    {
        return DataProvider::new()
            ->withContentType('application/json')
            ->withBody(
                \vsprintf('{"query": "%s", "variables": {"a": "%s"}, "operationName": "%s"}', [
                    $this->string(0),
                    $this->string(1),
                    $this->string(2),
                ])
            );
    }
}
