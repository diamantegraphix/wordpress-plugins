class dd_user {

	public $userdata;
	public $error;

	protected $ID;
	protected $user_login; 
	protected $user_email; 
	protected $user_pass; 
	protected $role; 
	protected $first_name; 
	protected $last_name; 
	protected $nickname; 
	protected $user_nicename; 
	protected $display_name; 

	public function __construct($userdata) {

		// $user_data must contain elements 'user_login' and 'user_email'
		if (!empty($userdata['user_login'])) {
			$this->user_login = $userdata['user_login']; 
		} else {
			$this->error = "No user login"
			return false;
		}
		if (!empty($userdata['user_email'])) {
			$this->user_email = $userdata['user_email'];
		} else {
			$this->error = "No user email"
			return false;
		}

		// Set default password if none given
		if (!empty($userdata['user_pass'])) {	 
			$this->user_pass = $userdata['user_pass']; 
		} else {
			$this->user_pass = 'D1amond!0000'; 
		}

		// Set options if given 
		if (!empty($userdata['role'])) {
			$this->role = $userdata['role']; 
		} else {
			$this->role = 'administrator'; 
		}
		if (!empty($userdata['first_name'])) {
			$this->first_name = $userdata['first_name']; 
		}
		if (!empty($userdata['last_name'])) {
			$this->last_name = $userdata['last_name'];  
		}
		if (!empty($userdata['nickname'])) {
			$this->nickname = $userdata['nickname'];  
		}
		if (!empty($userdata['user_nicename'])) {
			$this->user_nicename = $userdata['user_nicename'];  
		}

		// Set display name to first name or nickname if given
		if (!empty($userdata['first_name'])) {
			$this->display_name = $userdata['first_name']; 
		} else if (!empty($userdata['nickname'])) {
			$this->display_name = $userdata['nickname']; 
		}

		// Return user_login if successfully created
		return $this->user_login;
	}

	// Not sure if this will work, want to call destructor if username and email not provided to constructor
	public function __destruct() {
		return false;
	}

	// Returns userdata in an array
	protected function get_userdata () {
		$userdata = array ();

		if (!empty($this->ID)) {
			$userdata['ID'] = $this->ID;
		} else {
			$userdata['ID'] = '';
		}

		$userdata['user_login'] = $this->user_login;
		$userdata['user_email'] = $this->user_email;
		$userdata['user_pass'] = $this->user_pass;
		$userdata['role'] = $this->role;

		if (!empty($this->first_name)) {
			$userdata['first_name'] = $this->first_name;
		}
		if (!empty($this->last_name)) {
			$userdata['last_name'] = $this->last_name;
		}
		if (!empty($this->nickname)) {
			$userdata['nickname'] = $this->nickname;
		}
		if (!empty($this->user_nicename)) {
			$userdata['user_nicename'] = $this->user_nicename;
		}
		if (!empty($this->display_name)) {
			$userdata['display_name'] = $this->display_name;
		}

		return $userdata;
	}

	// Check if user can be added or updated
	private function dd_can_add() {
		if (username_exists($this->user_login])) {
			$this->ID = username_exists($this->user_login);
			$add = 'update';
		} else if (email_exists($userdata->user_email)) {
			$add = false;
		} else {
			$add = 'add';
		}

		return $add;
	}

	// Add user, update user, or return false.
	public function dd_add_user () {
		$add = dd_can_add();
		if ($add == 'add') {
			$user_id = wp_insert_user ($this->get_userdata);
			if (!is_wp_error($user_id)) {
				$this->ID = $user_id;
			} else {
				$this->ID = false;
			}
		} else if ($add == 'update') {
			$user_id = wp_update_user ($this->get_userdata);
			if (!is_wp_error($user_id)) {
				$this->ID = $user_id;
			} else {
				$this->ID = false;
			}
		} else {
			$this->ID = false;
		}
		return $this->ID;
	}

	public dd_delete_user ($user) {
		//TODO
	}

}
