<?php

namespace Knp\Menu;

use Knp\Menu\Factory\CoreExtension;
use Knp\Menu\Factory\ExtensionInterface;

/**
 * Factory to create a menu from a tree
 */
class MenuFactory implements FactoryInterface
{
    /**
     * @var array[]
     */
    private $extensions = array();

    /**
     * @var ExtensionInterface[]
     */
    private $sorted;

    public function __construct()
    {
        $this->addExtension(new CoreExtension(), -10);
    }

    public function createItem($item, array $options = array(), ItemInterface $parent = null)
    {
        $item = new MenuItem($item, $this);

        if (null !== $parent) {
            $this->addChild($parent, $item);
        }

        foreach ($this->getExtensions() as $extension) {
            $options = $extension->buildOptions($options);
        }

        foreach ($this->getExtensions() as $extension) {
            $extension->buildItem($item, $options);
        }

        return $item;
    }

    public function addChild(ItemInterface $parent, ItemInterface $child)
    {
        $child->setParent($parent);
        $children = $parent->getChildren();
        $children[$child->getName()] = $child;
        $parent->setChildren($children);
    }

    /**
     * Adds a factory extension
     *
     * @param ExtensionInterface $extension
     * @param integer            $priority
     */
    public function addExtension(ExtensionInterface $extension, $priority = 0)
    {
        $this->extensions[$priority][] = $extension;
        $this->sorted = null;
    }

    /**
     * Sorts the internal list of extensions by priority.
     *
     * @return ExtensionInterface[]
     */
    private function getExtensions()
    {
        if (null === $this->sorted) {
            krsort($this->extensions);
            $this->sorted = !empty($this->extensions) ? call_user_func_array('array_merge', $this->extensions) : array();
        }

        return $this->sorted;
    }
}
