<?php
/**
 * Хранитель (Memento)
 * позволяет, не нарушая инкапсуляцию,
 * зафиксировать и сохранить внутреннее состояние объекта так,
 * чтобы позднее восстановить его в это состояние.
 *
 * *********Применение
 * Шаблон Хранитель используется, когда:
 * = необходимо сохранить снимок состояния объекта
 * (или его части) для последующего восстановления
 * = прямой интерфейс получения состояния объекта
 * раскрывает детали реализации и нарушает инкапсуляцию объекта
 *
 * *********Классический вариант
 * Шаблон Хранитель используется двумя объектами:
 * "Создателем" (originator) и "Опекуном" (caretaker).
 * "Создатель" - это объект, у которого есть внутреннее состояние.
 * Объект "Опекун" может производить некоторые действия с "Создателем",
 * но при этом необходимо иметь возможность откатить изменения.
 * Для этого "Опекун" запрашивает у "Создателя" объект "Хранителя".
 * Затем выполняет запланированное действие (или последовательность действий).
 * Для выполнения отката "Создателя" к состоянию, которое предшествовало изменениям,
 * "Опекун" возвращает объект "Хранителя" его "Создателю".
 * "Хранитель" является непрозрачным
 * (т.е. таким, который не может или не должен изменяться "Опекуном").
 *
 * **********Нестандартный вариант
 * Отличие данного варианта от классического заключено в
 * более жёстком ограничении на доступ "Опекуна"
 * к внутреннему состоянию "Создателя".
 * В классическом варианте у "Опекуна"
 * есть потенциальная возможность
 * получить доступ к внутренним данным "Создателя" через "Хранителя",
 * изменить состояние и установить его обратно "Создателю".
 * В данном варианте "Опекун" обладает возможностью лишь
 * восстановить состояние "Хранителя", вызвав Restore.
 * Кроме всего прочего,
 * "Опекуну" не требуется владеть связью на "Хранителя",
 * чтобы восстановить его состояние.
 * Это позволяет сохранять и восстанавливать состояние
 * сложных иерархических или сетевых структур
 * (состояния объектов и всех связей между ними)
 * путём сбора снимков всех зарегистрированных объектов системы.
 */

class Pattern
{
    static function process()
    {
        $originator = new Originator();
        $originator->setState("On");
        $originator->getState();

        // Store internal state
        $caretaker = new Caretaker();
        $caretaker->setMemento($originator->CreateMemento());

        // Continue changing originator
        $originator->setState("Off");

        // Restore saved state
        $originator->setMemento($caretaker->getMemento());
        $originator->getState();
    }
}


/*
 * Паттерн хранитель, используется для хранения и восстановления состояния объекта
 */
    /**
     * Класс поддерживаюший сохранение состояний внутреннего состояния
     */
    class Originator {

        private $state;

        public function setState($state) {
            $this->state = $state;
            echo sprintf("State setted %s\n", $this->state);
        }

        public function getState() {
            echo sprintf("Current state: " . $this->state . "\n");
            return $this->state;
        }

        /**
         * Создать снимок состояния объекта
         * @return Memento
         */
        public function CreateMemento() {
            return new Memento($this->state);
        }

        /**
         * Восстановить состояние
         * @param \Memento\Memento $memento
         */
        public function setMemento(Memento $memento) {
            echo sprintf("Restoring state...\n");
            $this->state = $memento->getState();
        }

    }

    /**
     * Хранитель состояния
     */
    class Memento {

        private $state;

        public function __construct($state) {
            $this->state = $state;
        }

        public function getState() {
            return $this->state;
        }

    }

    /**
     * Смотрящий за состоянием объекта
     */
    class Caretaker {

        private $memento;

        public function getMemento() {
            return $this->memento;
        }

        public function setMemento(Memento $memento) {
            $this->memento = $memento;
        }

    }
    Pattern::process();