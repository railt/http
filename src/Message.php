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
 * Class Message
 */
class Message implements MessageInterface
{
    /**
     * @var iterable
     */
    private $data;

    /**
     * @var iterable|\Throwable[]
     */
    private $errors;

    /**
     * ResponseChunk constructor.
     * @param iterable $data
     * @param iterable|\Throwable[] $errors
     */
    public function __construct(iterable $data = [], iterable $errors = [])
    {
        $this->data = $data;
        $this->addExceptions($errors);
    }

    /**
     * @param iterable|\Throwable[] $errors
     */
    public function addExceptions(iterable $errors): void
    {
        foreach ($errors as $error) {
            $this->addException($error);
        }
    }

    /**
     * @param \Throwable $error
     */
    public function addException(\Throwable $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return iterable|\Throwable[]
     */
    public function getErrors(): iterable
    {
        return $this->errors;
    }

    /**
     * @return iterable|\Throwable[]
     */
    public function getExceptions(): iterable
    {
        return $this->getErrors();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \array_filter([
            static::FIELD_DATA   => $this->getData() ?: null,
            static::FIELD_ERRORS => $this->errors ?: null,
        ]);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        // Unwrap iterators
        $this->data = $this->data instanceof \Traversable ? \iterator_to_array($this->data) : $this->data;

        return $this->data;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return \count($this->errors) === 0;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return \count($this->errors) > 0;
    }
}
