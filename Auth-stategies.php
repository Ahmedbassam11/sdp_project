    <?php
ob_start();
     require_once "Manager.php";
    // require_once "beneficiary.php";
     require_once "Volunteer.php";
     require_once "beneficiary.php";
     require_once"doner.php";
     ob_clean();
    interface SignupProvider {
        public function signup(array $userData, array $roleData): bool;
    }

    class SignupAsManager implements SignupProvider
    {
        public function signup(array $userData, array $roleData): bool
        {
            try {
                // Create and insert a new User
                $user = new User($userData, true);
                // Retrieve and associate UserID
                $roleData['UserOb'] = $user;// Associate User object with Manager
                echo $roleData['UserOb'];
                $manager = new Manager($roleData);
                $manager->add();

                return true;
            } catch (Exception $e) {
                echo "Error during manager signup: " . $e->getMessage();
                return false;
            }
        }
    }

    class SignupAsBeneficary implements SignupProvider
    {
        public function signup(array $userData, array $roleData): bool
        {
            try {
                // Step 1: Create and insert a new User
                $user = new User($userData, true);
                // Retrieve and associate UserID
                $roleData['Userob']= $user; // Associate User object with Beneficiary
                $beneficiary = new Beneficiary($roleData);
                $beneficiary->add();

                return true;
            } catch (Exception $e) {
                echo "Error during beneficiary signup: " . $e->getMessage();
                return false;
            }
        }   
    }
    class SignupAsVolunteer implements SignupProvider
    {
        public function signup(array $userData, array $roleData): bool
        {
            try {
                // Step 1: Create and insert a new User
                $user = new User($userData, true);
                // Step 2: Create a Volunteer and associate the User
                $roleData['UserOb'] = $user; // Associate User object with Volunteer
                $volunteer = new Volunteer($roleData);
                $volunteer->addvolunteer();
                

                return true;
            } catch (Exception $e) {
                echo "Error during volunteer signup: " . $e->getMessage();
                return false;
            }
        }

    }
    class SignupAsDoner implements SignupProvider
    {
        public function signup(array $userData, array $roleData): bool
        {
            try {
                // Create and insert a new User
                $user = new User($userData, true);
                // Retrieve and associate UserID
                $roleData['UserOb'] = $user;// Associate User object with Manager
                
                $manager = new doner($roleData);
                $manager->add();

                return true;
            } catch (Exception $e) {
                echo "Error during manager signup: " . $e->getMessage();
                return false;
            }
        }
    }
        

    class ContextSignup
    {
        private SignupProvider $strategy;
        public function __construct(SignupProvider $strategy )
        {
            $this->strategy = $strategy;
        }

        public function signup(array $userData, array $roleData): bool
        {
            return $this->strategy->signup($userData, $roleData);    }
    }
    ?>

