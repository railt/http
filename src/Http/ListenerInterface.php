<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

/**
 * Interface ListenerInterface
 */
interface ListenerInterface
{
    /**
     * @param RequestInterface $request
     * @param \Closure $then
     * @return ListenerInterface|$this
     */
    public function listen(RequestInterface $request, \Closure $then): self;
}
