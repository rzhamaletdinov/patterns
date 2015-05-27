<?php

/**
 * В PHP осуществляется встроенная поддержка этого шаблона через входящее в поставку
 * расширение SPL (Standard PHP Library):
 * User - SplObserver - интерфейс для Observer (наблюдателя),
 * Chat - SplSubject - интерфейс Observable (наблюдаемого),
 * SplObjectStorage - вспомогательный класс (обеспечивает улучшенное сохранение и удаление
 * объектов, в частности, реализованы методы attach() и detach()).
 */


class Pattern
{
    static function process()
    {
        $chat       = Chat::get();
        $vasya      = new User('Вася');
        $lena       = new User('Лена');
        $ruslan     = new User('Руслан');
        $olga       = new User('Оля');

        //add users
        $chat->attach($vasya);
        $chat->attach($lena);
        $chat->attach($ruslan);
        $chat->attach($olga);

        //remove user
        $chat->detach($olga);

        $vasya->say('Всем привет!');
        $lena->say('Пока всем!');

        $chat->detach($lena);

        $ruslan->say('Пока!');

        $chat->detach($ruslan);
        $chat->detach($vasya);
    }
}
/**
 * Subject,that who makes messages
 */
class Chat implements SplSubject
{
    private $_observers = [];
    private $_message;
    private $_author;

    static private $_instance = NULL;

    private function __construct()
    {
    }

    static public function get()
    {
        if(self::$_instance == NULL)
            self::$_instance = new self();
        return self::$_instance;
    }


    //add user
    public function attach(SplObserver $observer)
    {
        $observer->online();
        $this->_observers[] = $observer;
    }

    //remove user
    public function detach(SplObserver $observer)
    {
        $observer->offline();
        $key = array_search($observer,$this->_observers, true);
        if($key)
            unset($this->_observers[$key]);
    }

    //set msg
    public function setMessage($msg, $author)
    {
        $this->_message = $msg;
        $this->_author  = $author;
        $this->notify();
    }

    public function getMessage()
    {
        return $this->_message;
    }

    public function getAuthor()
    {
        return $this->_author;
    }

    //notify observers(or some of them)
    public function notify()
    {
        foreach ($this->_observers as $value)
        {
            if($value->getName() == $this->_author)
                continue;
            $value->update($this);
        }
    }
}

/**
 * Observer,that who recieves messages
 */
class User implements SplObserver
{
    const ONLINE    = 'online';
    const OFFLINE   = 'offline';

    private $_name;
    private $_status;

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function update(SplSubject $subject)
    {
        print $this->_name.' is reading: '.$subject->getMessage()."(from " . $subject->getAuthor(). ")\n";
    }

    public function online()
    {
        print $this->_name . " is online!\n";
        $this->_status = self::ONLINE;
    }

    public function offline()
    {
        print $this->_name . " is offline!\n";
        $this->_status = self::OFFLINE;
    }

    public function say($msg)
    {
        if($this->_status == self::ONLINE)
        {
            print $this->_name . " say: " . $msg . "\n";
            Chat::get()->setMessage($msg, $this->_name);
        }
    }
}
Pattern::process();