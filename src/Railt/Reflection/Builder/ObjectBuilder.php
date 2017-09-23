<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Builder\Runtime\NamedTypeBuilder;
use Railt\Reflection\Builder\Support\Fields;
use Railt\Reflection\Contracts\Types\InterfaceType;
use Railt\Reflection\Contracts\Types\ObjectType;

/**
 * Class ObjectBuilder
 */
class ObjectBuilder implements ObjectType
{
    use Fields;
    use NamedTypeBuilder;

    private const AST_ID_IMPLEMENTS = '#Implements';

    /**
     * @var array|InterfaceType[]
     */
    private $interfaces = [];

    /**
     * ObjectBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootNamedTypeBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === self::AST_ID_IMPLEMENTS) {
            /** @var TreeNode $child */
            foreach ($ast->getChildren() as $child) {
                $this->addInterfaceRelation($child);
            }

            return true;
        }

        return false;
    }

    /**
     * @param TreeNode $child
     * @return void
     */
    private function addInterfaceRelation(TreeNode $child): void
    {
        $interface = $child->getChild(0)->getValueValue();

        $this->interfaces[$interface] = $this->getCompiler()->get($interface);
    }

    /**
     * @return iterable|InterfaceType[]
     */
    public function getInterfaces(): iterable
    {
        return \array_values($this->compiled()->interfaces);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasInterface(string $name): bool
    {
        return \array_key_exists($name, $this->compiled()->interfaces);
    }

    /**
     * @param string $name
     * @return null|InterfaceType
     */
    public function getInterface(string $name): ?InterfaceType
    {
        return $this->compiled()->interfaces[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfInterfaces(): int
    {
        return \count($this->compiled()->interfaces);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Object';
    }
}