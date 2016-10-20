<?php
namespace Craft;

/**
 * Zen Desk Form service
 */
class ZenDeskFormService extends BaseApplicationComponent
{

	
	/**
	 * Submits a Zen Desk Form Ticket via API.
	 *
	 * @param array $message
	 * @throws Exception
	 * @return API Response
	 */
	public function submitTicket($message)
	{
		$settings = craft()->plugins->getPlugin('zendeskform')->getSettings();

		define("ZDAPIKEY", $settings->ZDAPIKEY);
		define("ZDUSER", $settings->ZDUSER);
		define("ZDURL", $settings->ZDURL);

		function curlWrap($url, $json)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
			curl_setopt($ch, CURLOPT_URL, ZDURL.$url);
			curl_setopt($ch, CURLOPT_USERPWD, ZDUSER."/token:".ZDAPIKEY);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$output = curl_exec($ch);
			curl_close($ch);
			$decoded = json_decode($output);
			return $decoded;
		}		

		$create = json_encode(array('ticket' => array('subject' => $message->subject, 'comment' => array( "value"=> $message->message), 'requester' => array('name' => $message->fromName, 'email' => $message->fromEmail))));
		$return = curlWrap("/tickets.json", $create);
		return $return;
	}

}
