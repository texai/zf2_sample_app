<?php

namespace Product;


class Product
{
    private $code;

    private $name;

    private $description;

    private $categories;

    private $price;

    public function __construct(array $options = array())
    {
        $this->code        = (isset($options['code'])) ? $options['code'] : null;
        $this->name        = (isset($options['name'])) ? $options['name'] : null;
        $this->description = (isset($options['description'])) ? $options['description'] : null;
        $this->price       = (isset($options['price'])) ? $options['price'] : null;
        $this->categories  = (isset($options['categories'])) ? $options['categories'] : null;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDiscountPercentage()
    {
        return $this->getMainCategory()->getDiscountPercentage();
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getMainCategory()
    {
        return $this->categories['main'];
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setMainCategory(Category $category)
    {
        $this->categories['main'] = $category;
        return $this;
    }

    public function setCategories(array $categories)
    {
        $this->categories = $categories;
        return $this;
    }

    public function hasDiscount()
    {
        return (0.00 < $this->getMainCategory()->getDiscountPercentage());
    }
}
