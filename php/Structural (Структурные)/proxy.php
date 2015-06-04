<?php
/**
 * Заместитель (Proxy)
 * предоставляет объект, который контролирует доступ к другому объекту,
 * перехватывая все вызовы (выполняет функцию контейнера).
 *
 * ************ Проблема
 * Необходимо управлять доступом к объекту так,
 * чтобы не создавать громоздкие объекты «по требованию».
 *
 * ************ Решение
 * Создать суррогат громоздкого объекта.
 * «Заместитель» хранит ссылку,
 * которая позволяет заместителю обратиться к реальному субъекту
 * (объект класса «Заместитель» может обращаться к объекту класса «Субъект»,
 * если интерфейсы «Реального Субъекта» и «Субъекта» одинаковы).
 * Поскольку интерфейс «Реального Субъекта» идентичен интерфейсу «Субъекта»,
 * так, что «Заместителя» можно подставить вместо «Реального Субъекта»,
 * контролирует доступ к «Реальному Субъекту»,
 * может отвечать за создание или удаление «Реального Субъекта».
 * «Субъект» определяет общий для «Реального Субъекта» и «Заместителя» интерфейс,
 * так, что «Заместитель» может быть использован везде,
 * где ожидается «Реальный Субъект».
 * При необходимости запросы могут быть переадресованы «Заместителем» «Реальному Субъекту».
 *
 * *********** Виды
 *
 * ===Протоколирующий прокси:
 * сохраняет в лог все вызовы «Субъекта» с их параметрами.
 *
 * ===Удалённый заместитель (remote proxies):
 * обеспечивает связь с «Субъектом»,
 * который находится в другом адресном пространстве или на удалённой машине.
 * Также может отвечать за кодирование запроса и его аргументов
 * и отправку закодированного запроса реальному «Субъекту»,
 *
 * ===Виртуальный заместитель (virtual proxies):
 * обеспечивает создание реального «Субъекта» только тогда,
 * когда он действительно понадобится.
 * Также может кэшировать часть информации о реальном «Субъекте»,
 * чтобы отложить его создание,
 *
 * ===Копировать-при-записи:
 * обеспечивает копирование «субъекта»
 * при выполнении клиентом определённых действий (частный случай «виртуального прокси»).
 *
 * ===Защищающий заместитель (protection proxies):
 * может проверять, имеет ли вызывающий объект необходимые для выполнения запроса права.
 *
 * ===Кэширующий прокси:
 * обеспечивает временное хранение результатов расчёта
 * до отдачи их множественным клиентам,
 * которые могут разделить эти результаты.
 *
 * ===Экранирующий прокси:
 * защищает «Субъект» от опасных клиентов (или наоборот).
 *
 * ===Синхронизирующий прокси:
 * производит синхронизированный контроль доступа к «Субъекту» в асинхронной многопоточной среде.
 *
 * ===«Умная» ссылка (smart reference proxy):
 * производит дополнительные действия,
 * когда на «Субъект» создается ссылка, например,
 * рассчитывает количество активных ссылок на «Субъект».
 *
 * ********* Преимущества
 *  ===удалённый заместитель;
 *  ===виртуальный заместитель может выполнять оптимизацию;
 *  ===защищающий заместитель;
 *  ===«умная» ссылка;
 *
 *  ******** Недостатки
 *  ===резкое увеличение времени отклика.
 *
 * ********* Сфера применения
 * Шаблон Proxy может применяться в случаях работы с сетевым соединением,
 * с огромным объектом в памяти (или на диске) или с любым другим ресурсом,
 * который сложно или тяжело копировать.
 * Хорошо известный пример применения — объект, подсчитывающий число ссылок.
 *
 * ********* Прокси и близкие к нему шаблоны
 * Адаптер обеспечивает отличающийся интерфейс к объекту.
 * Прокси обеспечивает тот же самый интерфейс.
 * Декоратор обеспечивает расширенный интерфейс.
 */



/**
 * Subject - субъект
 * определяет общий для Math и "Proxy" интерфейс, так что класс
 * "Proxy" можно использовать везде, где ожидается
 */

class Pattern
{
    static function process()
    {
        $proxy = new MathProxy;

        // Do the math
        print("4 + 2 = "    .   $proxy->Add(4, 2) . "\n");
        print("4 - 2 = "    .   $proxy->Sub(4, 2) . "\n");
        print("4 * 2 = "    .   $proxy->Mul(4, 2) . "\n");
        print("4 / 2 = "    .   $proxy->Div(4, 2) . "\n");
    }
}

interface IMath
{
    function Add($x, $y);
    function Sub($x, $y);
    function Mul($x, $y);
    function Div($x, $y);
}



/**
 * RealSubject - реальный объект
 * определяет реальный объект, представленный заместителем
 */

class Math implements IMath
{
    public function __construct()
    {
        print ("Create object Math. Wait...\n");
        sleep(2);
    }

    public function  Add($x, $y){return $x + $y;}
    public function  Sub($x, $y){return $x - $y;}
    public function  Mul($x, $y){return $x * $y;}
    public function  Div($x, $y){return $x / $y;}
}


/**
 * Proxy - заместитель
 * хранит ссылку, которая позволяет заместителю обратиться к реальному
 * субъекту. Объект класса "MathProxy" может обращаться к объекту класса
 * "Math", если интерфейсы классов "Math" и "IMath" одинаковы;
 * предоставляет интерфейс, идентичный интерфейсу "IMath", так что заместитель
 * всегда может быть предоставлен вместо реального субъекта;
 */
class MathProxy implements IMath
{
    protected $math;

    public function __construct()
    {
        $this->math = null;
    }
    /// Быстрая операция - не требует реального субъекта
    public function Add($x, $y)
    {
        return $x + $y;
    }

    public function  Sub($x, $y)
    {
        return $x - $y;
    }


    /// Медленная операция - требует создания реального субъекта
    public function Mul($x, $y)
    {
        if ($this->math == null)
            $this->math = new Math();
        return $this->math->Mul($x, $y);
    }

    public function Div($x, $y)
    {
        if ($this->math == null)
            $this->math = new Math();
        return $this->math->Div($x, $y);
    }
}
Pattern::process();

