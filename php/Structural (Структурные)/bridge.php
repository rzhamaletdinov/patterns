<?php

/**
 * Шаблон мост (Bridge) — структурный шаблон проектирования,
 * используемый в проектировании чтобы «разделять абстракцию и реализацию так,
 * чтобы они могли изменяться независимо».
 * Шаблон использует инкапсуляцию, агрегирование
 * и может использовать наследование для того,
 * чтобы разделить ответственность между классами.
 * ******************* Цель
 * При частом изменении класса преимущества ооп становятся очень полезными,
 * позволяя делать изменения в программе,
 * обладая минимальными сведениями о реализации программы.
 * Шаблон bridge является полезным там,
 * где часто меняется не только сам класс,
 * но и то, что он делает.
 * ****************** Описание
 * Когда абстракция и реализация разделены, они могут изменяться независимо.
 * Другими словами, при реализации через паттерн мост,
 * изменение структуры интерфейса не мешает изменению структуры реализации.
 * ****************** Пример
 * Рассмотрим такую абстракцию как фигура.
 * Существует множество типов фигур, каждая со своими свойствами и методами.
 * Однако есть что-то, что объединяет все фигуры.
 * Например, каждая фигура должна уметь рисовать себя,
 * масштабироваться и т. п.
 * В то же время рисование графики может отличаться в зависимости от типа ОС,
 * или графической библиотеки.
 * Фигуры должны иметь возможность рисовать себя в различных графических средах,
 * но реализовывать в каждой фигуре все способы рисования
 * или модифицировать фигуру каждый раз при изменении способа рисования непрактично.
 * В этом случае помогает шаблон bridge, позволяя создавать новые классы,
 * которые будут реализовывать рисование в различных графических средах.
 * При использовании такого подхода очень легко можно добавлять как новые фигуры,
 * так и способы их рисования.
 * Мост служит именно для решения этой проблемы:
 * объекты создаются парами из объекта класса иерархии А и иерархии B,
 * а для понятия "реализация абстракции" используется ссылка из объекта A в парный ему объект B.
 */


interface IDrawer
{
    public function drawCircle($x, $y, $radius);
}


/**
 * Реализация интерфейся для маленького круга
 */
class SmallCircleDrawer implements IDrawer
{
    const RADIUS_MULTIPLIER = 0.25;

    public function drawCircle($x, $y, $radius)
    {
        echo 'Small circle center = ( '.$x.', '.$y.' ) radius = '.($radius * self::RADIUS_MULTIPLIER)."\n";
    }
}

/**
 * Реализация интерфейся для Большого круга
 */
class LargeCircleDrawer implements IDrawer
{
    const RADIUS_MULTIPLIER = 10;

    public function drawCircle($x, $y, $radius)
    {
        echo 'Large circle center = ( '.$x.', '.$y.' ) radius = '.($radius * self::RADIUS_MULTIPLIER)."\n";
    }
}


abstract class Shape
{
    protected $drawer;

    /**
     * принимает реализацию интерфейса в протектед переменную
     */
    protected function __construct(IDrawer $drawer)
    {
        $this->drawer = $drawer;
    }

    abstract public function draw();
    abstract public function enlargeRadius($multiplier);
}

class Circle extends Shape
{
    private $x;
    private $y;
    private $radius;

    public function __construct($x, $y, $radius, IDrawer $drawer)
    {
         //храним интерфейс в родителе
        parent::__construct($drawer);
        $this->x = $x;
        $this->y = $y;
        $this->radius = $radius;
    }

    public function draw()
    {
        //делегируем реализацию конкретному классу-рисовальщику
        $this->drawer->drawCircle($this->x, $this->y, $this->radius);
    }

    public function enlargeRadius($multiplier)
    {
        $this->radius *= $multiplier;
    }
}

/**
 * снаружи все различается только передачей класса-рисовальщика в конструктор фигуры
 * дальше все универсально для всех фигур
 */

$circle = new Circle(5, 10, 10, new LargeCircleDrawer());
$circle->draw();	// Large circle center = ( 5, 10 ) radius = 100
$circle = new Circle(20, 30, 100, new SmallCircleDrawer());
$circle->draw();	// Small circle center = ( 20, 30 ) radius = 25