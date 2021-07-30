
			<?php if ($message->participant->user_id == user_id()): ?>

			<div class="chat-row text-right">
				<span class="chat-message d-inline-block m-1 py-1 px-2 rounded-lg small text-white bg-primary"><?= $message->content ?></span>
				<span class="chat-avatar d-inline-block m-1 p-2 rounded-circle text-uppercase text-dark bg-light" data-toggle="tooltip" title="<?= $message->participant->name ?: $message->participant->username ?>"><?= substr($message->participant->user, 0, 2) ?></span>
			</div>

			<?php else: ?>

			<div class="chat-row">
				<span class="chat-avatar d-inline-block m-1 p-2 rounded-circle text-uppercase text-dark bg-light" data-toggle="tooltip" title="<?= $message->participant->name ?: $message->participant->username ?>"><?= substr($message->participant->username, 0, 2) ?></span>
				<span class="chat-message d-inline-block m-1 py-1 px-2 rounded-lg small text-white bg-secondary"><?= $message->content ?></span>
			</div>
			
			<?php endif; ?>
