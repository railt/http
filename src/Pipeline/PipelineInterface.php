<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline;

use Railt\Container\ContainerInterface;
use Railt\Http\Pipeline\Handler\HandlerInterface;

/**
 * Interface PipelineInterface
 */
interface PipelineInterface
{
    /**
     * @param MiddlewareInterface|string ...$middleware
     * @return PipelineInterface|$this
     */
    public function through(...$middleware): self;

    /**
     * @param ContainerInterface $app
     * @param mixed $payload
     * @param HandlerInterface $handler
     * @return mixed
     */
    public function send(ContainerInterface $app, $payload, HandlerInterface $handler);
}
