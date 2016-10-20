# craft-zendesk-form
Based on Pixel and Tonic's Contact Form plugin for Craft CMS, this plugin let's you create a basic form to submit a ticket to Zen Desk via their API.

Example Form Code:

	{% macro errorList(errors) %}
			{% if errors %}
				<ul class="errors">
					{% for error in errors %}
						<li>{{ error }}</li>
					{% endfor %}
				</ul>
			{% endif %}
		{% endmacro %}
		
		{% from _self import errorList %}

		<form method="post" action="" accept-charset="UTF-8">
			{# {{ getCsrfInput() }} #}
			
			<input type="hidden" name="action" value="zenDeskForm/sendMessage">
			<input type="hidden" name="redirect" value="thank-you?ticket={ticket}">
			
			<div class="hide">
				<input type="text" name="nickname" value="">
			</div>
			
			<p>
				Your Name:<input type="text" value="{% if message is defined %}{{ message.fromName }}{% endif %}" name="fromName">
				{{ message is defined and message ? errorList(message.getErrors('fromName')) }}
			</p>
			
			<h3><label for="fromEmail">Your Email</label></h3>
			<input id="fromEmail" type="email" name="fromEmail" value="{% if message is defined %}{{ message.fromEmail }}{% endif %}">
			{{ message is defined and message ? errorList(message.getErrors('fromEmail')) }}
			
			<h3><label for="subject">Subject</label></h3>
			<input id="subject" type="text" name="subject" value="{% if message is defined %}{{ message.subject }}{% endif %}">
			{{ message is defined and message ? errorList(message.getErrors('subject')) }}
			
			<h3><label for="message">Message</label></h3>
			<textarea rows="10" cols="40" id="message" name="message">{% if message is defined %}{{ message.message }}{% endif %}</textarea>
			{{ message is defined and message ? errorList(message.getErrors('message')) }}
			
			
			<p>
				<input type="submit" value="submit" id="submitter">
			</p>
		</form>



The example code above passes the created ticket id to the thank you page via:
{{ craft.request.getQuery('ticket') }}

See https://github.com/pixelandtonic/ContactForm
