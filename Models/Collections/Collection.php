<?php

namespace Models\Collections;

/**
 * Base class for all collections
 */
abstract class Collection
{
    /**
     * @var array List
     */
    protected $list;

    /**
     * @var array Map
     */
    protected $map;


    public function __construct()
    {
        $this->reset();
    }


    public function getListItem()
    {
        // TODO Implement
    }

    public function addListItem($item)
    {
        $this->list[] = $item;
        // TODO Get item ID in a list
        return null;
    }

    public function removeListItem($itemId)
    {
        // TODO Implement
        // TODO Return removed item
        return null;
    }

    public function getList()
    {
        return $this->list;
    }

    public function setList(array $list)
    {
        $this->list = $list;
        return $this;
    }


    public function getMapItem($key)
    {
        return isset($this->map[$key])
            ? $this->map[$key]
            : null;
    }

    public function addMapItem($key, $value)
    {
        $this->map[$key] = $value;
    }

    public function removeMapItem($key)
    {
        $mapItem = $this->getMapItem($key);
        unset($this->map[$key]);
        return $mapItem;
    }

    public function getMap()
    {
        return $this->map;
    }

    public function setMap(array $map)
    {
        $this->map = $map;
        return $this;
    }


    public function reset()
    {
        $this->list = [];
        $this->map = [];
    }
}