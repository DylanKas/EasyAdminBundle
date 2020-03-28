<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Collection;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Collection\CollectionInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\ActionDto;

final class ActionCollection implements CollectionInterface
{
    /** @var ActionDto[] */
    private $actions;

    /**
     * @param Action[] $actions
     */
    private function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    public function __clone()
    {
        foreach ($this->actions as $actionName => $actionDto) {
            $this->actions[$actionName] = clone $actionDto;
        }
    }

    /**
     * @param ActionDto[] $actions
     */
    public static function new(array $actions): ActionCollection
    {
        return new self($actions);
    }

    /**
     * @return ActionDto[]
     */
    public function all(): array
    {
        return $this->actions;
    }

    public function getGlobalActions(): self
    {
        $globalActions = array_filter($this->actions, static function (ActionDto $action) {
            return $action->isGlobalAction();
        });

        return self::new($globalActions);
    }

    public function getBatchActions(): self
    {
        $batchActions = array_filter($this->actions, static function (ActionDto $action) {
            return $action->isBatchAction();
        });

        return self::new($batchActions);
    }

    public function getEntityActions(): self
    {
        $entityActions = array_filter($this->actions, static function (ActionDto $action) {
            return $action->isEntityAction();
        });

        return self::new($entityActions);
    }

    public function get(string $actionName): ?ActionDto
    {
        return $this->actions[$actionName] ?? null;
    }

    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->actions);
    }

    public function offsetGet($offset)
    {
        return $this->actions[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->actions[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->actions[$offset]);
    }

    public function count(): int
    {
        return \count($this->actions);
    }

    /**
     * @return \ArrayIterator|\Traversable|ActionDto[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->actions);
    }
}