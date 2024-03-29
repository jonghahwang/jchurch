<section class="title">
    <?php if ($this->controller == 'category' && $this->method === 'edit'): ?>
    <h4><?php echo sprintf(lang('cat_edit_title'), $category->title); ?></h4>
    <?php else: ?>
    <h4><?php echo lang('cat_create_title'); ?></h4>
    <?php endif; ?>
</section>

<section class="item">
<?php echo form_open($this->uri->uri_string(), 'class="crud" id="categories"'); ?>
    <div class="form_inputs">
        <ul>
            <li class="even">
                <label for="title"><?php echo lang('global:title'); ?><span>*</span></label>
                <div class="input"><?php echo form_input('title', $category->title); ?></div>
                <div class="input"><?php echo form_hidden('id', $category->id); ?></div>
            </li>
        </ul>
    </div>    
    <div class="buttons">
        <?php $this->load->view('partials/buttons', array('buttons' => array('save', 'cancel'))); ?>
    </div>
    
<?php echo form_close(); ?>
</section>