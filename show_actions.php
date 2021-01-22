<?php
if ($isExt) {
	echo "Adding \"NotRequiredExtension\" to the form factory";
} elseif ($isForm) {
	$isRequiredString = ($isRequired ? 'true' : 'false');
	echo "Setting ['required'=>$isRequiredString] to the form options";
} else {
	echo "Not setting \"required\" to the form nor the extension, using a form defaults";
}

echo "\n\n";
