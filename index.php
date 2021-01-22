<?php
require_once __DIR__.'/lib/vendor/autoload.php';

use \Symfony\Component\Form\Forms;
use \Symfony\Component\Form\AbstractTypeExtension;
use \Symfony\Component\Form\Extension\Core\Type as T;



//Parse command-line options: "set" and "required"
$optionsData = getopt('', ['set:', 'required:']);
$setMode     = $optionsData['set'] ?? false;
$isRequired  = (isset($optionsData['required']) and $optionsData['required'] == 'true');
$isExt       = ($setMode == 'ext' and !$isRequired);
$isForm      = ($setMode == 'form');

require __DIR__.'/show_actions.php';



class NotRequiredExtension extends AbstractTypeExtension {
	public static function getExtendedTypes (): iterable {
		return [T\FormType::class];
	}

	public function configureOptions ($resolver) {
		$resolver->setDefault('required', false);
	}
}



$formFactoryBuilder = Forms::createFormFactoryBuilder();

if ($isExt) {
	$formFactoryBuilder->addTypeExtension(new \NotRequiredExtension());
}

$builderParams = [
	T\FormType::class,
	['inherit'=>'', 'required_true'=>'', 'required_false'=>''],
];
if ($isForm) {
	$builderParams[] = ['required' => $isRequired];
}

$formView = $formFactoryBuilder
	->getFormFactory()
	->createBuilder(...$builderParams)
	->add('inherit',        T\TextType::class)
	->add('required_true',  T\TextType::class, ['required'=>true])
	->add('required_false', T\TextType::class, ['required'=>false])
	->getForm()
	->createView()
;

//Show fields "required" value - whether it's "true" or "false"
foreach (['inherit', 'required_true', 'required_false'] as $fieldName) {
	echo
		str_pad("$fieldName:", 16) .
		($formView->children[$fieldName]->vars['required'] ? 'true' : 'false') .
		"\n"
	;
}
