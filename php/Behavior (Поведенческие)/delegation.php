<?php
	/**
	 * Делегирование (англ. Delegation) — шаблон проектирования, 
	 * в котором объект внешне выражает некоторое поведение, 
	 * но в реальности передаёт ответственность за выполнение этого поведения связанному объекту. 
	 * Шаблон делегирования является фундаментальной абстракцией, 
	 * на основе которой реализованы другие шаблоны - композиция (также называемая агрегацией), 
	 * примеси (mixins) и аспекты (aspects).
	 */ 
	
	/**
	 *  класс для хранения данных о сотруднике
	 */
	class Employee {
	
		private $_name;
		private $_departament;
	
		public function __construct($name, $departament) {
			$this->_name = $name;
			$this->_departament = $departament;
		}
	
		public function getName() {
			return $this->_name;
		}
	
		public function getDepartament() {
			return $this->_departament;
		}
		
		public function setName($name) {
			$this->_name = $name;
		}
		
		public function setDepartament($departament) {
			$this->_departament;
		}
	}

	/**
	 *	класс для хранения списка объектов
	 */
	class ObjectList {
	
		private $_objList;
	
		public function __construct() {
			$this->free();
		}
		/**
		 *чтобы не скучать!
		 */
		public function free() {
			$this->_objList = [];
		}
	
		public function count() {
			return count($this->_objList);
		}
	
		public function add($obj) {
			array_push($this->_objList, $obj);
		}
	
		public function remove($obj) {
			$k = array_search( $obj, $this->_objList, true );
			if ( $k !== false ) {
				unset( $this->_objList[$k] );
			}
		}
	
		public function get($index) {
			return $this->_objList[$index];
		}
	
		public function set($index, $obj) {
			$this->_objList[$index] = $obj;
		}
	}

	/**
	 * класс для хранения сотрудников
	 */
	class EmployeeList {
	
		/**
		 * объект класса "список объектов"
		 */
		private $_employeersList;
	
		public function __construct(){
			// создаём объект методы которого будем делегировать
			$this->_employeersList = new ObjectList;
		}
	
		public function getEmployer($index) {
			return $this->_employeersList->get($index);
		}
	
		public function setEmployer($index, Employee $objEmployer) {
			$this->_employeersList->set($index, $objEmployer);
		}
	
		public function __destruct() {
			$this->_employeersList->free();
		}
	
		public function add(Employee $objEmployer) {
			$this->_employeersList->add($objEmployer);
		}
	
		public function remove(Employee $objEmployer) {
			$this->_employeersList->remove($objEmployer);
		}
	
	
		/**
		 * Последовательный поиск сотрудника по имени
		 * через аргумент $offset можно задавать позицию с которой вести поиск.
		 * если сотрудник не найден вернёт значение меньше ноля (-1)
		 * 
		 * @param unknown $name
		 * @param number $offset
		 * @return number
		 */
		public function getIndexByName($name, $offset=0) {
			$result = -1; // предполагаем, что его нету в списке
			$cnt = $this->_employeersList->count();
			for ($i = $offset; $i < $cnt; $i++) {
				if ( !strcmp( $name, $this->_employeersList->get($i)->getName() ) ) {
					$result = $i;
					break;
				}
			}
			return $result;
		}
	}

	$obj1 = new Employee("Танасийчук Степан", "web студия");
	$obj2 = new Employee("Кусый Назар", "web студия");
	$obj3 = new Employee("Сорока Орест", "web студия");
	
	$objList = new EmployeeList();
	$objList->add($obj1);
	$objList->add($obj2);
	$objList->add($obj3);
	

	print_r($objList);
	echo "-----------\r\n";
	
	$index = $objList->getIndexByName("Кусый Назар");
	$emploee = $objList->getEmployer($index);
	$emploee->setName('Безкусый Назар');
	print_r($emploee);
	echo "-----------\r\n";
	
	$objList->setEmployer($index, $emploee);
	print_r($objList);