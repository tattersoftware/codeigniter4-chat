<div class="card chat-conversation h-100">
	<div class="card-header"><?= $conversation->name ?? 'Chat' ?></div>

	<div class="card-body overflow-auto" style="max-height: 300px;">
		<div id="conversation-<?= $conversation->id ?>" class="chat-messages">

			<?php foreach ($conversation->messages as $message): ?>

			<?php if ($message->participant->user_id == user_id()): ?>

			<div class="chat-row text-right">
				<span class="chat-message d-inline-block m-1 py-1 px-2 rounded-pill small text-white bg-primary"><?= $message->content ?></span>
				<span class="chat-avatar d-inline-block m-1 p-2 rounded-circle text-uppercase text-white bg-secondary" data-toggle="tooltip" title="<?= $message->participant->username ?>"><?= substr($message->participant->username, 0, 2) ?></span>
			</div>

			<?php else: ?>

			<div class="chat-row">
				<span class="chat-avatar d-inline-block m-1 p-2 rounded-circle text-uppercase text-white bg-secondary" data-toggle="tooltip" title="<?= $message->participant->username ?>"><?= substr($message->participant->username, 0, 2) ?></span>
				<span class="chat-message d-inline-block m-1 py-1 px-2 rounded-pill small text-white bg-secondary"><?= $message->content ?></span>
			</div>
			
			<?php endif; ?>

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
