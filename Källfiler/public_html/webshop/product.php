<?php

class product {
    public $id;
    public $name;
    public $price;
    public $category;
    public $stock;
    public $description;
    public $imageurl;

    public function __construct($id, $name, $price, $category, $stock, $description, $imageurl){
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
        $this->stock = $stock;
        $this->description = $description;
        $this->imageurl = $imageurl;

    }
} 