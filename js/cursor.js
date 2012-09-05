function cursorPosition(textarea){
//var textarea = document.getElementById(”myTextArea”);
	textarea.focus();

	// get selection in firefox, opera, …

	if (typeof(textarea.selectionStart) == 'number') {
		return textarea.selectionStart;
	} else if (document.selection) {
		var selection_range = document.selection.createRange().duplicate();

		if (selection_range.parentElement() == textarea) { // Check that the selection is actually in our textarea
			// Create three ranges, one containing all the text before the selection,
			// one containing all the text in the selection (this already exists), and one containing all
			// the text after the selection.
			var before_range = document.body.createTextRange();
			before_range.moveToElementText(textarea); // Selects all the text
			before_range.setEndPoint("EndToStart", selection_range); // Moves the end where we need it

			var after_range = document.body.createTextRange();
			after_range.moveToElementText(textarea); // Selects all the text
			after_range.setEndPoint("StartToEnd", selection_range); // Moves the start where we need it

			var before_finished = false, selection_finished = false, after_finished = false;
			var before_text, untrimmed_before_text, selection_text, untrimmed_selection_text, after_text, untrimmed_after_text;

			// Load the text values we need to compare
			before_text = untrimmed_before_text = before_range.text;
			selection_text = untrimmed_selection_text = selection_range.text;
			after_text = untrimmed_after_text = after_range.text;

			// Check each range for trimmed newlines by shrinking the range by 1 character and seeing
			// if the text property has changed. If it has not changed then we know that IE has trimmed
			// a \r\n from the end.
			do {
				if (!before_finished) {
					if (before_range.compareEndPoints("StartToEnd", before_range) == 0) {
						before_finished = true;
					} else {
						before_range.moveEnd("character", -1)
					if (before_range.text == before_text) {
						untrimmed_before_text += "\r\n";
					} else {
						before_finished = true;
					}
				}
			}
			if (!selection_finished) {
				if (selection_range.compareEndPoints("StartToEnd", selection_range) == 0) {
				selection_finished = true;
				} else {
					selection_range.moveEnd("character", -1)
					if (selection_range.text == selection_text) {
						untrimmed_selection_text += "\r\n";
					} else {
						selection_finished = true;
					}
				}
			}
			if (!after_finished) {
				if (after_range.compareEndPoints("StartToEnd", after_range) == 0) {
				after_finished = true;
				} else {
					after_range.moveEnd("character", -1)
					if (after_range.text == after_text) {
						untrimmed_after_text += "\r\n";
					} else {
						after_finished = true;
					}
				}
			}

			} while ((!before_finished || !selection_finished || !after_finished));

			// Untrimmed success test to make sure our results match what is actually in the textarea
			// This can be removed once you’re confident it’s working correctly
			var untrimmed_text = untrimmed_before_text + untrimmed_selection_text + untrimmed_after_text;
			var untrimmed_successful = false;
			if (textarea.value == untrimmed_text) {
				untrimmed_successful = true;
			}
			// ** END Untrimmed success test

			var startPoint = untrimmed_before_text.length;
			//alert(startPoint);
			return startPoint;
		}
	}
}