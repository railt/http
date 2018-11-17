<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

/**
 * Trait HasPath
 * @mixin ProvidePath
 */
trait HasPath
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @return array|string[]
     */
    public function getPathChunks(): array
    {
        return static::pathToChunks($this->getPath());
    }

    /**
     * @param string $path
     * @return array
     */
    public static function pathToChunks(string $path): array
    {
        \assert(\trim($path) !== '');

        $chunks = \explode(ProvidePath::PATH_DELIMITER, \trim($path));

        \assert(\count($chunks) > 0);

        return $chunks;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param array|string[] $chunks
     * @return ProvideType|$this
     */
    public function withPathChunks(array $chunks): ProvideType
    {
        return $this->withPath(static::chunksToPath($chunks));
    }

    /**
     * @param string $path
     * @return ProvideType|$this
     */
    public function withPath(string $path): ProvideType
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param array|string[] $chunks
     * @return string
     */
    public static function chunksToPath(array $chunks): string
    {
        $chunks = \array_map('\\strval', $chunks);
        $chunks = \array_filter($chunks, '\\strlen');

        return \implode(ProvidePath::PATH_DELIMITER, $chunks);
    }
}
