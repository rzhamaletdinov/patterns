<?php
/**
 * =====================================
 *       USING OF ABSTRACT FACTORY
 * =====================================
 */

class Pattern
{
    static function process()
    {

        Config::$factory    = ManFactory::name();
        $man                = AbstractFactory::getPerson()->Sasha();
        Config::$factory    = WomanFactory::name();
        $woman              = AbstractFactory::getPerson()->Sasha();

        $man->getInfo();
        $woman->getInfo();
    }
}

/**
 * Какой-нибудь файл конфигурации
 */
class Config
{
    public static $factory = 1;
}

/**
 * Интерфейс человека
 */
interface PersonInterface
{
    public function getInfo();
}

/**
 * Абстрактный человек AbstractPerson
 *
 */
abstract class AbstractPerson implements PersonInterface
{
    public function getInfo()
    {
        print "\n****Person****\n";
        print "Name: "  . static::getName();
        print "\n";
        print "Sex:  "  . static::getSex();
        print "\n**************\n";
    }

    protected function getName()
    {
        return static::NAME;
    }

    protected function getSex()
    {
        return static::SEX;
    }
}

/**
 * Абстрактная фабрика
 */
abstract class AbstractFactory
{
    const NAME = NULL;
    /**
     * Возвращает фабрику
     *
     * @return AbstractFactory - дочерний объект
     * @throws Exception
     */
    public static function getPerson()
    {
        switch (Config::$factory) {
            case ManFactory::name():
                return new ManFactory();
            case WomanFactory::name():
                return new WomanFactory();
        }
        throw new Exception('Bad config');
    }

    public static function name()
    {
        return static::NAME;
    }


    /**
     * Возвращает человека Саша
     *
     * @return mixed
     */

    abstract public function Sasha();
}

/**
 * =====================================
 *             MAN FAMILY
 * =====================================
 */

class ManFactory extends AbstractFactory
{
    const NAME = 'Man';
    /**
     * Возвращает человека
     *
     * @return ManSasha
     */

    public function Sasha()
    {
        return new ManSasha();
    }
}

/**
 * Человек из первой фабрики
 */
class ManSasha extends AbstractPerson
{
    const NAME  = 'Sasha';
    const SEX   = 'Man';
}

/**
 * =====================================
 *             WOMAN FAMILY
 * =====================================
 */

class WomanFactory extends AbstractFactory
{
    const NAME = 'Woman';
    /**
     * Возвращает человека
     *
     * @return GirlSasha
     */
    public function Sasha()
    {
        return new GirlSasha();
    }
}

/**
 * Человек из второй фабрики
 */
class GirlSasha extends AbstractPerson
{
    const NAME = 'Sasha';
    const SEX = 'Woman';
}
Pattern::process();