<?php
/**
 * Посредник (Mediator) —
 * обеспечивает взаимодействие множества объектов,
 * формируя при этом слабую связанность
 * и избавляя объекты от необходимости явно ссылаться друг на друга.
 *
 * ***********Проблема
 * Обеспечить взаимодействие множества объектов,
 * сформировав при этом слабую связанность
 * и избавив объекты от необходимости явно ссылаться друг на друга.
 *
 * ***********Решение
 * Создать объект,
 * инкапсулирующий способ взаимодействия множества объектов.
 *
 * ***********Преимущества
 * Устраняется связанность между "Коллегами", централизуется управление.
 *
 * ***********Описание
 * "Посредник" определяет интерфейс для обмена информацией с объектами "Коллеги",
 * "Конкретный посредник" координирует действия объектов "Коллеги".
 * Каждый класс "Коллеги" знает о своем объекте "Посредник",
 * все "Коллеги" обмениваются информацией только с посредником,
 * при его отсутствии им пришлось бы обмениваться информацией напрямую.
 * "Коллеги" посылают запросы посреднику и получают запросы от него.
 * "Посредник" реализует кооперативное поведение,
 * пересылая каждый запрос одному или нескольким "Коллегам".
 *
 * ***********Примечание
 * Mediator - это некая сущность,
 * которая знает всё о других.
 * Другие знают о медиаторе, но не знают ничего друг от друге.
 * Медиатор управляет другими, за счет получения от других некоторых событий.
 * Объект, отправивший событие ничего не знает о том,
 * что произошло.
 * Так достигается слабая связанность между компонентами.
 *
 * Observable просто имеет список подписчиков,
 * когда происходит какое-то сообытие,
 * он может сделать broadcast, все обсерверы получат уведомление.
 * Обсервер же просто рассылает сообщение всем своим подписчикам
 * Все происходит через один интерефейс.
 *
 */


class Pattern
{
    static function process()
    {
        $mediator = new ConcreteMediator();

        $collegue1 = new ConcreteColleague1($mediator);
        $collegue2 = new ConcreteColleague2($mediator);

        $mediator->setColleague1($collegue1);
        $mediator->setColleague2($collegue2);

        $collegue1->send('How are you ?');
        $collegue2->send('Fine, thanks!');
    }
}

//абстрактный класс посредник
abstract class Mediator {
    /**
     * Отправка сообщения {@code message} указанному получателю {@code colleague}
     * @param string message отправляемое сообщение
     * @param Colleague colleague отправитель сообщения
     */
    public abstract function send($message, Colleague $colleague);
}

//абстрактный класс коллега
abstract class Colleague {
    protected $mediator;

    public function __construct(Mediator $mediator) {
        $this->mediator = $mediator;
    }

    /**
     * Отправка сообщения посредством посредника
     * @param string message сообщение
     */
    public function send($message){
        $this->mediator->send($message, $this);
    }

    /**
     * Обработка полученного сообщения реализуется каждым конкретным
     * наследником
     * @param string message получаемое сообщение
     */
    public abstract function notify($message);
}

class ConcreteMediator extends Mediator {
    private $colleague1;
    private $colleague2;

    public function setColleague1(ConcreteColleague1 $colleague){
        $this->colleague1 = $colleague;
    }

    public function setColleague2(ConcreteColleague2 $colleague){
        $this->colleague2 = $colleague;
    }

    public function send($message, Colleague $colleague) {
        if ($colleague == $this->colleague1) {
            $this->colleague2->notify($message);
        } else {
            $this->colleague1->notify($message);
        }
    }
}

//коллега 1
class ConcreteColleague1 extends Colleague {
    public function notify($message) {
        echo sprintf("Collegue1 gets message: %s\n", $message);
    }
}

//коллега 2
class ConcreteColleague2 extends Colleague {
    public function notify($message) {
        echo sprintf("Collegue2 gets message: %s\n", $message);
    }
}
Pattern::process();