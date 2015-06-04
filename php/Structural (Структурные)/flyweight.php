<?php
/**
 * Приспособленец (Flyweight, "легковесный (элемент)") —
 * структурный шаблон проектирования, при котором объект,
 * представляющий себя как уникальный экземпляр в разных местах программы,
 * по факту не является таковым.
 *
 * ************ Цель
 * Оптимизация работы с памятью,
 * путем предотвращения создания экземпляров элементов,
 * имеющих общую сущность.
 *
 * ************ Описание
 * Flyweight используется для уменьшения затрат
 * при работе с большим количеством мелких объектов.
 * При проектировании приспособленца необходимо
 * разделить его свойства на внешние и внутренние.
 * Внутренние свойства всегда неизменны,
 * тогда как внешние могут отличаться
 * в зависимости от места и контекста применения
 * и должны быть вынесены за пределы приспособленца.
 * Flyweight дополняет шаблон Factory Method таким образом,
 * что при обращении клиента к Factory Method
 * для создания нового объекта
 * ищет уже созданный объект с такими же параметрами,
 * что и у требуемого, и возвращает его клиенту.
 * Если такого объекта нет, то фабрика создаст новый.
 */

class Pattern
{
    static function process()
    {

        $document="AAZZBBZB";
        // Build a document with text
        $chars=str_split($document);
        print_r($chars);

        $f = new CharacterFactory();

        // extrinsic state
        $pointSize = 0;

        // For each character use a flyweight object
        foreach ($chars as $key) {
            $pointSize++;
            $character = $f->GetCharacter($key);
            $character->Display($pointSize);
        }
    }
}


// "FlyweightFactory"
class CharacterFactory
{
    private $characters = array();
    public function GetCharacter($key)
    {
        // Uses "lazy initialization"
        if (!array_key_exists($key, $this->characters))
        {
            switch ($key)
            {
                case 'A': $this->characters[$key] = new CharacterA(); break;
                case 'B': $this->characters[$key] = new CharacterB(); break;
                case 'Z': $this->characters[$key] = new CharacterZ(); break;
            }
        }
        return $this->characters[$key];
    }
}

// "Flyweight"
abstract class Character
{
    protected $symbol;
    protected $width;
    protected $height;
    protected $ascent;
    protected $descent;
    protected $pointSize;

    public function Display($pointSize)
    {
        $this->pointSize = $pointSize;
        print ($this->symbol." (pointsize ".$this->pointSize.")\n");
    }
}

// "ConcreteFlyweight"

class CharacterA extends Character
{
    public function __construct()
    {
        $this->symbol = 'A';
        $this->height = 100;
        $this->width = 120;
        $this->ascent = 70;
        $this->descent = 0;
    }
}

// "ConcreteFlyweight"

class CharacterB extends Character
{
    public function __construct()
    {
        $this->symbol = 'B';
        $this->height = 100;
        $this->width = 140;
        $this->ascent = 72;
        $this->descent = 0;
    }
}

// ... C, D, E, etc.

// "ConcreteFlyweight"

class CharacterZ extends Character
{
    public function __construct()
    {
        $this->symbol = 'Z';
        $this->height = 100;
        $this->width = 100;
        $this->ascent = 68;
        $this->descent = 0;
    }
}
Pattern::process();
