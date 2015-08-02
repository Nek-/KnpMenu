<?php

namespace Knp\Menu;

/**
 * Interface implemented by the factory to create items
 */
interface FactoryInterface
{
    /**
     * Creates a menu item
     *
     * @param string $name
     * @param array  $options
     *
     * @return ItemInterface
     */
    public function createItem($name, array $options = array());


    /**
     * Add a child to the defined parent
     *
     * @param ItemInterface $parent
     * @param ItemInterface $child
     */
    public function addChild(ItemInterface $parent, ItemInterface $child);
}
