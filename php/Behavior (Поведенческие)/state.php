<?php
/**
 * Состояние (State)
 * Используется в тех случаях,
 * когда во время выполнения программы
 * объект должен менять свое поведение
 * в зависимости от своего состояния.
 *
 * Паттерн состоит из 3 блоков:
 *      Widget — класс,
 * объекты которого должны менять
 * свое поведение в зависимости от состояния.
 *      IState — интерфейс,
 * который должен реализовать каждое из конкретных состояний.
 * Через этот интерфейс объект Widget взаимодействует с состоянием,
 * делегируя ему вызовы методов.
 * Интерфейс должен содержать средства для обратной связи с объектом,
 * поведение которого нужно изменить.
 * Для этого используется событие (паттерн Publisher — Subscriber).
 * Это необходимо для того,
 * чтобы в процессе выполнения программы заменять объект состояния при появлении событий.
 * Возможны случаи,
 * когда сам Widget периодически опрашивает объект состояние на наличие перехода.
 *
 *      StateA … StateZ — классы конкретных состояний.
 * Должны содержать информацию о том,
 * при каких условиях и в какие состояния
 * может переходить объект из текущего состояния.
 * Например, из StateA объект может переходить в состояние StateB и StateC,
 * а из StateB — обратно в StateA и так далее.
 * Объект одного из них должен содержать Widget при создании.
 *
 *
 */

/**
 * Паттерн Состояние управляет изменением поведения объекта при изменении его внутреннего состояния.
 * Внешне это выглядит так, словно объект меняет свой класс.
 */

    class Pattern
    {
        public static function process()
        {
            $client = new Client();
        }
    }

    class Client
    {
        public function __construct()
        {
            $context = new Context();
            $context->request();
            $context->request();
            $context->request();
            $context->request();
            $context->request();
            $context->request();
        }
    }

    /**
     *  Класс с несколькими внутренними состояниями
     */
    class Context
    {
        /**
         * @var AState
         */
        public $state;

        const STATE_A = 1;
        const STATE_B = 2;
        const STATE_C = 3;

        public function __construct()
        {
            $this->setState(Context::STATE_A);
        }

        /**
         * Действия Context делегируются объектам состояний для обработки
         */
        public function request()
        {
            $this->state->handle();
        }

        /**
         * Это один из способов реализации переключения состояний
         * @param $state выбранное состояние, возможные варианты перечислены в списке констант Context::STATE_..
         */
        public function setState($state)
        {
            if ($state == Context::STATE_A) {
                $this->state = new ConcreteStateA($this);
            } elseif ($state == Context::STATE_B) {
                $this->state = new ConcreteStateB($this);
            } elseif ($state == Context::STATE_C) {
                $this->state = new ConcreteStateC($this);
            }
        }

    }

    /**
     * Общий интерфейс всех конкретных состояний.
     * Все состояния реализуют один интерфейс, а следовтельно, являются взаимозаменяемыми.
     */
    class AState
    {
        /**
         * @var Context храним ссылку на контекст для удобного переключения состояний
         */
        protected $context;

        public function __construct(&$context)
        {
            $this->context =& $context;
        }

        /**
         * Обработка в разных состояниях может отличаться.
         * Если AState не просто интерфейс а абстрактный класс,
         * то он может содержать стандартные обработки, тогда классы конкретных состояний будут описывать только свои особенности относительно стандартного поведения.
         */
        public function handle()
        {
            echo "\n standart handle";
        }

    }

    /**
     * Далее идёт набор конкретных состояний, которые обрабатывают запросы от Context.
     * Каждый класс предоставляет собственную реализацию запроса.
     * Таким образом, при переходе объекта Context в другое состояние, меняется и его повденеие.
     */

    class ConcreteStateA extends AState
    {
        public function handle()
        {
            echo "\n State A handle";
            // переключаем состояние
            $this->context->setState(Context::STATE_B);
        }

    }

    class ConcreteStateB extends AState
    {
        public function handle()
        {
            echo "\n State B handle";
            // переключаем состояние
            $this->context->setState(Context::STATE_C);
        }
    }

    class ConcreteStateC extends AState
    {
        public function handle()
        {
            echo "\n State C handle";
            // переключаем состояние
            $this->context->setState(Context::STATE_A);
        }
    }
    Pattern::process();