/* document.observe("dom:loaded", function() {
		$$('pagerJumptoJS').each(
			function (n) {
				n.observe('keyup', pagerJSkeypress);
				
			}
		);
	}
); */

function pagerJSkeyup(event, el, lower, upper) {
	var form = el.up('form');
	var value = pagerParseInt_(el.value);
	if (value < lower) {
		value = lower;
	} else if (value > upper) {
		value = upper;
	}
	el.value = value;
	/* event = event || window.event;
	Event.extend(event);
	if (event.keyCode == Event.KEY_BACKSPACE || event.keyCode == Event.KEY_RETURN) {
		alert('backspace pressed');
	} */
}

/**
 * Alternate parseInt to make sure 001 is parsed as decimal and no NaN is returned.
 *
 * @param string text Text to parse as int.
 * @return int Integer value of the text.
 */
function pagerParseInt_(text) {
	text = text.replace(/^(0+)[1-9]/, '');
	if (text == null || text == '' || isNaN(text)) return 0;
	
	var cleared = parseInt(text);
	if (cleared < 0) return 0;
	return cleared;
}

