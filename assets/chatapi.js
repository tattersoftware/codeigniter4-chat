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
    element.removeAttribute('data-autoresize');
  });
});

// Scroll chat message wrappers to the bottom
$(".card-body").scrollTop(10000);

function sendMessage(formElement) {
	const url = siteUrl + 'chatapi/messages';
	const data = new URLSearchParams(new FormData(formElement));

	fetch(url, {
		method: 'post',
		body: data,
	})
	.then((response) => {
		if (response.status == 201) {
			location.reload();
		}
	});
}
