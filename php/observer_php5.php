<?php 
	/**
	 * Наблюдатель (англ. Observer) — поведенческий шаблон проектирования. 
	 * Также известен как «подчинённые» (Dependents), «издатель-подписчик» (Publisher-Subscriber). 
	 * Создает механизм у класса, который позволяет получать экземпляру объекта этого класса 
	 * оповещения от других объектов об изменении их состояния, тем самым наблюдая за ними.
	 * 
	 * Определяет зависимость типа «один ко многим» между объектами таким образом, 
	 * что при изменении состояния одного объекта все зависящие от него оповещаются об этом событии.
	 */

	/**
	 * Обменник информацией
	 */
	class UserObserver
	{
		static private $instance = NULL;

		private $_users = [];
		private $_exchange_data;
	
		private function __construct()
		{}
	
		private function __clone()
		{}
	
		static public function load()
		{
			if(self::$instance == NULL)
				self::$instance = new self();
			return self::$instance;
		}
	
		public function getUserData()
		{
			return $this->_exchange_data;
		}
	
		public function setUserData(array $args)
		{
			foreach ($args as $key => $value)
				$this->_exchange_data[$key] = $value;
			$this->updateUsers();
		}
	
		public function registerUser(User $user)
		{
			$this->_users[] = $user;
		}
		
		public function clearExchange()
		{
			$this->_exchange_data = [];	
		}

		function updateUsers()
		{
			foreach($this->_users as $user)
				$user->update($this);
			$this->clearExchange();
		}
	}
	
	interface Observer
	{
		function update(UserObserver $obj);
	}

	class User implements Observer
	{
		const STATUS_KEY		= 'status';
		const STATUS_ONLINE 	= 'online';
		const STATUS_OFFLINE 	= 'offline';
		const MESSAGE 	= 'message'; 		

		private $_name; 
		private $_data;
	
		public function __construct($name)
		{
			$this->_name = $name;
			UserObserver::load()->registerUser($this);
		}
	
		public function update(UserObserver $obj)
		{
			$data = $obj->getUserData();
			$this->setData($data);
			$this->onAddAlert($data);
		}

		public function setData(array $args)
		{
			foreach ($args as $key => $value) 
				$this->_data[$key] = $value;
		}

		public function onAddAlert(array $args)
		{
			foreach ($args as $key => $value) 
			{
				if($key == User::STATUS_KEY && $value == User::STATUS_ONLINE)
					print $this->_name . " is online!\n";
				if($key == User::STATUS_KEY && $value == User::STATUS_OFFLINE)
					print $this->_name . " is offline!\n";					
				if($key == User::MESSAGE)
					print $this->_name . " get message: " . $value . "\n";
			}
		}
	}
	
	$user1 = new User('Vasya');
	$user2 = new User('Petya');
	
	UserObserver::load()->setUserData([User::STATUS_KEY => User::STATUS_ONLINE, User::MESSAGE => 'Hellow!']);
	UserObserver::load()->setUserData([User::STATUS_KEY => User::STATUS_OFFLINE]);