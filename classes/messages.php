<?php

namespace Messages;

/**
 * The purpose of this class is to save messages to the session to display
 * them later, for example after a page redirection.
 */
class Messages {
	/**
	 * @var Messages default instance
	 */
	protected static $_instance;

	/**
	 * @var array contains references to all instantiations of Messages
	 */
	protected static $_instances = array();

	/**
	 * Load the configuration
	 */
	public static function _init() {
		\Config::load('messages', true);
	}

	/**
	 * Create a new instance of Messages. If a name is given, you will be able
	 * to retrieve the created instance by name later.
	 * If an instance with this name already exists, a notice is raised and this
	 * instance is returned.
	 * @param string $name Name of the instance
	 * @return Messages Newly created instance
	 */
	public static function forge($name = 'default') {
		if(isset(static::$_instances[$name])) {
			\Error::notice('Fieldset with this name exists already, cannot be overwritten.');
			return static::$_instances[$name];
		}
		static::$_instances[$name] = new static($name);

		if ($name == 'default')
			static::$_instance = static::$_instances[$name];

		return static::$_instances[$name];
	}

	/**
	 * Retrieve a particular instance by name or the default one if none is given.
	 * If no instance exists for the name, a new one is created.
	 * @param string $name Name of the instance
	 * @return Messages Existing instance or new one if it didn't exists.
	 */
	public static function instance($name = 'default') {
		if(isset(static::$_instances[$name]))
			return static::$_instances[$name];
		else
			return static::forge($name);
	}

	/**
	 * Base name of the session object
	 * @var string
	 */
	private $session_name;
	/**
	 * List of all possible groups
	 * @var array
	 */
	private $groups;

	/**
	 * Initialize some configuration like the base session name or the list of
	 * permitted groups.
	 * @param string $name Name of this particular instance
	 */
	private function __construct($name) {
		$this->session_name = \Config::get('messages.session').'.'.$name.'.';
		$this->groups = \Config::get('messages.groups');
	}

	/**
	 * Add a new message to the session.
	 * The current object is returned to allow method chaining.
	 * @param string $group Group of the message
	 * @param string $message The message
	 * @return Messages the instance
	 */
	public function message($group, $message) {
		$name = $this->session_name.$group;
		$messages = \Session::get($name, array());
		$messages[] = $message;
		\Session::set($name, $messages);
		return $this;
	}

	/**
	 * Clear a particular group from the session or all the groups if null or no
	 * argument is given.
	 * The current object is returned to allow method chaining.
	 * @param string $group The group to clear, or all if null
	 * @return Messages the instance
	 */
	public function clear($group = null) {
		if(is_null($group))
			foreach($this->groups as $g)
				$this->clear($g);
		else \Session::delete($this->session_name.$group);
		return $this;
	}

	/**
	 * Return the messages for a particular group or all the messages for all
	 * group if null or no argument is given.
	 * The messages are cleared from the session by default, if you want to keep
	 * them you must pass false as second argument.
	 * @param string $group The group to retrieve
	 * @param bool $clear Do we clear the messages from the session ?
	 * @return array string for each messages or array of string for each group
	 */
	public function get($group = null, $clear = true) {
		if(is_null($group)) {
			$messages = array();
			foreach($this->groups as $g)
				$messages[$g] = $this->get($g);
		} else {
			$name = $this->session_name.$group;
			$messages = \Session::get($name, array());
		}
		if($clear) $this->clear($group);
		return $messages;
	}

	/**
	 * Return the messages for a particular group or all the messages for all
	 * group if null or no argument is given.
	 * The format used to convert the messages to string can be changed in the
	 * config file.
	 * @param string $group The group to retrieve
	 * @param bool $clear Do we clear the messages from the session ?
	 * @return string The messages formatted according to the configuration
	 */
	public function show($group = null, $clear = true) {
		$data = '';
		if(is_null($group)) {
			foreach($this->groups as $g)
				$data .= $this->show($g, $clear);
			$data = str_replace('{groups}', $data, \Config::get('messages.all_groups'));
		} else {
			$format = \Config::get('messages.message');
			foreach($this->get($group, $clear) as $message)
				$data .= str_replace('{message}', $message, $format);
			if(! empty($data) || \Config::get('messages.show_empty'))
				$data = str_replace(array('{group}', '{messages}'), array($group, $data), \Config::get('messages.group'));
		}
		return $data;
	}
}