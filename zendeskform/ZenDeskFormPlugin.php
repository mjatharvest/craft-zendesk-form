<?php
namespace Craft;

class ZenDeskFormPlugin extends BasePlugin
{
	/**
	 * @return mixed
	 */
	public function getName()
	{
		return Craft::t('Zen Desk Form');
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return '0.0.1';
	}

	public function getSchemaVersion()
	{
		return '1.0.0';
	}

	/**
	 * @return string
	 */
	public function getDeveloper()
	{
		return 'Mike Moreau';
	}

	/**
	 * @return string
	 */
	public function getDeveloperUrl()
	{
		return 'http://harvestmedia.com';
	}

	/**
	 * @return string
	 */
	public function getPluginUrl()
	{
		return 'https://github.com/mjatharvest/craft-zendesk-form.git';
	}

	/**
	 * @return string
	 */
	public function getDocumentationUrl()
	{
		return '$this->getPluginUrl().'/blob/master/README.md';
	}

	/**
	 * @return string
	 */
	public function getReleaseFeedUrl()
	{
		return ''; //@todo https://raw.githubusercontent.com/pixelandtonic/ContactForm/master/releases.json
	}

	/**
	 * @return mixed
	 */
	public function getSettingsHtml()
	{
		return craft()->templates->render('zendeskform/_settings', array(
			'settings' => $this->getSettings()
		));
	}

	/**
	 * @param array|BaseModel $values
	 */
	public function setSettings($values)
	{
		if (!$values)
		{
			$values = array();
		}

		if (is_array($values))
		{
			// Merge in any values that are stored in craft/config/zendeskform.php
			foreach ($this->getSettings() as $key => $value)
			{
				$configValue = craft()->config->get($key, 'zendeskform');

				if ($configValue !== null)
				{
					$values[$key] = $configValue;
				}
			}
		}

		parent::setSettings($values);
	}

	/**
	 * @return array
	 */
	protected function defineSettings()
	{
		return array(
			'ZDAPIKEY'              => array(AttributeType::String, 'default' => 'your api key here', 'required' => true),
			'ZDUSER'                => array(AttributeType::String, 'default' => 'your username here', 'required' => true),
			'ZDURL'                 => array(AttributeType::String, 'default' => 'your url here', 'required' => true),
			'honeypotField'         => array(AttributeType::String, 'default' => 'nickname', 'required' => true),
			'successFlashMessage'   => array(AttributeType::String, 'default' => Craft::t('Your message has been sent.'), 'required' => true)
		);
	}
}
