<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Extension;

/**
 * Interface ProvidesExtensions
 */
interface ProvidesExtensions
{
    /**
     * @var string
     */
    public const FIELD_EXTENSIONS = 'extensions';

    /**
     * @return array
     */
    public function getExtensions(): array;

    /**
     * @param string|int|bool|float|ExtensionInterface|\JsonSerializable $keyOrExtension
     * @param ExtensionInterface|\JsonSerializable|mixed $value
     * @return ProvidesExtensions|$this
     */
    public function withExtension($keyOrExtension, $value = null): self;
}
