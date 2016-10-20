<?php
namespace Craft;

/**
 * Zen Desk Form controller
 */
class ZenDeskFormController extends BaseController
{
	/**
	 * @var Allows anonymous access to this controller's actions.
	 * @access protected
	 */
	protected $allowAnonymous = true;


	/**
	 * Sends an email based on the posted params.
	 *
	 * @throws Exception
	 */
	public function actionSendMessage()
	{
		$this->requirePostRequest();
		$savedBody = false;

		$settings = craft()->plugins->getPlugin('zendeskform')->getSettings();

		$message = new ZenDeskFormModel();
		$message['fromEmail'] = strip_tags(craft()->request->getPost('fromEmail'));
		$message['fromName'] = strip_tags(craft()->request->getPost('fromName'));
		$message['subject'] = strip_tags(craft()->request->getPost('subject'));
		$message['message'] = strip_tags(craft()->request->getPost('message'));
		
		if ($message->validate())
		{
			
			// Only actually send this if the honeypot test was valid
			if ($this->validateHoneypot($settings->honeypotField))
			{

				$response = craft()->zenDeskForm->submitTicket($message);
				
				// Make sure we have a ticket ID
				if($response->ticket->id > 0)
				{
				
					if (craft()->request->isAjaxRequest())
					{
						$this->returnJson(array('success' => true));
					}
					else
					{
						// Deprecated. Use 'redirect' instead.
						$successRedirectUrl = craft()->request->getPost('successRedirectUrl');
	
						if ($successRedirectUrl)
						{
							$_POST['redirect'] = $successRedirectUrl;
						}
						
						$data = array(
							'from' => $message['fromName'],
							'ticket' => $response->ticket->id
						);
	
						craft()->userSession->setNotice($settings->successFlashMessage);
						//$this->redirectToPostedUrl($message);
						$this->redirectToPostedUrl($data);
					}
					
				}
				else
				{
					craft()->userSession->setError('There was a problem with your support ticket submission.');
				}
			}
		}


		// Something has gone horribly wrong.
		if (craft()->request->isAjaxRequest())
		{
			return $this->returnErrorJson($message->getErrors());
		}
		else
		{
			craft()->userSession->setError('There was a problem with your submission, please check the form and try again!');

			if ($savedBody !== false)
			{
				$message->message = $savedBody;
			}

			craft()->urlManager->setRouteVariables(array(
				'message' => $message
			));
		}
	}

	/**
	 * Checks that the 'honeypot' field has not been filled out (assuming one has been set).
	 *
	 * @param string $fieldName The honeypot field name.
	 * @return bool
	 */
	protected function validateHoneypot($fieldName)
	{
		if (!$fieldName)
		{
			return true;
		}

		$honey = craft()->request->getPost($fieldName);
		return $honey == '';
	}
}
