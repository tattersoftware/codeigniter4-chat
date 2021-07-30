// Initialize Bootstrap tooltips
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})

// Auto-resize the textarea
// Code from Stephan Wagner; https://stephanwagner.me/auto-resizing-textarea-with-vanilla-javascript
document.addEventListener("DOMContentLoaded", function() {
	document.querySelectorAll('[data-autoresize]').forEach(function (element) {

		element.style.boxSizing = 'border-box';
		var offset = element.offsetHeight - element.clientHeight;

		document.addEventListener('input', function (event) {
			event.target.style.height = 'auto';
			event.target.style.height = event.target.scrollHeight + offset + 'px';
		});

		element.addEventListener("keypress", submitOnEnter);

		element.removeAttribute('data-autoresize');
	});
});

// Code from Dimitar Nestorov; https://stackoverflow.com/questions/8934088/how-to-make-enter-key-in-a-textarea-submit-a-form
function submitOnEnter(event) {
	if (event.which === 13 && ! event.shiftKey) {
			event.target.form.dispatchEvent(new Event("submit", {cancelable: true}));
			event.preventDefault(); // Prevents the addition of a new line in the text field (not needed in a lot of cases)
	}
}

// Scroll chat message wrappers to the bottom
$(".card-body").scrollTop(10000);

// Handle submitting messages via AJAX
function sendMessage(formElement) {
	const url = siteUrl + '/chatapi/messages';
	const data = new URLSearchParams(new FormData(formElement));

	fetch(url, {
		method: 'post',
		body: data,
		headers: { "X-Requested-With": "XMLHttpRequest" }
	})
	.then((response) => {
		if (response.status == 201) {
			// Reset the input
			formElement.content.value = '';

			// Add the message to the display
			return response.text();
		} else {
			return '<div class="chat-row text-muted"><p><em>Unable to send your message</em></p></div>';
		}
	})
	.then((text) => {
		// Add the pre-formatted message to the display
		var target = "conversation-" + formElement.conversation.value;
		document.getElementById(target).insertAdjacentHTML('beforeend', text);

		// Scroll the display
		$(".card-body").scrollTop(10000);
	});
}
