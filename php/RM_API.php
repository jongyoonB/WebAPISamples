<?php
/*
Name: ReachMail API Library
Description: Provides a variety of functions to interact with and extend the ReachMail API.
Author: ReachMail Support
Version: 0.1.0
Author URI: http://www.reachmail.net/support
License: MIT License (see LICENSE) http://creativecommons.org/licenses/MIT/
Last Modified: April 5, 2011
Requirements: PHP 5 or higher.
*/

  // ensure Curl extension installed
	if (!extension_loaded("curl")) {
		throw(new Exception("The Curl extension for PHP is required for ReachMail API to work."));
}

/* Define Static Variables */
  // Administration Service
   $base_url = 'https://services.reachmail.net/';
   $get_user_url =  'https://services.reachmail.net/Rest/Administration/v1/users/current';
   $queue_mailing_url = 'https://services.reachmail.net/Rest/Campaigns/v1/';
   $header = array("Content-Type: application/xml");
  // Contact Services
   $enumerate_fields_url = 'https://services.reachmail.net/Rest/Contacts/v1/lists/query/';
   $enumerate_lists_url = 'https://services.reachmail.net/Rest/Contacts/v1/lists/query/';
   $create_list_url = 'https://services.reachmail.net/Rest/Contacts/v1/lists/';
   $get_list_url = 'https://services.reachmail.net/Rest/Contacts/v1/lists/';
   $enumerate_recipients_url = 'https://services.reachmail.net/Rest/Contacts/v1/lists/recipients/query/';
   $get_recipients_url = 'https://services.reachmail.net/Rest/Contacts/v1/lists/recipients';
   $create_recipients_url = 'https://services.reachmail.net/Rest/Contacts/v1/lists/recipients/';
   $import_recipients_url = 'https://services.reachmail.net/Rest/Contacts/v1/lists/import/';
   $export_recipients_url = 'https://services.reachmail.net/Rest/Contacts/v1/lists/export/';
   $get_export_status_url = 'https://services.reachmail.net/Rest/Contacts/v1/lists/export/status/';
  //Mailing Services
   $enumerate_mailings_url = 'https://services.reachmail.net/Rest/Content/Mailings/v1/query/';
   $get_mailing_url = 'https://services.reachmail.net//Rest/Content/Mailings/v1/';
   $delete_mailing_url = 'https://services.reachmail.net//Rest/Content/Mailings/v1/';
   $create_mailing_url = 'https://services.reachmail.net/REST/Content/Mailings/v1/';
  //Report Services
   $enumerate_mailing_reports_url = 'https://services.reachmail.net/Rest/Reports/v1/mailings/query/';
   $get_mailing_report_url = 'https://services.reachmail.net/Rest/Reports/v1/mailings/';
   $get_mailing_report_summary_url = 'https://services.reachmail.net/Rest/Reporting/Content/Mailings/v1/Summary/';
  //Data Services
   $data_upload_url = 'https://services.reachmail.net/Rest/Data/';
   $data_exist_url = 'https://services.reachmail.net/Rest/Data/exists/';
	
	class login{
	           private  $account_key;
		   private  $username;
		   private  $password;
		
		function __construct($account_key = NULL, $username = NULL, $password = NULL) {
					$this->account_key = $account_key;
					$this->username = $username;
					$this->password = $password;		
		}	
		
		function getUser() {
		            global $get_user_url;
		            $account_id_request = curl_init();
                            $curl_options = array(
                                CURLOPT_URL => $get_user_url,
                                CURLOPT_HEADER => false,
                                CURLOPT_USERPWD => "$this->account_key\\$this->username:$this->password",
                                CURLOPT_RETURNTRANSFER => true
                            );
                            curl_setopt_array($account_id_request, $curl_options);
                            $response = curl_exec($account_id_request);
		            $xml = simplexml_load_string($response);
                            $account_id = $xml->AccountId;   
		            print "\n".$account_id."\n\n";					
                           echo $account_id->saveXML("user.xml");
		}
		
		function enumerateLists($account_id, $request_body) {
		
					global $header;
					global $enumerate_lists_url;
					$api_service_url = $enumerate_lists_url.$account_id;		
										
					$enumerate_lists_request = curl_init();
					$curl_options = array(
							CURLOPT_URL => $api_service_url,
							CURLOPT_HEADER => false,
							CURLOPT_USERPWD => "$this->account_key\\$this->username:$this->password",
							CURLOPT_HTTPHEADER => $header,
							CURLOPT_POST => true,
							CURLOPT_POSTFIELDS => $request_body,
							CURLOPT_RETURNTRANSFER => true
							);
					curl_setopt_array($enumerate_lists_request, $curl_options);
					$enumerate_lists_response = curl_exec($enumerate_lists_request);
					curl_close($enumerate_lists_request);
					$list_xml = simplexml_load_string($enumerate_lists_response);
					$list_names = array();
					$list_api_ids = array();
					foreach($list_xml->List as $list){
									$list_names[] = $list->Name;
									$list_api_ids[] = $list->Id;
					}
					$list_count = count($list_api_ids);
					print "\nFormat - List API Id\t:\tList Name\n";
					for($i=0; $i<$list_count; $i++){
							print $list_api_ids[$i]."\t:\t".$list_names[$i]."\n";
					}
					print "\n";
					echo $list_xml->saveXML("lists.xml");
}
}
	
?>