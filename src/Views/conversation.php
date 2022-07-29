<div class="card chat-conversation">
	<div class="card-header"><?= $conversation->name ?? 'Chat' ?></div>

	<div class="card-body overflow-auto" style="max-height: 300px;">
		<div id="conversation-<?= $conversation->id ?>" class="chat-messages">

			<?php foreach ($conversation->messages as $message): ?>

			<?php if (empty($day) || $day !== $message->created_at->format('n/j/Y')): ?>
			<?php $day = $message->created_at->format('n/j/Y'); ?>
			<div class="row">
				<div class="col-5"><hr></div>
				<div class="col-2"><?= $day ?></div>
				<div class="col-5"><hr></div>
			</div>
			<?php endif; ?>

			<?= view('Tatter\Chat\Views\message', ['message' => $message]) ?>

			<?php endforeach; ?>
		</div>
	</div>

	<div class="card-footer">
		<form action="<?= site_url('chatapi/messages') ?>" method="post" onsubmit="sendMessage(this); return false;">
			<input name="conversation" type="hidden" value="<?= $conversation->id ?>">

			<div class="input-group d-flex">
				<textarea
					class="flex-fill"
					name="content"
					data-autoresize
					rows="1"
					placeholder="Type your message..."
					aria-describedby="button-send"
				></textarea>

				<div class="input-group-append">
					<input type="submit" class="btn btn-outline-dark" id="button-send" value="Send">
				</div>
			</div>
		</form>
	</div>

	<style>
		.chat-conversation {
			min-height: 400px;
		}

		.chat-conversation textarea {
			box-sizing: border-box;
			resize: none;
		}

		.chat-message p {
			margin-bottom: 0;
		}
	</style>
</div>
