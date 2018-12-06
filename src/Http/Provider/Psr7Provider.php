<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Psr7Provider
 */
class Psr7Provider extends DataProvider
{
    /**
     * @var bool
     */
    private $bodyInitialized = false;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * RequestProvider constructor.
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        parent::__construct((array)$request->getQueryParams(), (array)$request->getParsedBody());
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getBody(): string
    {
        if ($this->bodyInitialized === false) {
            $this->bodyInitialized = true;
            $this->withBody($this->request->getBody()->getContents());
        }

        return parent::getBody();
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->request->getHeaderLine('Content-Type');
    }
}
