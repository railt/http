<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline\Middleware\Debug;

use Railt\Config\RepositoryInterface;
use Railt\Http\Pipeline\MiddlewareInterface;

/**
 * Class DebuggingMiddleware
 */
abstract class DebuggingMiddleware implements MiddlewareInterface
{
    /**
     * @var RepositoryInterface
     */
    private RepositoryInterface $config;

    /**
     * DebuggingMiddleware constructor.
     *
     * @param RepositoryInterface $config
     */
    public function __construct(RepositoryInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @return bool
     */
    protected function isDebug(): bool
    {
        return (bool)$this->config->get('debug', false);
    }
}
