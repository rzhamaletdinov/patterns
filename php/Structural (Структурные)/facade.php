<?php
/**
 * Шаблон фасад (Facade) — позволяет скрыть сложность системы
 * путем сведения всех возможных внешних вызовов к одному объекту,
 * делегирующему их соответствующим объектам системы.
 *
 * **************Проблема
 * Как обеспечить унифицированный интерфейс
 * с набором разрозненных реализаций или интерфейсов,
 * например, с подсистемой, если нежелательно высокое связывание
 * с этой подсистемой или реализация подсистемы может измениться?
 *
 * **************Решение
 * Определить одну точку взаимодействия с подсистемой — фасадный объект,
 * обеспечивающий общий интерфейс с подсистемой,
 * и возложить на него обязанность по взаимодействию с её компонентами.
 * Фасад — это внешний объект,
 * обеспечивающий единственную точку входа для служб подсистемы.
 * Реализация других компонентов подсистемы закрыта и не видна внешним компонентам.
 * Фасадный объект обеспечивает реализацию
 * GRASP паттерна Устойчивый к изменениям (Protected Variations)
 * с точки зрения защиты от изменений в реализации подсистемы.
 *
 * *************Особенности применения
 * Шаблон применяется для установки некоторого рода политики
 * по отношению к другой группе объектов.
 * Если политика должна быть яркой и заметной,
 * следует воспользоваться услугами шаблона Фасад.
 * Если же необходимо обеспечить скрытность и аккуратность (прозрачность),
 * более подходящим выбором является шаблон Заместитель (Proxy).
 */

class Pattern
{
    static function process()
    {
        /* Клиентская часть */
        $facade = new Computer();
        $facade->startComputer();
    }
}

/* Сложные части системы */
class CPU
{
    public function freeze()            { print "CPU: freeze\n"; }
    public function jump( $position )   { print "CPU: jump to " . $position . "\n"; }
    public function execute()           { print "CPU: ready!\n"; }

}

class Memory
{
    public function load( $position, $data ) {
        print "RAM: loading " . $position . "\n";
        if($data)
            print "RAM: ready!\n";
    }
}

class HardDrive
{
    public function read( $lba, $size ) {
        print "HDD: loading from boot: " . $lba . " (size: " . $size . ")\n";
        return true;
    }
}

/* Фасад */
class Computer
{
    const BOOT_ADDRESS  = '0x00000009';
    const BOOT_SECTOR   = 'mbr1';
    const SECTOR_SIZE   = '4096byte';

    protected $cpu;
    protected $memory;
    protected $hardDrive;

    public function __construct()
    {
        $this->cpu = new CPU();
        $this->memory = new Memory();
        $this->hardDrive = new HardDrive();
    }

    public function ready()
    {
        print "\nWelcome!\nPress any key to continue...\n";
    }


    public function startComputer()
    {
        $this->cpu->freeze();
        $this->memory->load( self::BOOT_ADDRESS, $this->hardDrive->read( self::BOOT_SECTOR, self::SECTOR_SIZE) );
        $this->cpu->jump( self::BOOT_ADDRESS );
        $this->cpu->execute();
        $this->ready();
    }
}
Pattern::process();