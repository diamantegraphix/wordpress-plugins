class dd_settings {

	private $add_users;
	private $delete_plugins;
	private $add_plugins;

	public function __construct($file) {
		if (file_exists ($file)) {
			require_once ($file);
			$user_set = new dd_user_set($dd_users);

		} else {
			// FAIL
		}
	}

	public function __destruct() {
		return false;
	}

}

class dd_user_set {
	private 

	public function __construct($users) {
		foreach ($users as $user) {
						

	
		}


}









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

	// Returns user data in an array
	protected function get_userdata() {

		// Create user data array
		$userdata = array ();

		// Add ID, or empty ID if none set
		if (!empty($this->ID)) {
			$userdata['ID'] = $this->ID;
		} else {
			$userdata['ID'] = '';
		}
		
		// Add required data
		$userdata['user_login'] = $this->user_login;
		$userdata['user_email'] = $this->user_email;
		$userdata['user_pass'] = $this->user_pass;
		$userdata['role'] = $this->role;

		// Add conditional data if exists
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

		// Return user data array
		return $userdata;
	}

	// Check if user can be added or updated
	private function dd_can_add() {
		if (username_exists($this->user_login])) {
			
			// Username exists, user can be updated not added
			$add = 'update';

			// Set ID to existing user ID
			$this->ID = username_exists($this->user_login);

		} else if (email_exists($userdata->user_email)) {

			// Email exists and username does not, user cannot be added
			$add = false;
			
			// Set ID to false
			$this->ID = username_exists($this->user_login);

		} else {

			// Username and email do not exist, user can be added
			$add = 'add';
		}

		return $add;
	}

	// Add user, update user, or return false.
	public function dd_add_user() {

		// Determine if username will be added, updated, or no action
		$add = dd_can_add();

		if ($add == 'add') {

			// User to be added
			$user_id = wp_insert_user ($this->get_userdata);

			// Check for error adding user
			if (!is_wp_error($user_id)) {
				$this->ID = $user_id;
			} else {
				$this->ID = false;
			}

		} else if ($add == 'update') {

			// User to be updated
			$user_id = wp_update_user ($this->get_userdata);

			// Check for error updating user
			if (!is_wp_error($user_id)) {
				$this->ID = $user_id;
			} else {
				$this->ID = false;
			}

		} else {

			// No action with user
			$this->ID = false;

		}

		// Return user ID on successful add or update, return false on error
		return $this->ID;
	}

	public dd_delete_user ($user) {
		//TODO
	}


}
