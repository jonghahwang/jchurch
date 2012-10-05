<section class="title">
    <h2><?php echo $board_name; ?></h2>
</section>

<?php if ($posts): ?>
<section>
    <table class="thin-table" width="700">
        <thead>
        <tr>
            <th width="20"><?php echo lang('board:no'); ?></th>
            <th><?php echo lang('global:title'); ?></th>
            <th><?php echo lang('global:date'); ?></th>
            <th><?php echo lang('global:author'); ?></th>
            <th><?php echo lang('global:view'); ?></th>
            <th width="80"></th>
        </tr>
        </thead>
        <tfoot>
            <tr>
            <td colspan="6">
                <div class="inner"><?php $this->load->view('partials/pagination'); ?></div>
            </td>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo $post->id; ?></td>
                <td><?php echo anchor('board/'.$board_name.'/view/'.$post->slug, $post->title, array('title' => $post->title)); ?></td>
                <td>
                    <?php if (isset($post->display_name)): ?>
						<?php echo anchor('user/' . $post->author_id, $post->display_name, 'target="_blank"'); ?>
					<?php else: ?>
						<?php echo lang('board:author_unknown'); ?>
					<?php endif; ?>
                </td>
                <td><?php echo format_date($post->created_on); ?></td>
                <td class="align-center">48</td>
                <td>&nbsp;</td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
<?php endif; ?>

<?php echo anchor('board/'.$board_name.'/create', lang('board:create_title')); ?>