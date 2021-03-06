<?php
namespace Craft;

class ZenDeskFormModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'fromName'          => array(AttributeType::String, 'required' => true, 'label' => 'Your Name'),
			'fromEmail'         => array(AttributeType::Email,  'required' => true, 'label' => 'Your Email'),
			'message'           => array(AttributeType::String, 'required' => true, 'label' => 'Message'),
			'subject'           => array(AttributeType::String, 'required' => true, 'label' => 'Subject')
		);
	}
}
