function validateForms()
{
	var elementsForms;
	
	if (!document.getElementsByTagName) return false;
	elementsForms = document.getElementsByTagName("form"); 
	for (var intCounter = 0; intCounter < elementsForms.length; intCounter++) 
	{ 
		return validateForm(elementsForms[intCounter]);
	} 
}
	
function validateForm(currentForm)
{
	var encryption = true;
	var elementsInputs;
	
 	elementsInputs = currentForm.getElementsByTagName("input");
 
	for (var intCounter = 0; intCounter < elementsInputs.length; intCounter++)
	{
		if (elementsInputs[intCounter].className != "n")
		{
			if (!encrypt(elementsInputs, intCounter))
			{
				encryption = false;
				
			}
		}
		else if (elementsInputs[intCounter].name == "c")
		{
			elementsInputs[intCounter].value = "1";
		}
	}
	return encryption;
}

function encrypt(elementsInputs, intCounter, strErrorMessage)
{
	//var result = elementsInputs[intCounter].value;
	var result = scramble (elementsInputs[intCounter].value);
	elementsInputs[intCounter].value = result;
	return true;
}
function applyOnSubmitToForms()
{
	elementsForms = document.getElementsByTagName("form"); 
	for (var intCounter = 0; intCounter < elementsForms.length; intCounter++) 
	{ 
		elementsForms[intCounter].onsubmit = function ()
		{
			if (!validateForms())
			{
				return false;
			}
		}
	} 
}		
function addLoadEvent(func) 
{
 	var oldonload = window.onload;
	if (typeof window.onload != 'function') 
	{
		window.onload = func;
	} 
	else 
	{
		window.onload = function() 
		{
	      		oldonload();
	      		func();
	    	}
	}
}

addLoadEvent(applyOnSubmitToForms);
