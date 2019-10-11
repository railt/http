<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline;

use Railt\Http\Pipeline\Handler\HandlerInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

/**
 * Interface MiddlewareInterface
 */
interface MiddlewareInterface
{
    /**
     * @param RequestInterface $payload
     * @param HandlerInterface $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $payload, HandlerInterface $next): ResponseInterface;
}
