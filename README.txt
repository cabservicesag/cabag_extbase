This extension can be thought of as a central library for extbase extenions and provides the following:
-------------------------------------------------------------------------------------------------------

Alternative API-Classes:
------------------------
Tx_CabagExtbase_Persistence_Repository
	Make your repositories extend from this class to add setQuerySettings() and getQuerySettings() functions.
	Using setQuerySettings() you can set the query settings on a per repository basis.
	!! This may slow down your queries because for each new query the caller object has to be retrieved !!

Tx_CabagExtbase_MVC_Controller_ActionController
    Use this ActionController which enables mapping from different get/post vars to each other
    Typoscript Example:
        settings {
            GPMapping {
                mainSearchWord {
                    from = tx_idcsupport_supportsearchmain|sword
                    to = tx_idcsupport_supportsearch|sword
                }
                searchWord {
                    from = tx_idcsupport_supportsearch|sword
                    to = tx_idcsupport_supportsearchmain|sword
                }
            }
        }

Tx_CabagExtBase_Persistence_Storage_CabagTypo3DbBackend
	This Db Backend allows you to sort over multiples fields when using concat. The syntax has to be like "concat(field1,field2,field3) as fieldxy. To use this, modify config.extbase like this:
	config.tx_extbase {
		objects {
			Tx_Extbase_Persistence_Storage_BackendInterface {
				className = Tx_CabagExtbase_Persistence_Storage_CabagTypo3DbBackend
			}
		}
	}

Tx_CabagExtbase_Controller_BaseController
	This alternative to Tx_Extbase_MVC_Controller_ActionController adds some extra functions to a controller.
	Currently these are:
		->translate($key, $arguments = null)
			Automatically adds the extension key to the localization request.
		
		->ensureLogin($loginPage, $logout = false)
			Makes sure that the user is logged in, if $logout is set, the user will be logged out first.
		
		->callActionMethod()
			Allows an alternate action to be called when an exception is thrown inside the action.
			The action is defined by the typoscript key plugin.tx_myext.settings.exceptionAction.
			This action must return any content to be displayed directly.
			By default this feature is off.
				- plugin.tx_myext.settings.exceptionAction = 1
					-> errorAction() is called
				- plugin.tx_myext.settings.exceptionAction = error
					-> errorAction() is called
				- plugin.tx_myext.settings.exceptionAction = errorAction
					-> errorAction() is called
				- plugin.tx_myext.settings.exceptionAction = myFancyErrorHandler
					-> myFancyErrorHandlerAction() is called
			Also allows to add the exceptions the flash messages.
				- plugin.tx_myext.settings.addExceptionToFlashMessage
					-> adds the Exception message as a flash message.
				- plugin.tx_myext.settings.addStackTraceToFlashMessage
					-> adds the stack trace as a flash message (only thought to be used during development).
			When an exception is caught this way, it will be written into $this->lastException.




Extbase domain model and repository:
------------------------------------
- static_country table
	- Tx_CabagExtbase_Domain_Model_StaticCountry
	- Tx_CabagExtbase_Domain_Repository_StaticCountryRepository
	!! You must include the extension TypoScript in order for this to work !!

ViewHelpers:
------------
- Tx_CabagExtbase_ViewHelpers_CdataViewHelper
	Renders a <![CDATA[...]]> around the content, as cdata tags are removed by the fluid engine.
	{namespace c=Tx_CabagExtbase_ViewHelpers}
	<c:cdata>This will be a HTML-comment and wrapped with a CDATA tag.</c:cdata>

- Tx_CabagExtbase_ViewHelpers_RawViewHelper
	Renders the content of the 'raw' attribute without letting fluid apply htmlspecialchars.
	{namespace c=Tx_CabagExtbase_ViewHelpers}
	<c:raw raw="{someVariableThatContainsHTMLTags}" />
	{c:raw(raw: someVariableThatContainsHTMLTags)}

- Tx_CabagExtbase_ViewHelpers_IfErrorViewHelper
	Renders the content if a form error occurred for the given property.
	{namespace c=Tx_CabagExtbase_ViewHelpers}
	<c:ifError for="myObject.myProperty">
		<div class="errorMessage">This is an error message!</div>
	</c:ifError>

- Tx_CabagExtbase_ViewHelpers_JsonViewHelper
	Renders the content into a json array.
	{namespace c=Tx_CabagExtbase_ViewHelpers}
	<c:json value="{some: 'array'}" />

- Tx_CabagExtbase_ViewHelpers_IfContainsViewHelper
	Renders content if value (or one value in array) is found in another value (string,array,object)
	{namespace c=Tx_CabagExtbase_ViewHelpers}
	<c:ifContains value="objectOrArrayOrString" in="objectOrArrayOrString">
		<div class="doSomething">Do something if found.</div>
	</c:ifContains>

- Tx_CabagExtbase_ViewHelpers_StrftimeViewHelper
	Renders a DateTime object (or constructor) using strftime formatting.
	{namespace c=Tx_CabagExtbase_ViewHelpers}
	<c:strftime date="@1234567890" format="%d.%m.%Y" />

- Tx_CabagExtbase_ViewHelpers_ObjectAccessorViewHelper
	Allows to execute a string path on a given object.
	{namespace c=Tx_CabagExtbase_ViewHelpers}
	<c:objectAccessor subject="{someModelObject}" path="field.subfield.someTextField" />
	c:objectAccessor(subject: someModelObject, path: 'field.subfield.someTextField')
	-> this returns the same as {someModelObject.field.subfield.someTextField}, but the path can be retrieved from a dynamic variable

- Tx_CabagExtbase_ViewHelpers_Date2CalViewHelper
	Renders a date2cal javascript date picker.
	<c:date2Cal id="wishedEndDateTime" prefix="tx_jamesservices" name="tx_jamesservices_pi1[newOrder][singleorders][{service.uid}][wishedEndDateTime]" />

- Tx_CabagExtbase_ViewHelpers_ImplodeViewHelper
	Implodes a given array.
	<c:implode value="{0: 'some', 1: 'text'}" glue=", " /> -> some, text
	<c:implode value="{0: 'b', 1: 'c', 2: 'a'}" glue="|" sort="1" /> -> a|b|c
	<c:implode value="{3: 'b', 1: 'c', 0: 'a'}" glue=":" sort="numeric" reverse="1" sortByKeys="1" /> -> b:c:a

- Tx_CabagExtbase_ViewHelpers_PregReplaceViewHelper
	Replaces text in the content with a regex.
	<c:pregReplace from="/<[^>]*>([^<]*)<\/[^>]>/" to="$1">...</c:pregReplace>
	
- Tx_CabagExtbase_ViewHelpers_EscapeViewHelper
	Allows certain html entities to pass through. To use it instead of the default escape viewhelper from the intercepter (for text nodes for example), add the following to the typoscript:
	config.tx_extbase {
		objects {
			Tx_Fluid_ViewHelpers_EscapeViewHelper {
				className = Tx_CabagExtbase_ViewHelpers_EscapeViewHelper
			}
		}
	}

- Tx_CabagExtbase_ViewHelpers_FormViewHelper
	Adds several options to the default form viewhelper
	<c:form method="GET"... dontRenderHmac="1" dontRenderReferrer="1"...></c:form>
	- renderArgumentsAsHidden takes the GET parameters of the action and renders them as hidden fields at the top of the form
	<c:form method="POST"... renderArgumentsAsHidden="1"...></c:form>

- Tx_CabagExtbase_ViewHelpers_DebugViewHelper
	Does and works the same as <f:debug>{bla}</f:debug> but instead also works the same in a backend module.

- Tx_CabagExtbase_ViewHelpers_SelectViewHelper
	Adds several options to the default select viewhelper
	<c:select property="topic" options="{topics}" optionLabelField="title" emptyOption="{f:translate(key: 'form.topics.empty')}" emptyOptionValue="0" />

Validators:
-----------
- Tx_CabagExtbase_Validator_TwiceValidator
	Allows to make sure two properties are equal.
	/**
	 * The E-Mail adress.
	 *
	 * @var string
	 * @validate NotEmpty
	 * @validate EmailAddress
	 * @validate Tx_CabagExtbase_Validator_TwiceValidator(key="email")
	 */
	protected $email;

	/**
	 * The E-Mail adress again.
	 *
	 * @var string
	 * @validate NotEmpty
	 * @validate EmailAddress
	 * @validate Tx_CabagExtbase_Validator_TwiceValidator(key="email")
	 */
	protected $emailAgain;

- Tx_CabagExtbase_Validator_BoundNotEmptyValidator
	Allows to make sure at least X of many fields is not empty.
	/**
	 * Phone 1
	 *
	 * @var string
	 * @validate Tx_CabagExtbase_Validator_BoundNotEmptyValidator(key="email", total="2", minimum="1")
	 */
	protected $phone1;

	/**
	 * Phone 2
	 *
	 * @var string
	 * @validate Tx_CabagExtbase_Validator_BoundNotEmptyValidator(key="email", total="2", minimum="1")
	 */
	protected $phone2;

Utility:
--------
- Tx_CabagExtbase_Utility_File
	Simplifies file upload with extbase.
	$file = t3lib_div::makeInstance('Tx_CabagExtbase_Utility_File', 'uploads/tx_myext/');
	$uploadedFile = $file->moveUploadedFile($_FILES['tx_myext_pi1'], array('theObject', 'theSubObject', 'theFileProperty'), '(xls)|(xlsx)');

- Tx_CabagExtbase_Utility_Mail
	Allows to generate mails directly with a fluid template.
	As of version 0.4.9 the controller context is optional and null can be passed instead.
	$customerMail = t3lib_div::makeInstance(
		'Tx_CabagExtbase_Utility_Mail',
		$this->controllerContext,
		t3lib_div::getFileAbsFileName($this->settings['customerMailTemplate']),
		$this->settings
	);
	
	$customerMail->assign('someVariable', $someVariable);
	
	$customerMail->sendPlainTextMail(
		$user->getEmail(),
		$this->translate('mail.customer.subject'),
		$this->settings['fromMail'],
		$this->translate('mail.fromName')
	);
	
	# instead like this a HTML-Mail is sent:
	
	$customerMail->sendMail(
		$user->getEmail(),
		$this->translate('mail.customer.subject'),
		$this->settings['fromMail'],
		$this->translate('mail.fromName'),
		true
	);
	
	# if the following is called first, the swiftmailer engine is used instead to send the mail
	# it must be called before the ->sendMail() call obviously
	
	$customerMail->setUseSwiftmailer(true);
	
	# to add attachements you can use the following
	# this only works when using html mail and/or swiftmailer engine
	
	$customerMail->addAttachment('uploads/tx_cabagextbase/someFile.pdf');

- Tx_CabagExtbase_Utility_Logging
	Allows to employ complex logging structures with multiple outputs at the same time.
	The severity corresponds to the HTTP return codes:
		100 -> additional information (debug)
		200 -> success message
		300 -> warnings/important messages
		400 -> errors
		500 -> serious stuff went wrong!
	
	The following the default options and a few example output's.
	The first constructor parameter is the default tag and the second is the minimum severity for an automatic write.
	$logging = $this->objectManager->create('Tx_CabagExtbase_Utility_Logging', 'tx_myextension', 400);
	// alternate non extbase:
	$logging = t3lib_div::makeInstance('Tx_CabagExtbase_Utility_Logging', 'tx_myextension', 400);
	$logging->addOutput('myExtFile', 'file', array(
		'file' => 'fileadmin/log/%Y%m%d-my.log', // strftime enabled, relative to PATH_site or /absolute/path/bla.log
		'severity' => 200 // minimum severity to be written
	));
	$logging->addOutput('myExtMail', 'mail', array(
		'emails' => 'some@example.com,other@example.com', // comma separated list of emails
		'subject' => '[STAGING] LOG', // the subject for the mail
		'from' => 'info@example.com', // the mail to send from
		'fromName' => 'Staging Log', // the name to send from (optional)
		'plain' => true, // send as plain text mail
		'template' => 'EXT:my_ext/Resources/Private/Mail/Logging.html', // template file (optional)
		'severity' => 400 // just send errors
	));
	$logging->addOutput('devlog');
	$logging->addOutput('flash', array(
		'flashMessageContainer' => $this->flashMessageContainer,
		'severity' => 200
	));
	
	$logging->log('Starting log run');
	$logging->log('An error occured!', Tx_CabagExtbase_Utility_Logging::ERROR, array(
		'some' => 'additional',
		'data' => 'provided'
	), 'tx_otherextension');
	
	$logging->write(); // may not do anything at all, if no entries are currently available (e.g. if an autowrite occured)

- Tx_CabagExtbase_Utility_AbstractWizicon
	Allows easy creation of wizicons for plugins. You must create a class that extends it and add it to ext_tables:
	// add wizicons
	if (TYPO3_MODE=='BE') {
		$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_MyExt_Utility_Wizicon_Plugins'] = t3lib_extMgm::extPath($_EXTKEY).'Classes/Utility/Wizicon/Plugins.php';
	}
	
	By default, the class resolves (overwrite the respective functions to change them):
		- Translations from Resources/Private/Language/locallang_db.xml
			- The plugin title from the key plugin.myplugin
			- The plugin description from the key plugin.myplugin.description
		- The icon from Resources/Public/Icons/Wizicon_myplugin.gif
	
	The class must look something like the following.
	class tx_MyExt_Utility_Wizicon_Plugins extends tx_CabagExtbase_Utility_AbstractWizicon {
		/**
		 * Must return the extension key that the wizicons will be generated for.
		 */
		function getExtensionKey() {
			return 'my_ext';
		}
		
		/**
		 * Adds the plugin wizard icon
		 *
		 * @param array Input array with wizard items for plugins
		 * @return array Modified input array, having the additional items.
		 */
		public function proc($wizardItems) {
			$wizardItems['plugins_tx_myext_' . $plugin] = $this->getWizard('myplugin');
			
			return $wizardItems;
		}
	}
