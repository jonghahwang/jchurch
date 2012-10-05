<div class="post">
	<!-- Post heading -->
	<div class="post_heading">
		<h2><a href="#"><?php echo $post->title; ?></a></h2>
	</div>
	<div class="entry">
		<?php echo $post->body; ?>
	</div>
	<p class="meta">
			Posted by <?php echo anchor('user/' . $post->author_id, $post->display_name); ?> 
			on <?php echo format_date($post->created_on); ?> 
			&nbsp;&bull;&nbsp; <?php echo anchor('blog/' .date('Y/m', $post->created_on) .'/'. $post->slug, count_comments($post->id)); ?>		
	</p>
</div>

<div id="comments">
	<?php if ($post->comments_enabled): ?>
		<?php echo display_comments($post->id); ?>
	<?php endif; ?>
</div>