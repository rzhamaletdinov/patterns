<?php

/**
 * Представьте, что у вас есть фабрика,
 * но вы не знаете, какая часть её функционала вам потребуется,
 * а какая – нет.
 * В таких случаях необходимые операции выполнятся
 * только если они нужны и только один раз
 *
 * Сохраняем в фабрике экземпляр объекта после создания
 */


class Pattern
{
    static function process()
    {
        /*
         * =====================================
         *      USING OF LAZY INITIALIZATION
         * =====================================
         */

        $factory = new Factory();

        print_r($factory->getFirstProduct()->getName());
        // The first product
        print_r($factory->getSecondProduct()->getName());
        // Second product
        print_r($factory->getFirstProduct()->getName());
        // The first product
    }
}


/**
 * Какой-то продукт
 */
interface Product
{
    /**
     * Возвращает название продукта
     *
     * @return string
     */
    public function getName();
}

class Factory
{

    /**
     * @var Product
     */
    protected $firstProduct;

    /**
     * @var Product
     */
    protected $secondProduct;


    /**
     * Возвращает продукт
     *
     * @return Product
     */
    public function getFirstProduct()
    {

        if (!$this->firstProduct) {
            $this->firstProduct = new FirstProduct();
        }
        return $this->firstProduct;
    }

    /**
     * Возвращает продукт
     *
     * @return Product
     */
    public function getSecondProduct()
    {

        if (!$this->secondProduct) {
            $this->secondProduct = new SecondProduct();
        }
        return $this->secondProduct;
    }
}

/**
 * Первый продукт
 */
class FirstProduct implements Product
{

    /**
     * Возвращает название продукта
     *
     * @return string
     */
    public function getName()
    {
        return 'The first product';
    }
}

/**
 * Второй продукт
 */
class SecondProduct implements Product
{

    /**
     * Возвращает название продукта
     *
     * @return string
     */
    public function getName()
    {
        return 'Second product';
    }
}

Pattern::process();