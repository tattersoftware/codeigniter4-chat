<div class="card chat-conversation">
	<div class="card-header"><?= $conversation->name ?? 'Chat' ?></div>

	<div class="card-body">
		<div id="conversation-<?= $conversation->id ?>" class="card-body chat-messages">

		<?php foreach ($conversation->messages as $message): ?>

		<?= view('Tatter\Chat\Views\message', ['message' => $message]) ?>

		<?php endforeach; ?>

		<?php foreach ($conversation->participants as $participant): ?>

		<?= $participant->username ?>

		<?php endforeach; ?>

		</div>
	</div>

	<div class="card-footer">
		<form action="<?= site_url('chatapi/message') ?>" method="post" onsubmit="sendMessage(); return false;">
			<input name="conversation" type="hidden" value="<?= $conversation->id ?>">

			<div class="input-group d-flex">
				<textarea class="flex-fill" data-autoresize rows="1" placeholder="Type your message..." aria-describedby="button-send"></textarea>

				<div class="input-group-append">
					<button type="button" class="btn btn-outline-secondary" id="button-send">Send</button>
				</div>
			</div>
		</form>
	</div>
</div>
