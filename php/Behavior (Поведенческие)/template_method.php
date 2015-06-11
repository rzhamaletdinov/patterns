<?php
/**
 * Шаблонный метод (Template method) —
 * определяет основу алгоритма и
 * позволяет наследникам переопределять
 * некоторые шаги алгоритма,
 * не изменяя его структуру в целом.
 *
 * *************Применимость
 * Однократное использование инвариантной части алгоритма,
 * с оставлением изменяющейся части на усмотрение наследникам.
 * Локализация и вычленение общего
 * для нескольких классов кода
 * для избегания дублирования.
 * Разрешение расширения кода наследниками
 * только в определенных местах.
 *
 * *************Участники
 * Abstract class -
 * определяет абстрактные операции,
 * замещаемые в наследниках для реализации шагов алгоритма;
 * реализует шаблонный метод,
 * определяющий скелет алгоритма.
 * Шаблонный метод вызывает замещаемые и другие,
 * определенные в Abstract class, операции.
 *
 * Concrete class -
 * реализует замещаемые операции необходимым для данной реализации способом.
 * Concrete class предполагает,
 * что инвариантные шаги алгоритма будут выполнены в AbstractClass.
 *
 */

class Pattern
{
    public static function process()
    {
        writeln('BEGIN TESTING TEMPLATE PATTERN');
        writeln();
        $book = new Book('PHP for Cats','Larry Truett');
        $exclaimTemplate    = new TemplateExclaim();
        $starsTemplate      = new TemplateStars();
        writeln('test 1 - show exclaim template');
        writeln($exclaimTemplate->showBookTitleInfo($book));
        writeln();
        writeln('test 2 - show stars template');
        writeln($starsTemplate->showBookTitleInfo($book));
        writeln();
        writeln('END TESTING TEMPLATE PATTERN');
    }
}

abstract class TemplateAbstract {

    public final function showBookTitleInfo($book_in)
    {
        $title = $book_in->getTitle();
        $author = $book_in->getAuthor();
        $processedTitle = $this->processTitle($title);
        $processedAuthor = $this->processAuthor($author);
        if (NULL == $processedAuthor)
            $processed_info = $processedTitle;
        else
            $processed_info = $processedTitle.' by '.$processedAuthor;
        return $processed_info;
    }

    abstract function processTitle($title);
    function processAuthor($author) {return NULL;}
}

class TemplateExclaim extends TemplateAbstract {
    function processTitle($title) {
        return Str_replace(' ','!!!',$title);
    }
    function processAuthor($author) {
        return Str_replace(' ','!!!',$author);
    }
}

class TemplateStars extends TemplateAbstract {
    function processTitle($title) {
        return Str_replace(' ','*',$title);
    }
}

class Book {
    private $author;
    private $title;
    function __construct($title_in, $author_in)
    {
        $this->author = $author_in;
        $this->title  = $title_in;
    }
    function getAuthor() {return $this->author;}
    function getTitle() {return $this->title;}
    function getAuthorAndTitle()
    {
        return $this->getTitle() . ' by ' . $this->getAuthor();
    }
}

function writeln($line_in = '') {
    echo $line_in."\n";
}

Pattern::process();