<?php
/**
 * Iterator — поведенческий шаблон проектирования.
 *
 * Представляет собой объект, позволяющий получить
 * последовательный доступ к элементам объекта-агрегата
 * без использования описаний каждого из агрегированных объектов.
 *
 * Например, такие элементы как дерево, связанный список, хэш-таблица и массив
 * могут быть пролистаны (и модифицированы) с помощью объекта Итератор.
 *
 * Перебор элементов выполняется объектом итератора, а не самой коллекцией.
 * Это упрощает интерфейс и реализацию коллекции,
 * а также способствует более логичному разделению обязанностей.
 *
 * Особенностью полноценно реализованного итератора является то,
 * что код, использующий итератор,
 * может ничего не знать о типе итерируемого агрегата.
 * Конечно же, (в С++) почти в любом агрегате можно выполнять итерации указателем void*, но при этом:
 * не ясно, что является значением «конец агрегата»,
 * для двусвязного списка это &ListHead,
 * для массива это &array[size],
 * для односвязного списка это NULL
 * операция Next сильно зависит от типа агрегата.
 *
 * Итераторы позволяют абстрагироваться от типа и
 * признака окончания агрегата,
 * используя полиморфный Next
 * (часто реализованный как operator++ в С++)
 * и полиморфный aggregate.end(),
 * возвращающий значение «конец агрегата».
 */


/**
 * Паттерн итератор предоставляет механизм
 * последовательного перебора элементов коллекции
 * без раскрытия реализации коллекции.
 *
 * Перебор элементов выполняется объектом итератора,
 * а не самой коллекцией.
 * Это упрощает интерфейс и реализацию коллекции,
 * а также способствует более логичному
 * распределению обязанностей.
 */

    class Pattern
    {
        static function process()
        {
            $runner = new Client(new FrontendCollection(), new BackendCollection());
            $runner->printAllCollections();
        }
    }

    /**
     * Наличие общего интерфейса удобно для клиента,
     * поскольку клиент отделяется от реализации коллекции объектов.
     *
     * ConcreteCollection содержит коллекцию объектов и реализует метод,
     * который возвращает итератор для этой коллекции.
     */
    interface ICollection
    {
        /**
         * Каждая разновидность ConcreteCollection отвечает за создание экземпляра Concrete Iterator,
         * который может использоваться для перебора своей коллекции объектов.
         */
        public function createIterator();
    }

    /**
     * Интерфейс Iterator должен быть реализован всеми итераторами.
     *
     * ConcreteIterator отвечает за управление текущей позицией перебора.
     */
    interface IIterator
    {
        /**
         * @abstract
         * @return boolean есть ли следующий элемент в коллекции
         */
        public function hasNext();

        /**
         * @abstract
         * @return mixed следующий элемент массива
         */
        public function next();

        /**
         * Удаляет текущий элемент коллекции
         * @abstract
         * @return void
         */
        public function remove();
    }

    /**
     * В моём примере обе коллекции используют одинаковый итератор - итератор массива.
     */
    class FrontendCollection implements ICollection
    {
        /**
         * @var Item[] $items
         */
        public $items = array();

        public function __construct()
        {
            $skill = [
                'less'      => 3,
                'js'        => 3,
                'bootstrap' => 4,
                'html5'     => 5,
                'angularjs' => 5
            ];

            $this->items = [
                new FrontendProgrammer('Вася', $skill),
                new FrontendProgrammer('Петя', $skill),
                new FrontendProgrammer('Леша', $skill),
            ];
        }

        public function createIterator()
        {
            return new ConcreteIterator1($this->items);
        }
    }

    class BackendCollection implements ICollection
    {
        /**
         * @var Item[] $items
         */
        public $items = array();

        public function __construct()
        {
            $skill = [
                'oop'           => 4,
                'php'           => 4,
                'nodejs'        => 3,
                'mysql'         => 4,
                'mongodb'       => 3
            ];

            $this->items = [
                new BackendProgrammer('Юля',  $skill),
                new BackendProgrammer('Катя', $skill),
                new BackendProgrammer('Вика', $skill),
            ];
        }

        public function createIterator()
        {
            return new ConcreteIterator1($this->items);
        }
    }

    class ConcreteIterator1 implements IIterator
    {
        /**
         * @var Item[] $items
         */
        protected $items = array();

        /**
         * @var int $position хранит текущую позицию перебора в массиве
         */
        public $position = 0;

        /**
         * @param $items массив объектов, для перебора которых создается итератор
         */
        public function __construct($items)
        {
            $this->items = $items;
        }

        public function hasNext()
        {
            if ($this->position >= count($this->items) || count($this->items) == 0) {
                return (false);
            } else {
                return (true);
            }
        }

        public function next()
        {
            $menuItem = $this->items[$this->position];
            $this->position++;
            return ($menuItem);
        }

        public function remove()
        {
            if ($this->position <= 0) {
                throw new \Exception('Нельзя вызывать remove до вызова хотя бы одного next()');
            }
            if ($this->items[$this->position - 1] != null) {
                for ($i = $this->position - 1; $i < count($this->items); $i++) {
                    $this->items[$i] = $this->items[$i + 1];
                }
                $this->items[count($this->items) - 1] = null;
            }
        }
    }

    class Client
    {

        /**
         * @var FrontendProgrammer $FrontendProgrammer
         */
        public $FrontendProgrammer;
        /**
         * @var BackendProgrammer $BackendProgrammer
         */
        public $BackendProgrammer;

        public function __construct($FrontendProgrammer, $BackendProgrammer)
        {
            $this->FrontendProgrammer = $FrontendProgrammer;
            $this->BackendProgrammer = $BackendProgrammer;
        }

        public function printAllCollections()
        {
            echo "\n";
            $iterator1 = $this->FrontendProgrammer->createIterator();
            echo "\nCollection : FrontendProgrammer \n";
            $this->formatCollection($iterator1);

            $iterator2 = $this->BackendProgrammer->createIterator();
            echo "\nCollection : BackendProgrammer \n";
            $this->formatCollection($iterator2);
        }

        /**
         * @param $iterator IIterator
         */
        private function formatCollection($iterator)
        {
            echo "=============================\n";
            while ($iterator->hasNext()) {
                $item = $iterator->next();
                $item->formatItem();
            }
        }
    }

    abstract class Item
    {
        public $name;

        protected $_id;
        protected static $inc = 0;

        public function __construct($name, array $skills)
        {
            $this->_id  = ++self::$inc;
            $this->name = $name;
            $this->_skills = new SkillList($skills);
        }

        public function id()
        {
            return $this->_id;
        }

        public function formatItem()
        {
            echo "---------------------------\n";
            echo "ID: \t" .   $this->id() . "\n";
            echo "Name: \t" . $this->name . "\n";
            echo "Skills: \n";
            echo $this->_skills->formatSkill() . "\n";
            echo "---------------------------\n";
            echo "\n";
        }

    }

    class FrontendProgrammer extends Item
    {
        protected $_skills;

        function __construct($name, array $skills)
        {
            /* Special logic */
            parent::__construct($name, $skills);
        }
    }

    class BackendProgrammer extends Item
    {
        protected $_skills;

        function __construct($name, array $skills)
        {
            /* Special logic */
            parent::__construct($name, $skills);
        }
    }

    class SkillList
    {
        protected $_items;

        function __construct(array $argc)
        {
            foreach ($argc as $skill => $value)
                $this->_items[$skill] = $value;
        }

        function formatSkill()
        {
            foreach ($this->_items as $skill => $value)
                echo "\t" . $skill . ": " . $value . "\n";
        }
    }

    Pattern::process();