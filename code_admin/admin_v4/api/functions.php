<?php

    require_once("Rest.inc.php");
    require_once("db.php");

    class functions extends REST {

        private $mysqli = NULL;
        private $db = NULL;
        
        public function __construct($db) {
            parent::__construct();
            $this->db = $db;
            $this->mysqli = $db->mysqli;
        }

    	public function checkConnection() {
    		if (mysqli_ping($this->mysqli)) {
                $respon = array(
                    'status' => 'ok', 'database' => 'connected'
                );
                $this->response($this->json($respon), 200);
    		} else {
                $respon = array(
                    'status' => 'failed', 'database' => 'not connected'
                );
                $this->response($this->json($respon), 404);
    		}
    	}
    	
    	
        public function userRegister() {
    		include "../include/config.php";
    		include "../public/register.php";
            
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$sql = "SELECT * FROM user_details WHERE device_id = '".$_GET['device_id']."' AND is_block = '1'"; 
                		$res = mysqli_query($connect, $sql);
                		
            			if(mysqli_num_rows($res) > 0) {
            				$set['result'][]=array('msg' => "You are not eligible to create new account!", 'success'=>'0');
            				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            				die();
            			} 
                		else {
                    		if(isset($_GET['referer'])) {
                    
                    			$qry = "SELECT * FROM user_details WHERE username = '".$_GET['username']."' OR email = '".$_GET['email']."' OR mobile = '".$_GET['mobile']."'"; 
                    			$sel = mysqli_query($connect, $qry);
                    		
                    			if(mysqli_num_rows($sel) > 0) {
                    				$set['result'][]=array('msg' => "This username, email or mobile already used!", 'success'=>'0');
                    				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    				die();
                    			} 
                    			else {
                    		    	$qry1 = "SELECT id FROM user_details WHERE refer = '".$_GET['referer']."' AND status='1'"; 
                    		        $sel1 = mysqli_query($connect, $qry1);
                    		        
                    		        if(mysqli_num_rows($sel1) > 0) {
                    		            $today = date("Y-m-d"); 
                    		            
                                		$qry_refered="SELECT refered FROM user_details WHERE refer='".$_GET['referer']."' AND status='1'";
                                		$total_refered = mysqli_fetch_array(mysqli_query($connect,$qry_refered));
                                		$total_refered = $total_refered['refered'];	
                                		$total=$total_refered+1;
                    		 		
                    					$data1 = array(
                        		 			'user_type'=>'Normal',	
                        					'fname'  => $_GET['fname'],
                        					'lname'  => $_GET['lname'],
                        					'username'  => $_GET['username'],
                        					'password'  => md5($_GET['password']),
                        					'email'  =>  $_GET['email'],
                        					'code'  =>  $_GET['code'],
                        					'mobile'  =>  $_GET['mobile'],
                        					'refer'  => $_GET['username'],
                        					'referer'  =>  $_GET['referer'],
                        					'cur_balance' => '2',
                        					'won_balance'  => '0',
                        					'bonus_balance' => '2',
                        					'created_date'=> date("Y-m-d"),
                        					'device_id' => $_GET['device_id'],
                        					'is_block' => '0',
                        					'status'  =>  '1'
                    					);
                    
                                        $data2 = array(
                    		 			    'refered'=>$total	
                    		 		    );
                    		 		    
                    		 		    $data3 = array(
                        					'username'  => $_GET['username'],
                        					'refer_points'  =>  '0',
                        					'refer_code'  =>  $_GET['referer'],
                        					'refer_status'  => '0',
                        					'refer_date'=>$today
                    					);
                    					
                    					
                    					$qry1 = Insert('user_details', $data1);	
                    					$qry2 = Update('user_details', $data2,"WHERE refer = '".$_GET['referer']."'");	
                    					$qry3 = Insert('referral_details', $data3);	
                    					
                    					$set1['result'][] = array('msg' => "Register succesfully...!", 'success'=>'2');
                    					echo $val= str_replace('\\/', '/', json_encode($set1, JSON_UNESCAPED_UNICODE));
                    					die();
                    				} else {
                    				    $set1['result'][]=array('msg' => "Referral code not found or wrong!", 'success'=>'1');
                    					echo $val= str_replace('\\/', '/', json_encode($set1, JSON_UNESCAPED_UNICODE));
                    					die();
                    				}
                    			
                    			}
                    			
                    		} 
                    		else if(isset($_GET['email'])) {
                    
                    			$qry = "SELECT * FROM user_details WHERE username = '".$_GET['username']."' OR email = '".$_GET['email']."' OR mobile = '".$_GET['mobile']."'"; 
                    			$sel = mysqli_query($connect, $qry);
                    		
                    			if(mysqli_num_rows($sel) > 0) {
                    				$set['result'][]=array('msg' => "This username, email or mobile already used!", 'success'=>'0');
                    				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    				die();
                    			} else {
                    	 			$data = array(
                    	 			'user_type'=>'Normal',	
                    				'fname'  => $_GET['fname'],
                    				'lname'  => $_GET['lname'],
                    				'username'  => $_GET['username'],
                    				'password'  =>  md5($_GET['password']),
                    				'email'  =>  $_GET['email'],
                    				'code'  =>  $_GET['code'],
                    				'mobile'  =>  $_GET['mobile'],
                    				'refer'  => $_GET['username'],
                    				'cur_balance' => '0',
                    				'won_balance'  => '0',
                    				'bonus_balance' => '0',
                    				'created_date'=> date("Y-m-d"),
                    				'device_id' => $_GET['device_id'],
                        			'is_block' => '0',
                    				'status'  =>  '1'
                    				);
                    
                    				$qry = Insert('user_details', $data);									 
                    				
                    				$set['result'][] = array('msg' => "Register succesfully...!", 'success'=>'1');
                    				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    				die();
                    			}
                    		}
                    		else {
                    			 header( 'Content-Type: application/json; charset=utf-8' );
                    			 $json = json_encode($set);
                    
                    			 echo $json;
                    			 exit;		 
                    		}
                		}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    
    	
    	public function userLogin() {
    		include "../include/config.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$qry = "SELECT * FROM user_details WHERE (username = '".$_GET['username']."' OR mobile = '".$_GET['username']."') AND password = '".md5($_GET['password'])."'"; 
                		$result = mysqli_query($connect, $qry);
                		$num_rows = mysqli_num_rows($result);
                		$row = mysqli_fetch_assoc($result);
                			
                	    if ($num_rows > 0 && $row['status'] == 1) { 		 
                			$set['result'][] = array('id' => $row['id'], 'fname' => $row['fname'], 'lname' => $row['lname'], 'username' => $row['username'], 'email' => $row['email'], 'mobile' => $row['mobile'], 'success' => '1'); 
                		} else if ($num_rows > 0 && $row['status'] == 0) {
                			$set['result'][] = array('msg' => 'Your account is not active', 'success' => '0');
                		} else {
                			$set['result'][] = array('msg' => 'Invalid username or password', 'success' => '0');
                		}
                		 
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    
    	
    	public function getUserProfile() {
    		include "../include/config.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$id = $_GET['id'];
        
                  		$qry = "SELECT cur_balance, won_balance, bonus_balance, status FROM user_details WHERE id = '$id' ";
                		$result = mysqli_query($connect, $qry);	 
                		$row = mysqli_fetch_assoc($result);
                		  				 
                		$set['result'][] = array(
                			'cur_balance' => $row['cur_balance'],
                			'won_balance' => $row['won_balance'],
                			'bonus_balance' => $row['bonus_balance'],
                			'status' => $row['status'],
                			'success'=>'1'
                		);
                
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    
    	
    	public function updateUserProfile() {
    		include "../include/config.php";
    		include "../public/register.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			if(isset($_GET['password'])) {
                			$data = array(
                			'password'  =>  md5($_GET['password'])
                			);
                		} else if(isset($_GET['cur_balance'])) {
                			$data = array(
                			'cur_balance'  =>  $_GET['cur_balance'],
                			'won_balance'  =>  $_GET['won_balance'],
                			'bonus_balance'  =>  $_GET['bonus_balance']
                			);
                		} else{
                			$data = array(
                			'fname'  =>  $_GET['fname'],
                			'lname'  =>  $_GET['lname'],
                			'email'  =>  $_GET['email']
                			);
                		}
                			
                		$user_edit = Update('user_details', $data, "WHERE id = '".$_GET['id']."'");
                	 	$set['result'][] = array('msg'=>'Updated', 'success'=>'1');
                				 
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    
    	
    	public function updateUserPhoto() {
    	    include "../include/config.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			// check if "image" abd "user_id" is set 
                	    if(isset($_POST["image"]) && isset($_POST["user_id"])) {
                
                	        $data = $_POST["image"];
                	        $time = time();
                
                	        $user_id = $_POST["user_id"];
                	        //$oldImage ="images/"."1_1497679518.jpg";
                	        $ImageName = $user_id.'_'.$time.".jpg";
                
                	        //$filePath = "images/".$ImageName;
                	        $filePath = '../upload/avatar/'.$ImageName; // path of the file to store
                	        echo "file : ".$filePath;
                	        //echo "unlink : ".$oldImage;
                
                	        // check if file exits
                	        if (file_exists($filePath)) {
                	            unlink($filePath); // delete the old file
                	        } 
                	        // create a new empty file
                	        $myfile = fopen($filePath, "w") or die("Unable to open file!");
                	        // add data to that file
                	        file_put_contents($filePath, base64_decode($data));
                
                	        // update the Customer table with new image name.
                	        $query = " UPDATE user_details SET imageName = '$ImageName' WHERE id = '$user_id' ";
                	        mysqli_query($connect, $query);
                
                	    } 
                	    else {
                	        echo 'not set';
                	    }
                	    mysqli_close($connect);
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    
    
        public function resetPassword() {
    		include "../include/config.php";
    		include "../public/reset.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$data = array(
                			'password'  =>  md5($_GET['password'])
                		);
                			
                		$user_edit = Update('user_details', $data, "WHERE mobile = '".$_GET['mobile']."'");
                	 	$set['result'][] = array('msg'=>'Password Updated Successfully!!!', 'success'=>'1');
                				 
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	

        
    	public function getMatchPlay() {
    	    include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$user_id = $_GET['user_id'];
        	    
                	    $query = "SELECT t1.id, t1.title, t1.time, t1.winPrize,t1.imgCover, t1.perKill, t1.entryFee, t1.entryType, t1.version, t1.map, t1.isPrivateMatch, t1.matchType, t1.sponsoredBy, t1.match_status, t1.is_cancel, t1.cancel_reason, t1.private_match_code, t2.match_id, t2.room_size,t2.total_joined, t3.id As joined_status, COUNT(t3.id) AS user_joined, t4.rules, t5.image
                	    FROM match_details t1 
                	    LEFT JOIN room_details t2 ON t1.id = t2.match_id
                	    LEFT JOIN participant_details t3 ON (t1.id = t3.match_id and t3.user_id='$user_id')
                	    LEFT JOIN tbl_rules t4 ON t1.matchRules = t4.rule_id
                	    LEFT JOIN tbl_image t5 ON t1.imgCover = t5.img_id
                	    WHERE t1.match_status='0' AND t1.is_del = '0' GROUP BY t1.id ORDER BY t1.id ASC";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
        
        
        public function getMatchLive() {
    	    include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$user_id = $_GET['user_id'];
        	    
                	    $query = "SELECT t1.id, t1.title, t1.time, t1.winPrize,t1.imgCover, t1.perKill, t1.entryFee, t1.entryType, t1.version, t1.map, t1.isPrivateMatch, t1.matchType, t1.sponsoredBy, t1.spectateURL, t1.match_status, t1.is_cancel, t1.cancel_reason, t2.match_id, t2.room_id, t2.room_pass, t2.room_size,t2.total_joined, t3.id As joined_status, t4.rules, t5.image 
                	    FROM match_details t1 
                	    LEFT JOIN room_details t2 ON t1.id = t2.match_id
                	    LEFT JOIN participant_details t3 ON (t1.id = t3.match_id and t3.user_id='$user_id')
                	    LEFT JOIN tbl_rules t4 ON t1.matchRules = t4.rule_id
                	    LEFT JOIN tbl_image t5 ON t1.imgCover = t5.img_id
                	    WHERE t1.match_status='1' AND t1.is_del = '0' GROUP BY t1.id ORDER BY t1.id ASC";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
        
        
        public function getMatchResult() {
    	    include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$user_id = $_GET['user_id'];
        	    
                	    $query = "SELECT t1.id, t1.title, t1.time, t1.winPrize,t1.imgCover, t1.perKill, t1.entryFee, t1.entryType, t1.version, t1.map, t1.isPrivateMatch, t1.matchType, t1.sponsoredBy, t1.spectateURL, t1.matchNotes, t1.match_status, t3.id As joined_status, t4.image 
                	    FROM match_details t1 
                	    LEFT JOIN participant_details t3 ON (t1.id = t3.match_id and t3.user_id='$user_id')
                	    LEFT JOIN tbl_image t4 ON t1.imgCover = t4.img_id
                	    WHERE t1.match_status='3' AND t1.is_del = '0' GROUP BY t1.id ORDER BY t1.id DESC LIMIT 10";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
            
    
        	
        public function getMatchParticipants() {
    	    include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$match_id = $_GET['match_id'];
        	    
                	    $query = "SELECT id, user_id, pubg_id FROM participant_details WHERE match_id='$match_id' GROUP BY pubg_id";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    
    
        public function getMatchWinner() {
    	    include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$match_id = $_GET['match_id'];
        	    
                	    $query = "SELECT id, user_id, pubg_id, kills, position, win, prize FROM participant_details WHERE match_id='$match_id' AND win='1' GROUP BY pubg_id ORDER BY position ASC";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    
        
        public function getMatchFullResult() {
    	    include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$match_id = $_GET['match_id'];
        	    
                	    $query = "SELECT id, user_id, pubg_id, kills, position, win, prize FROM participant_details WHERE match_id='$match_id' GROUP BY pubg_id ORDER BY position ASC";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    		
    	
    	
    	public function getMySummary() {
    		include "../include/config.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$user_id = $_GET['user_id'];
        
                  		$qry = "SELECT COUNT(DISTINCT(t1.match_id)) AS maches_played,sum(t1.kills) AS total_kills,sum(t1.prize) AS amount_won 
                  		FROM participant_details t1
                  		LEFT JOIN match_details t2 ON (t1.match_id = t2.id and t1.user_id='$user_id')
                  		WHERE t1.user_id = '$user_id' AND t2.match_status ='3'";
                		$result = mysqli_query($connect, $qry);	 
                		$row = mysqli_fetch_assoc($result);
                		  				 
                		$set['result'][] = array(
                			'maches_played' => $row['maches_played'],
                			'total_kills'=>$row['total_kills'],
                			'amount_won'=>$row['amount_won'],
                			'success'=>'1'
                		);
            
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
        		
   
        public function getMyStatistics() {
            include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$user_id = $_GET['user_id'];
        	    
                	    $query = "SELECT t1.id, t1.title, t1.time, t1.entryFee, t3.prize 
                	    FROM participant_details t3 
                	    LEFT JOIN match_details t1 ON (t3.match_id = t1.id and t3.user_id='$user_id')
                	    WHERE t1.match_status='3' AND t3.user_id='$user_id'";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
        	
        	
    	public function getMyTransactions() {
            include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$user_id = $_GET['user_id'];
        	    
                	    $query = "SELECT id, user_id, order_id, payment_id, amount, remark, type, from_unixtime(date, '%d-%m-%Y') AS date, wallet, coins, status, account_holder_name, account_holder_id FROM transaction_details WHERE user_id='$user_id' ORDER BY id DESC";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                    	    header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    
    
    
        public function getTopPlayers() {
    	    include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$query = "SELECT pubg_id, sum(prize) AS prize FROM participant_details GROUP BY pubg_id ORDER BY prize DESC LIMIT 0,10";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
    	public function getMyReferralsSummary() {
    		include "../include/config.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$refer_code = $_GET['refer_code'];
        
                  		$qry = "SELECT count(refer_code) AS refer_code, sum(refer_points) AS refer_points FROM referral_details
                  		WHERE refer_code='$refer_code'";
                		$result = mysqli_query($connect, $qry);	 
                		$row = mysqli_fetch_assoc($result);
                		  				 
                		$set['result'][] = array(
                			'refer_code' => $row['refer_code'],
                			'refer_points'=>$row['refer_points'],
                			'success'=>'1'
                		);
                
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
    	public function getMyReferralsList() {
            include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$refer_code = $_GET['refer_code'];
        	    
                	    $query = "SELECT t1.refer_date, t1.refer_status, t2.fname, t2.lname 
                	    FROM referral_details t1
                	    LEFT JOIN user_details t2 ON t1.username = t2.username
                	    WHERE t1.refer_code='$refer_code'";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
    	public function getTopLeaders() {
    	    include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$query = "SELECT sum(t1.refer_points) AS refer_points, t2.fname, t2.lname 
                	    FROM referral_details t1
                	    LEFT JOIN user_details t2 ON t1.refer_code = t2.refer
                	    GROUP BY t1.refer_code ORDER BY refer_points DESC LIMIT 0,10";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
        	
    	public function getMyRewardsSummary() {
    		include "../include/config.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$username = $_GET['username'];
        
                  		$qry = "SELECT count(username) AS rewards, sum(reward_points) AS earnings FROM rewarded_details
                  		WHERE username='$username'";
                		$result = mysqli_query($connect, $qry);	 
                		$row = mysqli_fetch_assoc($result);
                		  				 
                		$set['result'][] = array(
                			'rewards' => $row['rewards'],
                			'earnings'=>$row['earnings'],
                			'success'=>'1'
                		);
                
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
            		    exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
        		
        
        public function getMyRewardsList() {
            include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$username = $_GET['username'];
        	    
                	    $query = "SELECT from_unixtime(reward_date, '%d-%m-%Y') AS reward_date, COUNT(reward_date) AS reward_count, SUM(reward_points) AS reward_points 
                	    FROM rewarded_details
                	    WHERE username='$username' GROUP BY from_unixtime(reward_date, '%d-%m-%Y') ORDER BY reward_date DESC";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
    	public function getTopRewards() {
    	    include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$query = "SELECT sum(t1.reward_points) AS reward_points, t2.fname, t2.lname 
                	    FROM rewarded_details t1
                	    LEFT JOIN user_details t2 ON t1.username = t2.username
                	    GROUP BY t1.username ORDER BY reward_points DESC LIMIT 0,10";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
    	
        public function joinMatch() {
    		include "../include/config.php";
    		include "../public/join.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			if(isset($_GET['match_id']) && isset($_GET['pubg_id'])) {
                            $entryType = $_GET['entryType'];
                            $entryFee = $_GET['entryFee'];
                            $matchType = $_GET['matchType'];
                            $privateStatus = $_GET['privateStatus'];
                            $accessKey = $_GET['accessKey'];
                    		
                		    if($privateStatus == "yes") {
                				$qry3 = "SELECT private_match_code FROM match_details WHERE id = '".$_GET['match_id']."'"; 
                        		$row3 = mysqli_fetch_array(mysqli_query($connect,$qry3));
                            	$private_match_code = $row3['private_match_code'];
                            	
                            	if ($private_match_code == $accessKey) {
                        	        $qry1 = "SELECT * FROM participant_details WHERE match_id = '".$_GET['match_id']."' AND pubg_id = '".$_GET['pubg_id']."'"; 
                		            $sel1 = mysqli_query($connect, $qry1);
                		            
                    			    if(mysqli_num_rows($sel1) > 0) {
                        				$set['result'][]=array('msg' => "This pubg username is already exist!", 'success'=>'0');
                        				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                        				die();
                        			}
                    			    else {
                    			        if ($entryType == 'Paid') {
                            			    $qry4 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                			$sel4 = mysqli_query($connect, $qry4);
                                        		
                                			if(mysqli_num_rows($sel4) > 0) {
                                			    $today = date("Y-m-d");
                		            
                                			    $qry5 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                        		$row5 = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                    		    $refer_code = $row5['refer_code'];	
                                        		
                                		        $qry6 = "SELECT cur_balance, bonus_balance FROM user_details WHERE refer = '$refer_code'"; 
                                        		$row6 = mysqli_fetch_array(mysqli_query($connect,$qry6));
                                        		$cur_balance = $row6['cur_balance'];
                                        		$bonus_balance = $row6['bonus_balance'];	
                                        		$balance=$cur_balance+2;
                                        		$bonus=$bonus_balance+2;
                                        		
                                        		$qry7 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                        		$row7 = mysqli_fetch_array(mysqli_query($connect,$qry7));
                                        		$total_joined = $row7['total_joined'];	
                                        		$joined=$total_joined+1;
                                        		
                                        		$qry8 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                        		$row8 = mysqli_fetch_array(mysqli_query($connect,$qry8));
                                        		$cur_balance1 = $row8['cur_balance'];	
                                        		$won_balance1 = $row8['won_balance'];
                                        		$bonus_balance1 = $row8['bonus_balance'];
                                        	
                                        		
                                        		if($won_balance1 >= $entryFee) {
                                        		    $won_balance2 = $won_balance1 - $entryFee;
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                        		    $bonus_balance2 = $bonus_balance1;
                                        		}
                                        		else if($won_balance1 < $entryFee && $bonus_balance1 >= $entryFee) {
                                        		    $diff = $entryFee - $won_balance1;
                                        		    $won_balance2 = '0';
                                        		    $bonus_balance2 = $bonus_balance1 - $diff;
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                        		}
                                        		else if($won_balance1 < $entryFee && $bonus_balance1 < $entryFee) {
                                        		    $diff = $won_balance1 + $bonus_balance1;
                                        		    if($diff <= $entryFee) {
                                        		        $won_balance2 = '0';
                                        		        $bonus_balance2 = '0';
                                        		        $cur_balance2 = $cur_balance1 - $entryFee;    
                                        		    }
                                        		    else if($won_balance1 >= $bonus_balance1) {
                                            		    $won_balance2 = '0';
                                            		    $bonus_balance2 = $won_balance1 - $bonus_balance1;
                                            		    $cur_balance2 = $cur_balance1 - $entryFee;
                                        		    }
                                        		    else if($won_balance1 <= $bonus_balance1) {
                                            		    $won_balance2 = '0';
                                            		    $bonus_balance2 = $bonus_balance1 - $won_balance1;
                                            		    $cur_balance2 = $cur_balance1 - $entryFee;
                                        		    }
                                        		}
                                        		else {
                                        		    $won_balance2 = '0';
                                        		    $bonus_balance2 = '0';
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                        		}
                                        		
                                        		
                                			    $data1 = array(
                                    				'match_id'  => $_GET['match_id'],
                                    				'user_id'  => $_GET['user_id'],
                                    				'pubg_id'  => $_GET['pubg_id'],
                                    				'name'  =>  $_GET['name']
                                				);
                                
                                                $data2 = array(
                                					'refer_points'  =>  '2',
                                					'refer_status'  => '1',
                                					'refer_date'=>$today
                            					);
                            					
                            					$data3 = array(
                                					'cur_balance'  =>  $balance,
                                					'bonus_balance' => $bonus
                            					);
                            					
                            					$data4 = array(
                                					'total_joined'  =>  $joined
                            					);
                            					
                            					$data5 = array(
                                					'cur_balance'  =>  $cur_balance2,
                                					'won_balance'  =>  $won_balance2,
                                					'bonus_balance' => $bonus_balance2
                            					);
                            					
                            					if ($_GET['pubg_id']!="null") {
                                				    $qry9 = Insert('participant_details', $data1);
                            					}
                                				$qry10 = Update('referral_details', $data2,"WHERE refer_code = '$refer_code' AND username = '".$_GET['username']."'");
                                				$qry11 = Update('user_details', $data3,"WHERE refer = '$refer_code'");
                                				
                                				$qry12 = Update('room_details', $data4,"WHERE match_id = '".$_GET['match_id']."'");
                                				
                                				$qry13 = Update('user_details', $data5,"WHERE id = '".$_GET['user_id']."'");
                                				
                                				$set['result'][]=array('msg' => "Joined succesfully...!", 'success'=>'2');
                                				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                				die();
                            			    }
                            			    else {
                            			        $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                        		$row4 = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                        		$total_joined = $row4['total_joined'];	
                                        		$joined=$total_joined+1;
                                        		
                                        		$qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                        		$row5 = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                        		$cur_balance1 = $row5['cur_balance'];	
                                        		$won_balance1 = $row5['won_balance'];
                                        		$bonus_balance1 = $row5['bonus_balance'];
                                        	
                                        		
                                        		if($won_balance1 >= $entryFee) {
                                        		    $won_balance2 = $won_balance1 - $entryFee;
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                        		    $bonus_balance2 = $bonus_balance1;
                                        		}
                                        		else if($won_balance1 < $entryFee && $bonus_balance1 >= $entryFee) {
                                        		    $diff = $entryFee - $won_balance1;
                                        		    $won_balance2 = '0';
                                        		    $bonus_balance2 = $bonus_balance1 - $diff;
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                        		}
                                        		else if($won_balance1 < $entryFee && $bonus_balance1 < $entryFee) {
                                        		    $diff = $won_balance1 + $bonus_balance1;
                                        		    if($diff <= $entryFee) {
                                        		        $won_balance2 = '0';
                                        		        $bonus_balance2 = '0';
                                        		        $cur_balance2 = $cur_balance1 - $entryFee;    
                                        		    }
                                        		    else if($won_balance1 >= $bonus_balance1) {
                                            		    $won_balance2 = '0';
                                            		    $bonus_balance2 = $won_balance1 - $bonus_balance1;
                                            		    $cur_balance2 = $cur_balance1 - $entryFee;
                                        		    }
                                        		    else if($won_balance1 <= $bonus_balance1) {
                                            		    $won_balance2 = '0';
                                            		    $bonus_balance2 = $bonus_balance1 - $won_balance1;
                                            		    $cur_balance2 = $cur_balance1 - $entryFee;
                                        		    }
                                        		}
                                        		else {
                                        		    $won_balance2 = '0';
                                        		    $bonus_balance2 = '0';
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                        		}
                                        		
                                        		
                            			        $data1 = array(
                                    				'match_id'  => $_GET['match_id'],
                                    				'user_id'  => $_GET['user_id'],
                                    				'pubg_id'  => $_GET['pubg_id'],
                                    				'name'  =>  $_GET['name']
                                				);
                                
                                	            $data2 = array(
                                					'total_joined'  =>  $joined
                            					);
                            					
                            					$data3 = array(
                                					'cur_balance'  =>  $cur_balance2,
                                					'won_balance'  =>  $won_balance2,
                                					'bonus_balance' => $bonus_balance2
                            					);
                            					
                            					if ($_GET['pubg_id']!="null") {
                                				    $qry6 = Insert('participant_details', $data1);				
                            					}
                                				$qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                                				
                                				$qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                                				
                                				$set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                                				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                                				die();
                            			    }
                    			        }
                        			    else {
                        			        $qry4 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                    		$row4 = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                    		$total_joined = $row4['total_joined'];	
                                    		$joined=$total_joined+1;
                                    		
                                    		$qry5 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                    		$row5 = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                    		$cur_balance1 = $row5['cur_balance'];	
                                    		$won_balance1 = $row5['won_balance'];
                                    		$bonus_balance1 = $row5['bonus_balance'];
                                    	
                                    		
                                    		if($won_balance1 >= $entryFee) {
                                    		    $won_balance2 = $won_balance1 - $entryFee;
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		    $bonus_balance2 = $bonus_balance1;
                                    		}
                                    		else if($won_balance1 < $entryFee && $bonus_balance1 >= $entryFee) {
                                    		    $diff = $entryFee - $won_balance1;
                                    		    $won_balance2 = '0';
                                    		    $bonus_balance2 = $bonus_balance1 - $diff;
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		}
                                    		else if($won_balance1 < $entryFee && $bonus_balance1 < $entryFee) {
                                    		    $diff = $won_balance1 + $bonus_balance1;
                                    		    if($diff <= $entryFee) {
                                    		        $won_balance2 = '0';
                                    		        $bonus_balance2 = '0';
                                    		        $cur_balance2 = $cur_balance1 - $entryFee;    
                                    		    }
                                    		    else if($won_balance1 >= $bonus_balance1) {
                                        		    $won_balance2 = '0';
                                        		    $bonus_balance2 = $won_balance1 - $bonus_balance1;
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		    }
                                    		    else if($won_balance1 <= $bonus_balance1) {
                                        		    $won_balance2 = '0';
                                        		    $bonus_balance2 = $bonus_balance1 - $won_balance1;
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		    }
                                    		}
                                    		else {
                                    		    $won_balance2 = '0';
                                    		    $bonus_balance2 = '0';
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		}
                                    		
                                    		
                        			        $data1 = array(
                                				'match_id'  => $_GET['match_id'],
                                				'user_id'  => $_GET['user_id'],
                                				'pubg_id'  => $_GET['pubg_id'],
                                				'name'  =>  $_GET['name']
                            				);
                            
                            	            $data2 = array(
                            					'total_joined'  =>  $joined
                        					);
                        					
                        					$data3 = array(
                            					'cur_balance'  =>  $cur_balance2,
                            					'won_balance'  =>  $won_balance2,
                            					'bonus_balance' => $bonus_balance2
                        					);
                        					
                        					if ($_GET['pubg_id']!="null") {
                            				    $qry6 = Insert('participant_details', $data1);
                        					}
                            				$qry7 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                            				
                            				$qry8 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                            				
                            				$set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                            				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            				die();
                        			    }
                        		    }
                            	}
                            	else {
                        	    	$set['result'][]=array('msg' => "Invalide Secret Key", 'success'=>'3');
                    				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    				die();
                            	}
                			} 
                			else {
                			    $qry1 = "SELECT * FROM participant_details WHERE match_id = '".$_GET['match_id']."' AND pubg_id = '".$_GET['pubg_id']."'"; 
                		            $sel1 = mysqli_query($connect, $qry1);
                		
                			    if (mysqli_num_rows($sel1) > 0) {
                    				$set['result'][]=array('msg' => "This pubg username is already exist!", 'success'=>'0');
                    				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    				die();
                    			}
                			    else {
                			        if ($entryType == 'Paid') {
                        			    $qry3 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                            			$sel3 = mysqli_query($connect, $qry3);
                                    		
                            			if(mysqli_num_rows($sel3) > 0) {
                            			    $today = date("Y-m-d");
                            			   
                            			    $qry4 = "SELECT refer_code FROM referral_details WHERE username = '".$_GET['username']."' AND refer_status = '0'"; 
                                    		$row4 = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                		    $refer_code = $row4['refer_code'];	
                                    		
                            		        $qry5 = "SELECT cur_balance, bonus_balance FROM user_details WHERE refer = '$refer_code'"; 
                                    		$row5 = mysqli_fetch_array(mysqli_query($connect,$qry5));
                                    		$cur_balance = $row5['cur_balance'];
                                    		$bonus_balance = $row5['bonus_balance'];
                                    		$balance=$cur_balance+2;
                                    		$bonus=$bonus_balance+2;
                                    		
                                    		$qry6 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                    		$row6 = mysqli_fetch_array(mysqli_query($connect,$qry6));
                                    		$total_joined = $row6['total_joined'];	
                                    		$joined=$total_joined+1;
                                    		
                                    		$qry7 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                    		$row7 = mysqli_fetch_array(mysqli_query($connect,$qry7));
                                    		$cur_balance1 = $row7['cur_balance'];	
                                    		$won_balance1 = $row7['won_balance'];
                                    		$bonus_balance1 = $row7['bonus_balance'];
                                    	
                                    		
                                    		if($won_balance1 >= $entryFee) {
                                    		    $won_balance2 = $won_balance1 - $entryFee;
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		    $bonus_balance2 = $bonus_balance1;
                                    		}
                                    		else if($won_balance1 < $entryFee && $bonus_balance1 >= $entryFee) {
                                    		    $diff = $entryFee - $won_balance1;
                                    		    $won_balance2 = '0';
                                    		    $bonus_balance2 = $bonus_balance1 - $diff;
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		}
                                    		else if($won_balance1 < $entryFee && $bonus_balance1 < $entryFee) {
                                    		    $diff = $won_balance1 + $bonus_balance1;
                                    		    if($diff <= $entryFee) {
                                    		        $won_balance2 = '0';
                                    		        $bonus_balance2 = '0';
                                    		        $cur_balance2 = $cur_balance1 - $entryFee;    
                                    		    }
                                    		    else if($won_balance1 >= $bonus_balance1) {
                                        		    $won_balance2 = '0';
                                        		    $bonus_balance2 = $won_balance1 - $bonus_balance1;
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		    }
                                    		    else if($won_balance1 <= $bonus_balance1) {
                                        		    $won_balance2 = '0';
                                        		    $bonus_balance2 = $bonus_balance1 - $won_balance1;
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		    }
                                    		}
                                    		else {
                                    		    $won_balance2 = '0';
                                    		    $bonus_balance2 = '0';
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		}
                                    	
                                    	
                            			    $data1 = array(
                                				'match_id'  => $_GET['match_id'],
                                				'user_id'  => $_GET['user_id'],
                                				'pubg_id'  => $_GET['pubg_id'],
                                				'name'  =>  $_GET['name']
                            				);
                            
                                            $data2 = array(
                            					'refer_points'  =>  '2',
                            					'refer_status'  => '1',
                            					'refer_date'=>$today
                        					);
                        					
                        					$data3 = array(
                            					'cur_balance'  =>  $balance,
                            					'bonus_balance' => $bonus
                        					);
                        					
                        					$data4 = array(
                            					'total_joined'  =>  $joined
                        					);
                        					
                        					$data5 = array(
                            					'cur_balance'  =>  $cur_balance2,
                            					'won_balance'  =>  $won_balance2,
                            					'bonus_balance' => $bonus_balance2
                        					);
                        					
                        					if ($_GET['pubg_id']!="null") {
                            				$qry8 = Insert('participant_details', $data1);
                        					}
                        				
                            				$qry9 = Update('referral_details', $data2,"WHERE refer_code = '$refer_code' AND username = '".$_GET['username']."'");
                            				$qry10 = Update('user_details', $data3,"WHERE refer = '$refer_code'");
                            				
                            				$qry11 = Update('room_details', $data4,"WHERE match_id = '".$_GET['match_id']."'");
                            				
                            				$qry12 = Update('user_details', $data5,"WHERE id = '".$_GET['user_id']."'");
                            				
                            				$set['result'][]=array('msg' => "Joined succesfully...!", 'success'=>'2');
                            				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            				die();
                        			    }
                        			    else {
                        			        $qry3 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                    		$row3 = mysqli_fetch_array(mysqli_query($connect,$qry3));
                                    		$total_joined = $row3['total_joined'];	
                                    		$joined=$total_joined+1;
                                    		
                                    		$qry4 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                    		$row4 = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                    		$cur_balance1 = $row4['cur_balance'];	
                                    		$won_balance1 = $row4['won_balance'];
                                    		$bonus_balance1 = $row4['bonus_balance'];
                                    	
                                    		
                                    		if($won_balance1 >= $entryFee) {
                                    		    $won_balance2 = $won_balance1 - $entryFee;
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		    $bonus_balance2 = $bonus_balance1;
                                    		}
                                    		else if($won_balance1 < $entryFee && $bonus_balance1 >= $entryFee) {
                                    		    $diff = $entryFee - $won_balance1;
                                    		    $won_balance2 = '0';
                                    		    $bonus_balance2 = $bonus_balance1 - $diff;
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		}
                                    		else if($won_balance1 < $entryFee && $bonus_balance1 < $entryFee) {
                                    		    $diff = $won_balance1 + $bonus_balance1;
                                    		    if($diff <= $entryFee) {
                                    		        $won_balance2 = '0';
                                    		        $bonus_balance2 = '0';
                                    		        $cur_balance2 = $cur_balance1 - $entryFee;    
                                    		    }
                                    		    else if($won_balance1 >= $bonus_balance1) {
                                        		    $won_balance2 = '0';
                                        		    $bonus_balance2 = $won_balance1 - $bonus_balance1;
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		    }
                                    		    else if($won_balance1 <= $bonus_balance1) {
                                        		    $won_balance2 = '0';
                                        		    $bonus_balance2 = $bonus_balance1 - $won_balance1;
                                        		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		    }
                                    		}
                                    		else {
                                    		    $won_balance2 = '0';
                                    		    $bonus_balance2 = '0';
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                    		}
                                    		
                                    		
                        			        $data1 = array(
                                				'match_id'  => $_GET['match_id'],
                                				'user_id'  => $_GET['user_id'],
                                				'pubg_id'  => $_GET['pubg_id'],
                                				'name'  =>  $_GET['name']
                            				);
                            
                            	            $data2 = array(
                            					'total_joined'  =>  $joined
                        					);
                        					
                        					$data3 = array(
                            					'cur_balance'  =>  $cur_balance2,
                            					'won_balance'  =>  $won_balance2,
                            					'bonus_balance' => $bonus_balance2
                        					);
                        					
                        					if ($_GET['pubg_id']!="null") {
                            				$qry5 = Insert('participant_details', $data1);
                        					}
                            				$qry6 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                            				
                            				$qry7 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                            				
                            				$set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                            				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                            				die();
                        			    }
                			        }
                    			    else {
                    			        $qry3 = "SELECT total_joined FROM room_details WHERE match_id = '".$_GET['match_id']."'"; 
                                		$row3 = mysqli_fetch_array(mysqli_query($connect,$qry3));
                                		$total_joined = $row3['total_joined'];	
                                		$joined=$total_joined+1;
                                		
                                		$qry4 = "SELECT cur_balance, won_balance, bonus_balance FROM user_details WHERE id = '".$_GET['user_id']."'"; 
                                		$row4 = mysqli_fetch_array(mysqli_query($connect,$qry4));
                                		$cur_balance1 = $row4['cur_balance'];	
                                		$won_balance1 = $row4['won_balance'];
                                		$bonus_balance1 = $row4['bonus_balance'];
                                	
                                		
                                		if($won_balance1 >= $entryFee) {
                                		    $won_balance2 = $won_balance1 - $entryFee;
                                		    $cur_balance2 = $cur_balance1 - $entryFee;
                                		    $bonus_balance2 = $bonus_balance1;
                                		}
                                		else if($won_balance1 < $entryFee && $bonus_balance1 >= $entryFee) {
                                		    $diff = $entryFee - $won_balance1;
                                		    $won_balance2 = '0';
                                		    $bonus_balance2 = $bonus_balance1 - $diff;
                                		    $cur_balance2 = $cur_balance1 - $entryFee;
                                		}
                                		else if($won_balance1 < $entryFee && $bonus_balance1 < $entryFee) {
                                		    $diff = $won_balance1 + $bonus_balance1;
                                		    if($diff <= $entryFee) {
                                		        $won_balance2 = '0';
                                		        $bonus_balance2 = '0';
                                		        $cur_balance2 = $cur_balance1 - $entryFee;    
                                		    }
                                		    else if($won_balance1 >= $bonus_balance1) {
                                    		    $won_balance2 = '0';
                                    		    $bonus_balance2 = $won_balance1 - $bonus_balance1;
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                		    }
                                		    else if($won_balance1 <= $bonus_balance1) {
                                    		    $won_balance2 = '0';
                                    		    $bonus_balance2 = $bonus_balance1 - $won_balance1;
                                    		    $cur_balance2 = $cur_balance1 - $entryFee;
                                		    }
                                		}
                                		else {
                                		    $won_balance2 = '0';
                                		    $bonus_balance2 = '0';
                                		    $cur_balance2 = $cur_balance1 - $entryFee;
                                		}
                                		
                            		
                    			        $data1 = array(
                            				'match_id'  => $_GET['match_id'],
                            				'user_id'  => $_GET['user_id'],
                            				'pubg_id'  => $_GET['pubg_id'],
                            				'name'  =>  $_GET['name']
                        				);
                        
                        	            $data2 = array(
                        					'total_joined'  =>  $joined
                    					);
                    					
                    					$data3 = array(
                        					'cur_balance'  =>  $cur_balance2,
                        					'won_balance'  =>  $won_balance2,
                        					'bonus_balance' => $bonus_balance2
                    					);
                    					
                    					if ($_GET['pubg_id']!="null") {
                        				$qry5 = Insert('participant_details', $data1);				}
                        				$qry6 = Update('room_details', $data2,"WHERE match_id = '".$_GET['match_id']."'");
                        				
                        				$qry7 = Update('user_details', $data3,"WHERE id = '".$_GET['user_id']."'");
                        				
                        				$set['result'][] = array('msg' => "Joined succesfully...!", 'success'=>'2');
                        				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                        				die();
                			        }   
                			    }
                			}
                			
                		} else {
                			 header( 'Content-Type: application/json; charset=utf-8' );
                			 $json = json_encode($set);
                
                			 echo $json;
                			 exit;		 
                		}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
    
        public function getAddCoins() {
            include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$query = "SELECT * FROM tbl_payouts WHERE status = '0' AND mode = '1' ORDER BY id DESC";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
        public function getRedeemCoins() {
            include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$query = "SELECT * FROM tbl_payouts WHERE status = '0' AND mode = '0' ORDER BY id DESC";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}	
    	
            
            
        public function addTransaction() {
    		include "../include/config.php";
    		include "../public/transaction.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			if(isset($_GET['account_holder_name']) && isset($_GET['account_holder_id'])) {
                		    $play_coins = $_GET['coins'];
                		    $user_id = $_GET['user_id'];
                		    $current_time = time();
                		    
                		    $qry = "SELECT cur_balance, won_balance FROM user_details WHERE id = '$user_id'"; 
                            $userdata = mysqli_fetch_array(mysqli_query($connect,$qry));
                         	$tot_coins = $userdata['cur_balance'];
                         	$won_coins = $userdata['won_balance'];
                            $new_tot_coins = $tot_coins - $play_coins;
                            $new_won_coins = $won_coins - $play_coins;
                                
                			$data1 = array(
                				'user_id'  => $_GET['user_id'],
                				'order_id'  => $_GET['order_id'],
                				'account_holder_name'  => $_GET['account_holder_name'],
                				'account_holder_id'  => $_GET['account_holder_id'],
                				'amount'  => $_GET['amount'],
                				'coins'  => $_GET['coins'],
                				'wallet'  => $_GET['wallet'],
                				'remark'  => $_GET['remark'],
                				'type'  =>  $_GET['type'],
                				'date' => $current_time,
                				'status'  =>  '0'
            				);
            
                            $data2 = array(
            					'cur_balance'  =>  $new_tot_coins,
            					'won_balance'  =>  $new_won_coins
            				);
            				
            				$qry1 = Insert('transaction_details', $data1);
            				$qry2 = Update('user_details', $data2,"WHERE id = '$user_id'");
            			
            				$set['result'][] = array('msg' => "Your request has been successfully sent. Please wait for approval.", 'success'=>'1');
            				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            				die();
                			
                		} else if(isset($_GET['order_id']) && isset($_GET['payment_id'])) {
                		    $play_coins = $_GET['coins'];
                		    $user_id = $_GET['user_id'];
                		    $current_time = time();
                		    
                		    $qry = "SELECT cur_balance FROM user_details WHERE id = '$user_id'"; 
                            $userdata = mysqli_fetch_array(mysqli_query($connect,$qry));
                         	$tot_coins = $userdata['cur_balance'];
                            $new_tot_coins = $tot_coins + $play_coins;
                            
                			$data1 = array(
                				'user_id'  => $_GET['user_id'],
                				'order_id'  => $_GET['order_id'],
                				'payment_id'  => $_GET['payment_id'],
                				'amount'  => $_GET['amount'],
                				'coins'  => $_GET['coins'],
                				'wallet'  => $_GET['wallet'],
                				'remark'  => $_GET['remark'],
                				'type'  =>  $_GET['type'],
                				'date' => $current_time,
                				'status'  =>  '1'
                			);
                                
                            $data2 = array(
            					'cur_balance'  =>  $new_tot_coins
            				);
            				
            				$qry1 = Insert('transaction_details', $data1);	
            				$qry2 = Update('user_details', $data2,"WHERE id = '$user_id'");
            				
            				$set['result'][] = array('msg' => "Your request has been successfully sent. Please wait for approval.", 'success'=>'1');
            				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            				die();
                			
                		} else {
                			 header( 'Content-Type: application/json; charset=utf-8' );
                			 $json = json_encode($set);
                
                			 echo $json;
                			 exit;		 
                		}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    		
    	public function addReward() {
    		include "../include/config.php";
    		include "../public/rewards.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			if(isset($_GET['username']) && isset($_GET['reward_points'])) {
                            $username = $_GET['username'];
                            $play_coins = $_GET['reward_points'];
                            $reward_limit = $_GET['reward_limits'];
                            $current_time = time();
                                
                            $sql = "SELECT count(id) AS count FROM rewarded_details WHERE username = '$username' AND from_unixtime(reward_date, '%Y-%m-%d') = CURDATE() ORDER BY id DESC LIMIT 1"; 
                    		$res = mysqli_fetch_array(mysqli_query($connect, $sql));	 
                    		$count = $res['count']+1;
                    		   
                    		if($count >= $reward_limit) {
                                $qry = "SELECT cur_balance, bonus_balance FROM user_details WHERE username = '$username'"; 
                                $userdata = mysqli_fetch_array(mysqli_query($connect,$qry));
                             	$tot_coins = $userdata['cur_balance'];
                             	$bonus_coins = $userdata['bonus_balance'];
                                $new_tot_coins = $tot_coins + $play_coins;
                                $new_bonus_coins = $bonus_coins + $play_coins;
                                 
                                $data1 = array(
                    				'username'  => $username,
                    				'reward_points'  => $play_coins,
                    				'reward_date'  =>  $current_time
                        		);
                        				
                            	$data2 = array(
                					'cur_balance'  =>  $new_tot_coins,
                					'bonus_balance'  =>  $new_bonus_coins
                				);
                				
                				$qry1 = Insert('rewarded_details', $data1);
                				$qry2 = Update('user_details', $data2,"WHERE username = '$username'");
                				
                                $diff = ($current_time + 86400 - ($current_time % 86400)) - $current_time;
                                $set['result'][]=array('msg' => $diff, 'success'=>'0');
                    			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    			die();
                			} 
                			else {    
                                $data1 = array(
                    				'username'  => $username,
                    				'reward_points'  => '0',
                    				'reward_date'  =>  $current_time
                        		);
                        				
                				
                				$qry1 = Insert('rewarded_details', $data1);
                				
                				$diff = $reward_limit - $count;
                				$set['result'][]=array('msg' => "Please complete this task. $diff time letf to redeem reward.", 'success'=>'1');
                				echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                				die();
                			}
                			
                		} else {
                			 header( 'Content-Type: application/json; charset=utf-8' );
                			 $json = json_encode($set);
                
                			 echo $json;
                			 exit;		 
                		}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
    	
    	public function getRewards() {
    		include "../include/config.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			if(isset($_GET['username']) && isset($_GET['reward_limits'])) {
                            $username = $_GET['username'];
                            $reward_limit = $_GET['reward_limits'];
                            $current_time = time();
                                
                            $sql = "SELECT count(id) AS count FROM rewarded_details WHERE username = '$username' AND from_unixtime(reward_date, '%Y-%m-%d') = CURDATE() ORDER BY id DESC LIMIT 1"; 
                    		$res = mysqli_fetch_array(mysqli_query($connect, $sql));	 
                    		$count = $res['count'];
                    		   
                    		if($count >= $reward_limit) {
                                $diff = ($current_time + 86400 - ($current_time % 86400)) - $current_time;
                                $set['result'][]=array('msg' => $diff, 'success'=>'0');
                    			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    			die();
                			} 
                			else {
                          		$set['result'][]=array('msg' => $count, 'success'=>'1');
                    			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
                    			die();
                			}
                        } else {
                			 header( 'Content-Type: application/json; charset=utf-8' );
                			 $json = json_encode($set);
                
                			 echo $json;
                			 exit;		 
                		}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
    	
    	public function getNotification() {
    	    include "../include/config.php";
    	    
    	    if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$query = "SELECT * FROM announcement_details ORDER BY id DESC LIMIT 0,5";
                	    $result = mysqli_query($connect,$query);
                
                        if($result){
                        	while($row=mysqli_fetch_array($result)){
                                $flag[]=$row;
                            }
                        	header( 'Content-Type: application/json; charset=utf-8' );
                            $json = json_encode($flag);
                    
                            echo $json;
                            exit;
                    	}
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
    	public function updateApp() {
    		include "../include/config.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$qry = "SELECT * FROM update_details";
                		$result = mysqli_query($connect, $qry);	 
                		$row = mysqli_fetch_assoc($result);
                		  				 
                		$set['result'][] = array(
                			'id' => $row['id'],
                			'force_update' => $row['force_update'],
                			'whats_new' => $row['whats_new'],
                			'update_date' => $row['update_date'],
                			'latest_version_name' => $row['latest_version_name'],
                			'update_url' => $row['update_url'],
                			'success'=>'1'
                		);
                
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
        		
        
        public function getAboutUs() {
    		include "../include/config.php";
            
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$qry = "SELECT * FROM tbl_about";
                		$result = mysqli_query($connect, $qry);	 
                		$row = mysqli_fetch_assoc($result);
                		  				 
                		$set['result'][] = array(
                			'content' => $row['content'],
                			'success'=>'1'
                		);
                
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    		
        
        public function getContactUs() {
    		include "../include/config.php";
            
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$qry = "SELECT * FROM tbl_contact";
                		$result = mysqli_query($connect, $qry);	 
                		$row = mysqli_fetch_assoc($result);
                		  				 
                		$set['result'][] = array(
                			'title' => $row['title'],
                			'phone' => $row['phone'],
                			'email' => $row['email'],
                			'address' => $row['address'],
                			'other' => $row['other'],
                			'whatsapp_no' => $row['whatsapp_no'],
                			'messenger_id' => $row['messenger_id'],
                			'fb_follow' => $row['fb_follow'],
                			'ig_follow' => $row['ig_follow'],
                			'twitter_follow' => $row['twitter_follow'],
                			'youtube_follow' => $row['youtube_follow'],
                			'success'=>'1'
                		);
                
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    	
    	public function getTermsConditions() {
    		include "../include/config.php";
            
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$qry = "SELECT * FROM tbl_terms_conditions";
                		$result = mysqli_query($connect, $qry);	 
                		$row = mysqli_fetch_assoc($result);
                		  				 
                		$set['result'][] = array(
                			'content' => $row['content'],
                			'success'=>'1'
                		);
                
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    		
    	public function getPrivacyPolicy() {
    		include "../include/config.php";
    
            if(isset($_GET['access_key'])) {
            
            	$akcode = trim($_GET['access_key']);
            	$personalToken = "f7UVwyKdoNIPZGrhYc7sWUJ7oneVYC4o";
                $userAgent = "Purchase code verification on skyforcoding.com";
            
            	// Make sure the code is valid before sending it to Envato
            	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $akcode)) {
            		// throw new Exception("Invalid code");
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();            
            	}
            	
            	// Build the request
            	$ch = curl_init();
            	curl_setopt_array($ch, array(
            		CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$akcode}",
            		CURLOPT_RETURNTRANSFER => true,
            		CURLOPT_TIMEOUT => 20,
            		
            		CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer {$personalToken}",
            			"User-Agent: {$userAgent}"
            		)
            	));
            
            	// Send the request with warnings supressed
            	$response = @curl_exec($ch);
            
            	$body = @json_decode($response);
            
            	if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            		try {
            			throw new Exception("Error parsing response");
            		} catch(Exception $e) {
            			echo $e->getMessage();
            		}
            	}
            
            	if (isset($body->item->name)) {
            
            		$id = $body->item->id;
            		$name = $body->item->name;
            
            		if($id == 23898180) {
            			$qry = "SELECT * FROM tbl_privacy_policy";
                		$result = mysqli_query($connect, $qry);	 
                		$row = mysqli_fetch_assoc($result);
                		  				 
                		$set['result'][] = array(
                			'content' => $row['content'],
                			'success'=>'1'
                		);
                
                		header( 'Content-Type: application/json; charset=utf-8' );
                		$json = json_encode($set);
                
                		echo $json;
                		exit;
            		} else {
            			$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            			echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            			die();
            		}
            	}
            	else {
            		$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            		echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            		die();
            	}
            } 
            else {
            	$set['result'][]=array('msg' => "Invalid Access Key", 'success'=>'0');
            	echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
            	die();
            }
    	}
    	
    }
?>