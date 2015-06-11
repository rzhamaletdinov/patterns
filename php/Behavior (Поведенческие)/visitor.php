<?php
/**
 * Посетитель (visitor)
 * описывает операцию,
 * которая выполняется над объектами других классов.
 * При изменении visitor нет необходимости изменять обслуживаемые классы.
 *
 * Шаблон демонстрирует
 * классический приём восстановления информации о потерянных типах,
 * не прибегая к понижающему приведению типов.
 *
 * ***********Решаемая проблема
 * Необходимо сделать какие-то несвязные операции над рядом объектов,
 * но нужно избежать загрязнения их кода.
 * И нет возможности или желания запрашивать тип каждого узла
 * и осуществлять приведение указателя к правильному типу,
 * прежде чем выполнить нужную операцию.
 *
 * ***********Задача
 * Над каждым объектом некоторой структуры выполняется
 * одна или более операций.
 * Нужно определить новую операцию,
 * не изменяя классы объектов.
 *
 * ***********Решение
 * Для независимости посетитель имеет отдельную иерархию.
 * Структуры имеют некий интерфейс взаимодействия.
 *
 * ***********Использование
 * Если есть вероятность изменения иерархии обслуживаемого класса,
 * либо она будет нестабильной или открытый интерфейс достаточно эффективен для доступа шаблона,
 * то его использование будет вредоносным.
 * Создается базовый класс Visitor с методами visit()
 * для каждого подкласса родительского Element.
 * Добавьте метод accept(visitor) в иерархию Element.
 * Для каждой операции,
 * которая должна выполняться для объектов Element,
 * создайте производный от Visitor класс.
 * Реализации метода visit() должны использовать
 * открытый интерфейс класса Element.
 * В результате:
 * клиенты создают объекты Visitor
 * и передают их каждому объекту Element,
 * вызывая accept().
 *
 * **********Рекомендации
 * Шаблон следует использовать, если:
 *      =имеются различные объекты разных классов с разными интерфейсами,
 * но над ними нужно совершать операции, зависящие от конкретных классов;
 *      =необходимо над структурой выполнить различные, усложняющие структуру операции;
 *      =часто добавляются новые операции над структурой.
 *
 * **********Преимущества:
 * упрощается добавление новых операций;
 * объединение родственных операции в классе Visitor;
 * класс Visitor может запоминать в себе какое-то состояние по ходу обхода контейнера.
 *
 * *********Недостатки:
 * затруднено добавление новых классов,
 * поскольку нужно обновлять иерархию посетителя и его сыновей.
 *
 *
 */

class Pattern
{
    static function process()
    {
        $app = new VisitorApplication();
        $app->run();
    }
}

/**
 * Интерфейс объекта над которым может выполняеться действие
 */
interface Obj {
    /**
     * @param Visitor $visitor
     * @param mixed $params
     */
    public function visit(Visitor $visitor, $params);
}
/**
 * Интерфейс посетителя
 */
interface Visitor {

    /**
     * Действие A
     * @param A $a
     * @param type $params
     */
    public function visitA(A $a, $params);

    /**
     * Действие B
     * @param B $b
     * @param type $params
     */
    public function visitB(B $b, $params);
}
/**
 * Класс А реализующий интерфейс Obj
 */
class A implements Obj {
    /**
     * Интерфейс для посетилеля
     * @param Visitor $visitor посетитель
     * @param mixed $params параметры
     */
    public function visit(Visitor $visitor, $params) {
        $visitor->visitA($this, $params);
    }
}
/**
 * Класс B реализующий интерфейс Obj
 */
class B implements Obj {
    /**
     * Интерфейс для посетителя
     * @param Visitor $visitor
     * @param type $params
     */
    public function visit(Visitor $visitor, $params) {
        $visitor->visitB($this, $params);
    }
}
/**
 * Посетитель
 */
class Visitor1 implements Visitor {
    /**
     * Действие которое выполняет посетитель
     */
    public function visitA(A $a, $params){
        echo sprintf("Execute method visitA in class Visitor1, params %s\n", $params);
    }

    /**
     * Действие которое выполняет посетитель
     */
    public function visitB(B $b, $params){

        echo sprintf("Execute method visitB in class Visitor1, params %s\n", $params);
    }
}
/**
 * Посетитель 2
 */
class Visitor2 implements Visitor {
    /**
     * Действие которое выполняет посетитель
     */
    public function visitA(A $a, $params){

        echo sprintf("Execute method visitA in class Visitor2, params %s\n", $params);
    }

    /**
     * Действие которое выполняет посетитель
     */
    public function visitB(B $b, $params){

        echo sprintf("Execute method visitB in class Visitor1, params %s\n", $params);
    }
}
class VisitorApplication {
    public function run(){
        $a = new A();
        $b = new B();

        $a->visit(new Visitor1(), 'visitor 1 visited class A');
        $b->visit(new Visitor1(), 'visitor 1 visited class B');

        $a->visit(new Visitor2(), 'visitor 2 visited class A');
        $b->visit(new Visitor2(), 'visitor 2 visited class B');
    }
}
Pattern::process();