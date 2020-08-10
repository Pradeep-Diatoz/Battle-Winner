<?php

	require_once("Rest.inc.php");
	require_once("db.php");
	require_once("functions.php");

	class API extends REST {

		private $functions = NULL;
		private $db = NULL;

		public function __construct() {
			$this->db = new DB();
			$this->functions = new functions($this->db);
		}

		public function check_connection() {
			$this->functions->checkConnection();
		}

		/*
		 * ALL API Related android client -------------------------------------------------------------------------
		*/
		
        private function user_register() {
	        $this->functions->userRegister();
	    }

	    private function user_login() {
	        $this->functions->userLogin();
	    }

	    private function get_user_profile() {
	        $this->functions->getUserProfile();
	    }

	    private function update_user_profile() {
	        $this->functions->updateUserProfile();
	    }
	    
	    private function update_user_photo() {
	        $this->functions->updateUserPhoto();
	    }
	    
	    private function reset_password() {
	        $this->functions->resetPassword();
	    }

	    
	    
	    private function get_match_play() {
			$this->functions->getMatchPlay();
		}
		
		private function get_match_live() {
			$this->functions->getMatchLive();
		}
		
		private function get_match_result() {
			$this->functions->getMatchResult();
		}
		
		
		
		private function get_match_participants() {
			$this->functions->getMatchParticipants();
		}
		
	    private function get_match_winner() {
			$this->functions->getMatchWinner();
		}
		
		private function get_match_full_result() {
			$this->functions->getMatchFullResult();
		}
		
		
		
		private function get_my_summary() {
			$this->functions->getMySummary();
		}
		
		private function get_my_statistics() {
			$this->functions->getMyStatistics();
		}
		
		private function get_my_transactions() {
			$this->functions->getMyTransactions();
		}
		
		
	    
	    private function get_top_players() {
			$this->functions->getTopPlayers();
		}
		
		private function get_my_referrals_summary() {
			$this->functions->getMyReferralsSummary();
		}
		
		private function get_my_referralsList() {
			$this->functions->getMyReferralsList();
		}
		
		private function get_top_leaders() {
			$this->functions->getTopLeaders();
		}
		
		private function get_my_rewards_summary() {
			$this->functions->getMyRewardsSummary();
		}
		
		private function get_my_rewardsList() {
			$this->functions->getMyRewardsList();
		}
		
		private function get_top_rewards() {
			$this->functions->getTopRewards();
		}
		
		
		
		private function join_match() {
			$this->functions->joinMatch();
		}
		
		
		
		private function get_add_coins() {
			$this->functions->getAddCoins();
		}
		
		private function get_redeem_coins() {
			$this->functions->getRedeemCoins();
		}
		
		
		
		private function add_transaction() {
			$this->functions->addTransaction();
		}
		
		private function add_reward() {
			$this->functions->addReward();
		}
		
		
	    
	    private function get_rewards() {
			$this->functions->getRewards();
		}
	    
	    
	    private function update_app() {
	        $this->functions->updateApp();
	    }

        private function get_notification() {
	        $this->functions->getNotification();
	    }
	    
	    private function get_about_us() {
	        $this->functions->getAboutUs();
	    }
	    
	    private function get_contact_us() {
	        $this->functions->getContactUs();
	    }
	    
	    private function get_privacy_policy() {
	        $this->functions->getPrivacyPolicy();
	    }
	    
	     private function get_terms_conditions() {
	        $this->functions->getTermsConditions();
	    }

		/*
		 * End of API Transactions ----------------------------------------------------------------------------------
		*/

		public function processApi() {
			if(isset($_REQUEST['x']) && $_REQUEST['x']!=""){
				$func = strtolower(trim(str_replace("/","", $_REQUEST['x'])));
				if((int)method_exists($this,$func) > 0) {
					$this->$func();
				} else {
					echo 'processApi - method not exist';
					exit;
				}
			} else {
				echo 'processApi - method not exist';
				exit;
			}
		}

	}

	// Initiiate Library
	$api = new API;
	$api->processApi();

?>