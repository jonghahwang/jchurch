<div class="post">

	<h3><?php echo $post->title; ?></h3>

	<div class="meta">
		<div class="date"><?php echo lang('board:posted_label');?>: <span><?php echo format_date($post->created_on); ?></span></div>
		
		<?php if (isset($post->display_name)): ?>
		<div class="author">
			<?php echo lang('board:written_by_label'); ?>: 
			<span><?php echo anchor('user/' . $post->author_id, $post->display_name); ?></span>
		</div>
		<?php endif; ?>

		<?php if ($post->category->slug): ?>
		<div class="category">
			<?php echo lang('board:category_label');?>: 
			<span><?php echo anchor('board/category/'.$post->category->slug, $post->category->title);?></span>
		</div>
		<?php endif; ?>
		
		<?php if ($post->keywords): ?>
		<div class="keywords">
			<?php echo lang('board:tagged_label');?>:
			<?php foreach ($post->keywords as $keyword): ?>
				<span><?php echo anchor('board/tagged/'.$keyword->name, $keyword->name, 'class="keyword"') ?></span>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		
		<?php if ($this->current_user->id == $post->author_id): ?>
		    <?php echo anchor('board/'.$board_name.'/edit/'.$post->id, lang("board:edit_post")); ?>
		<?php endif; ?>
	</div>

	<div class="body">
		<?php echo $post->body; ?>
	</div>
	
</div>

<?php if ($post->comments_enabled): ?>
	<?php echo display_comments($post->id); ?>
<?php endif; ?>