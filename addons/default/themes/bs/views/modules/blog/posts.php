<?php if (isset($category->title)): ?>
	<h2 id="page_title"><?php echo $category->title; ?></h2>
<?php endif; ?>

<?php if (!empty($blog)): ?>
<?php foreach ($blog as $post): ?>
	<div class="blog_post">
		<!-- Post heading -->
		<div class="post_heading">
			<h2><?php echo  anchor('blog/' .date('Y/m', $post->created_on) .'/'. $post->slug, $post->title); ?></h2>
			<p class="post_date"><small><?php echo lang('blog:posted_label');?>: <?php echo format_date($post->created_on); ?></small></p>
			<?php if($post->category_slug): ?>
			<p class="post_category">
				<?php echo lang('blog:category_label');?>: <?php echo anchor('blog/category/'.$post->category_slug, $post->category_title);?>
			</p>
			<?php endif; ?>
			
		</div>
		<div class="post_body">
			<?php echo $post->intro; ?>
		</div>
        <?php if($post->keywords): ?>
        
			<p class="post_keywords">
				<i class="icon-tags"></i>
				<?php foreach ($post->keywords as $keyword): ?>
					<span><?php echo anchor('blog/tagged/'.$keyword->name, $keyword->name, 'class="keyword"') ?></span>
				<?php endforeach; ?>
			</p>
		<?php endif; ?>
	</div>
<?php endforeach; ?>

<?php echo $pagination['links']; ?>

<?php else: ?>
	<p><?php echo lang('blog_currently_no_posts');?></p>
<?php endif; ?>