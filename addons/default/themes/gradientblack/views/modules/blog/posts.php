<?php if (isset($category->title)): ?>
	<h2 id="page_title"><?php echo $category->title; ?></h2>
<?php endif; ?>

<?php if (!empty($blog)): ?>
<?php foreach ($blog as $post): ?>
	<div class="post">
		<!-- Post heading -->
		<div class="post_heading">
			<h2 class="title"><?php echo  anchor('blog/' .date('Y/m', $post->created_on) .'/'. $post->slug, $post->title); ?></h2>
			<p class="meta">
			Posted by <?php echo anchor('user/' . $post->author_id, $post->display_name); ?> 
			on <?php echo format_date($post->created_on); ?> 
			in <?php echo anchor('blog/category/'.$post->category_slug, $post->category_title); ?>
			&nbsp;&bull;&nbsp; <?php echo anchor('blog/' .date('Y/m', $post->created_on) .'/'. $post->slug, count_comments($post->id)); ?> &nbsp;&bull;&nbsp; 
			<?php echo  anchor('blog/' .date('Y/m', $post->created_on) .'/'. $post->slug, "Read more"); ?></p>
		</div>
		<div class="entry">
			<?php echo $post->intro; ?>
		</div>		
					
	</div>
<?php endforeach; ?>

<?php echo $pagination['links']; ?>

<?php else: ?>
	<p><?php echo lang('blog_currently_no_posts');?></p>
<?php endif; ?>