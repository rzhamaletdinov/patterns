<?php

/**
 * Паттерн Компоновщик (Composite)
 *
 * Объединяет объекты в древовидную структуру
 * для представления иерархии от частного к целому.
 * Компоновщик позволяет клиентам обращаться
 * к отдельным объектам и к группам объектов одинаково.
 *
 * ************* Цель
 * Паттерн определяет иерархию классов,
 * которые одновременно могут состоять из примитивных и сложных объектов,
 * упрощает архитектуру клиента,
 * делает процесс добавления новых видов объекта более простым.
 *
 *
 */

    class Pattern
    {
        public static function process()
        {
            $pull           = new Composite("#Pull");

            $prod_server    = new Composite("prod_server");
            $system_server  = new Composite("system_server");
            $test_server    = new Composite("test_server");
            $dev_server     = new Composite("dev_server");

            $pull->add($prod_server);
            $pull->add($system_server);
            $pull->add($test_server);
            $pull->add($dev_server);

            $prod_server->add(new Leaf("Game1"));
            $prod_server->add(new Leaf("Game2"));

            $system_server->add(new Leaf("Tools"));
            $system_server->add(new Leaf("Backup"));
            $system_server->add(new Leaf("GitRepository"));

            $test_server->add(new Leaf("SoftLaunchGame"));

            $dev_server->add(new Leaf("Huyak-huyak"));

            $client = new Client($pull);

            $client->printOperation1();
            $client->printOperation2();
        }
    }
    /**
     * Паттерн-компоновщик с внешним итератором
     * Итератор использует рекурсию для перебора дерева элементов
     */
    /**
     * Клиент использует интерфейс AComponent для работы с объектами.
     * Интерфейс AComponent определяет интерфейс для всех компонентов: как комбинаций, так и листовых узлов.
     * AComponent может реализовать поведение по умолчанию для add() remove() getChild() и других операций
     */
    abstract class AComponent
    {

        public $customPropertyName;

        public $customPropertyDescription;

        /**
         * @param AComponent $component
         */
        public function add($component)
        {
            throw new \Exception("Unsupported operation");
        }

        /**
         * @param AComponent $component
         */
        public function remove($component)
        {
            throw new \Exception("Unsupported operation");
        }

        /**
         * @param int $int
         */
        public function getChild($int)
        {
            throw new \Exception("Unsupported operation");
        }

        /**
         * @return  IPhpLikeIterator
         */
        abstract function createIterator();

        public function operation1()
        {
            throw new \Exception("Unsupported operation");
        }
    }

    /**
     * Leaf наследует методы add() remove() getChild( которые могут не иметь смысла для листового узла.
     * Хотя листовой узер можно считать узлом с нулём дочерних объектов
     *
     * Leaf определяет поведение элементов комбинации. Для этого он реализует операции, поддерживаемые интерфейсом Composite.
     */
    class Leaf extends AComponent
    {
        public function __construct($name, $description = '')
        {
            $this->customPropertyName = $name;
            $this->customPropertyDescription = $description;
        }

        public function createIterator()
        {
            return new NullIterator();
        }

        public function operation1()
        {
            echo ("\n \t\t {$this->customPropertyName}");
//            echo ("\n I'am leaf {$this->customPropertyName}, i don't want to do operation 1. {$this->customPropertyDescription}");
        }
    }

    class NullIterator implements IPhpLikeIterator
    {
        public function valid()
        {
            return (false);
        }

        public function next()
        {
            return (false);
        }

        public function current()
        {
            return (null);
        }

        public function remove()
        {
            throw new \CException('unsupported operation');
        }
    }

    /**
     * Интерфейс Composite определяет поведение компонентов, имеющих дочерние компоненты, и обеспечивает хранение последних.
     *
     * Composite также реализует операции, относящиеся к Leaf. Некоторые из них не могут не иметь смысла для комбинаций; в таких случаях генерируется исключение.
     */
    class Composite extends AComponent
    {

        private $_iterator = null;

        /**
         * @var \ArrayObject AComponent[] $components для хранения потомков типа AComponent
         */
        public $components = null;

        public function __construct($name, $description = '')
        {
            $this->customPropertyName = $name;
            $this->customPropertyDescription = $description;
        }

        /**
         * @param AComponent $component
         */
        public function add($component)
        {
            if (is_null($this->components)) {
                $this->components = new \ArrayObject;
            }
            $this->components->append($component);
        }

        public function remove($component)
        {
            foreach ($this->components as $i => $c) {
                if ($c === $component) {
                    unset($this->components[$i]);
                }
            }
        }

        public function getChild($int)
        {
            return ($this->components[$int]);
        }

        public function operation1()
        {
            echo "\n\n $this->customPropertyName $this->customPropertyDescription";
            echo "\n --------------------------------";

            $iterator = $this->components->getIterator();
            while ($iterator->valid()) {
                $component = $iterator->current();
                $component->operation1();
                $iterator->next();
            }
        }

        /**
         * @return CompositeIterator
         */
        public function createIterator()
        {
            if (is_null($this->_iterator)) {
                $this->_iterator = new CompositeIterator($this->components->getIterator());
            }
            return ($this->_iterator);
        }
    }

    /**
     *  Рекурсивный итератор компоновщика
     */
    class CompositeIterator implements IPhpLikeIterator
    {

        public $stack = array();

        /**
         * @param \ArrayIterator $componentsIterator
         */
        public function __construct($componentsIterator)
        {
            //$this->stack= new \ArrayObject;
            $this->stack[] = $componentsIterator;
        }

        public function remove()
        {
            throw new \CException('unsupported operation');
        }

        public function valid()
        {
            if (empty($this->stack)) {
                return (false);
            } else {
                /** @var $componentsIterator \ArrayIterator */
                // берём первый элемент
                $componentsIterator = array_shift(array_values($this->stack));
                if ($componentsIterator->valid()) {
                    return (true);
                } else {
                    array_shift($this->stack);
                    return ($this->valid());
                }
            }
        }

        public function next()
        {
            /** @var $componentsIterator \ArrayIterator */
            $componentsIterator = current($this->stack);
            $component = $componentsIterator->current();
            if ($component instanceof Composite) {
                array_push($this->stack, $component->createIterator());
            }
            $componentsIterator->next();
            //return($component);
        }

        public function current()
        {
            if ($this->valid()) {
                /** @var $componentsIterator \ArrayIterator */
                // берём первый элемент
                $componentsIterator = array_shift(array_values($this->stack));
                return ($componentsIterator->current());
            } else {
                return (null);
            }
        }
    }

    /**
     * Интерфейс Iterator должен быть реализован всеми итераторами.
     * Данный интерфейс явялется частью интерфейса стандартного php итератора.
     * Конкретный Iterator отвечает за управление текущей позицией перебора в конкретной коллекции.
     */
    interface IPhpLikeIterator
    {
        /**
         * @abstract
         * @return boolean есть ли текущий элемент
         */
        public function valid();

        /**
         * @abstract
         * @return mixed перевести курсор дальше
         */
        public function next();

        /**
         * @abstract
         * @return mixed получить текущий элемент
         */
        public function current();

        /**
         * удалить текущий элемент коллекции
         * @abstract
         * @return void
         */
        public function remove();
    }


    class Client
    {
        /**
         * @var AComponent
         */
        public $topItem;

        public function __construct($topItem)
        {
            $this->topItem = $topItem;
        }

        public function printOperation1()
        {
            $this->topItem->operation1();
        }

        public function printOperation2()
        {
            echo "\n\n\n";
            $iterator = $this->topItem->createIterator();
            while ($iterator->valid()) {
                /** @var $component AComponent */
                $component = $iterator->current();
                if (strstr($component->customPropertyName, 'leaf1')) {
                    echo ("\n I'm Client, I found leaf {$component->customPropertyName}, I'll just leave it here (for my 'first-leafs' tea collection). {$component->customPropertyDescription}");
                }
                $iterator->next();
            }
        }
    }
    Pattern::process();