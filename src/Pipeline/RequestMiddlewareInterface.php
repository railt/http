<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline;

use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

/**
 * Interface RequestMiddlewareInterface
 */
interface RequestMiddlewareInterface
{
    /**
     * @param RequestInterface $request
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, RequestHandlerInterface $next): ResponseInterface;
}
