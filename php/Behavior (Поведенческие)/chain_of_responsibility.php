<?php
/**
 * Цепочка обязанностей (Chain of responsibility) —
 * предназначен для организации в системе уровней ответственности.
 *
 * ********** Применение
 * Шаблон рекомендован для использования в условиях:
 * в разрабатываемой системе имеется группа объектов,
 * которые могут обрабатывать сообщения определенного типа;
 * все сообщения должны быть обработаны хотя бы одним объектом системы;
 * сообщения в системе обрабатываются по схеме «обработай сам либо перешли другому»,
 * то есть одни сообщения обрабатываются на том уровне,
 * где они получены,
 * а другие пересылаются объектам иного уровня.
 *
 *
 */

    class Pattern
    {
        static function process()
        {
            // строим цепочку обязанностей
            $logger     = new StdoutLogger(Logger::DEBUG);
            $logger1    = $logger->setNext(new EmailLogger(Logger::NOTICE));
            $logger2    = $logger1->setNext(new StderrLogger(Logger::ERR));

            // Handled by StdoutLogger
            $logger->message("Entering function y.", Logger::DEBUG);

            // Handled by StdoutLogger and EmailLogger
            $logger->message("Step1 completed.", Logger::NOTICE);

            // Handled by all three loggers
            $logger->message("An error has occurred.", Logger::ERR);
        }
    }
    abstract class Logger {

        const ERR = 3;
        const NOTICE = 5;
        const DEBUG = 7;

        protected $mask;
        // Следующий элемент в цепочке обязанностей
        protected $next;

        public function __construct($mask) {
            $this->mask = $mask;
        }

        public function setNext(Logger $log) {
            $this->next = $log;
            return $log;
        }

        public function message($msg, $priority) {
            if ($priority <= $this->mask) {
                $this->writeMessage($msg);
            }

            if ($this->next != null) {
                $this->next->message($msg, $priority);
            }
        }

        protected abstract function writeMessage($msg);
    }

    class StdoutLogger extends Logger {

        protected function writeMessage($msg) {
            echo sprintf("Writing to stdout: %s\n", $msg);
        }

    }

    class EmailLogger extends Logger {

        protected function writeMessage($msg) {
            echo sprintf("Sending email: %s\n", $msg);
        }

    }

    class StderrLogger extends Logger {

        protected function writeMessage($msg) {
            echo sprintf("Sending ERROR: %s\n", $msg);
        }

    }
    Pattern::process();