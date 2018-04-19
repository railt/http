<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Railt\Http\RequestInterface;
use Railt\Http\Support\ConfigurableRequest;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testJsonIsReadable(): void
    {
        $request = $this->request('{"query": "some"}');

        $this->assertNotSame('{}', $request->getQuery());
    }

    /**
     * @param string $body
     * @param bool $makeJson
     * @return RequestInterface
     */
    abstract protected function request(string $body, bool $makeJson = true): RequestInterface;

    /**
     * @return void
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testJsonIsNotReadable(): void
    {
        $request = $this->request('{"query": "some"}', false);

        $this->assertSame('{}', $request->getQuery());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testQueryIsReadable(): void
    {
        $expected = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $request = $this->request('{"query": "' . $expected . '"}');

        $this->assertSame($expected, $request->getQuery());
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testQueryDefaultValue(): void
    {
        $request = $this->request('');

        $this->assertSame('{}', $request->getQuery());
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testVariablesDefaultValue(): void
    {
        $request = $this->request('');

        $this->assertCount(0, $request->getVariables());
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testOperationDefaultValue(): void
    {
        $request = $this->request('');

        $this->assertNull($request->getOperation());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testVariablesIsReadable(): void
    {
        $expected = [(string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX)];

        $request = $this->request('{"variables": "' . $expected[0] . '"}');

        $this->assertSame($expected, $request->getVariables());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testOperationIsReadable(): void
    {
        $expected = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $request = $this->request('{"operationName": "' . $expected . '"}');

        $this->assertSame($expected, $request->getOperation());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testAllDataIsReadable(): void
    {
        $query     = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);
        $variables = [(string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX)];
        $operation = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $data = \json_encode([
            'query'         => $query,
            'variables'     => $variables,
            'operationName' => $operation,
        ]);

        $request = $this->request($data);

        $this->assertSame($query, $request->getQuery());
        $this->assertSame($variables, $request->getVariables());
        $this->assertSame($operation, $request->getOperation());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testPostIsReadable(): void
    {
        $query     = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);
        $variables = [(string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX)];
        $operation = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $_POST = [
            'query'         => $query,
            'variables'     => $variables,
            'operationName' => $operation,
        ];

        $request = $this->request('', false);

        $this->assertSame($query, $request->getQuery());
        $this->assertSame($variables, $request->getVariables());
        $this->assertSame($operation, $request->getOperation());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetIsReadable(): void
    {
        $query     = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);
        $variables = [(string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX)];
        $operation = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $_GET = [
            'query'         => $query,
            'variables'     => $variables,
            'operationName' => $operation,
        ];

        $request = $this->request('', false);

        $this->assertSame($query, $request->getQuery());
        $this->assertSame($variables, $request->getVariables());
        $this->assertSame($operation, $request->getOperation());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetHasLowerPriorityThanPost(): void
    {
        $query     = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);
        $variables = [(string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX)];
        $operation = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $_POST = [
            'query'         => $query,
            'variables'     => $variables,
            'operationName' => $operation,
        ];

        $_GET = [
            'query'         => (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX),
            'variables'     => (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX),
            'operationName' => (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX),
        ];

        $request = $this->request('', false);

        $this->assertSame($query, $request->getQuery());
        $this->assertSame($variables, $request->getVariables());
        $this->assertSame($operation, $request->getOperation());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testRawDataHasLowerPriorityThanJsonRequest(): void
    {
        $query     = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);
        $variables = [(string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX)];
        $operation = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $data = \json_encode([
            'query'         => $query,
            'variables'     => $variables,
            'operationName' => $operation,
        ]);

        $_GET = $_POST = $_REQUEST = $HTTP_GET_VARS = $HTTP_POST_VARS = $HTTP_RAW_POST_DATA = [
            'query'         => (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX),
            'variables'     => (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX),
            'operationName' => (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX),
        ];

        $request = $this->request($data);

        $this->assertSame($query, $request->getQuery());
        $this->assertSame($variables, $request->getVariables());
        $this->assertSame($operation, $request->getOperation());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testConfigurableRequest(): void
    {
        $query         = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);
        $queryArgument = '_' . (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $variables         = [(string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX)];
        $variablesArgument = '_' . (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $operation         = (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);
        $operationArgument = '_' . (string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX);

        $data = \json_encode([
            $queryArgument     => $query,
            $variablesArgument => $variables,
            $operationArgument => $operation,
        ]);

        /** @var ConfigurableRequest|RequestInterface $request */
        $request = $this->request($data);

        $request
            ->setQueryArgument($queryArgument)
            ->setVariablesArgument($variablesArgument)
            ->setOperationArgument($operationArgument);

        $this->assertSame($query, $request->getQuery());
        $this->assertSame($variables, $request->getVariables());
        $this->assertSame($operation, $request->getOperation());
    }
}
