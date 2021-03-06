<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Provider\GlobalsProvider;
use Railt\Http\Provider\ProviderInterface;
use Railt\Http\Resolver\ApolloBatchingRequest;
use Railt\Http\Resolver\GetHttpRequest;
use Railt\Http\Resolver\JsonHttpRequest;
use Railt\Http\Resolver\PostHttpRequest;
use Railt\Http\Resolver\ResolverInterface;

/**
 * Class Factory
 */
class Factory implements \IteratorAggregate
{
    /**
     * @var string[]
     */
    private const DEFAULT_RESOLVERS = [
        JsonHttpRequest::class,
        ApolloBatchingRequest::class,
        PostHttpRequest::class,
        GetHttpRequest::class,
    ];

    /**
     * @var array|ResolverInterface[]
     */
    private $resolvers = [];

    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * Factory constructor.
     *
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;

        $this->boot();
    }

    /**
     * @param ProviderInterface $provider
     * @return Factory
     */
    public static function create(ProviderInterface $provider): self
    {
        return new static($provider);
    }

    /**
     * @return Factory
     */
    public static function createFromGlobals(): self
    {
        return static::create(new GlobalsProvider());
    }

    /**
     * @return void
     */
    private function boot(): void
    {
        foreach (self::DEFAULT_RESOLVERS as $resolver) {
            $this->resolvers[] = new $resolver();
        }
    }

    /**
     * @param ResolverInterface $resolver
     * @return Factory|$this
     */
    public function addResolver(ResolverInterface $resolver): self
    {
        $this->resolvers[] = $resolver;

        return $this;
    }

    /**
     * @return iterable|RequestInterface[]
     */
    public function getRequests(): iterable
    {
        return $this->resolve($this->provider);
    }

    /**
     * @param ProviderInterface $provider
     * @return iterable|RequestInterface[]
     */
    private function resolve(ProviderInterface $provider): iterable
    {
        foreach ($this->resolvers as $resolver) {
            yield from $requests = $this->parse($resolver, $provider);

            if ($requests->getReturn()) {
                break;
            }
        }
    }

    /**
     * @param ResolverInterface $resolver
     * @param ProviderInterface $provider
     * @return \Generator|bool
     */
    private function parse(ResolverInterface $resolver, ProviderInterface $provider): \Generator
    {
        $resolved = false;

        foreach ($resolver->parse($provider) as $query) {
            $resolved = true;
            yield $query;
        }

        return $resolved;
    }

    /**
     * @return \Traversable|RequestInterface[]
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getRequests();
    }
}
