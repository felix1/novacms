<section class="content-header">
    <h1>Manage Global Blocks</h1>
    <ol class="breadcrumb">
        <li><a href='<?php echo admin_url('dashboard'); ?>'><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Manage Global Blocks</li>
    </ol>
</section>

<section class='content'>

    <?php echo Session::getMessages(); ?>


<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Global Blocks</h3>
    </div>
    <div class="box-body">

    <div class="alert alert-success alert-dismissable">
        <h4>Global Content Blocks</h4>
        <p>These blocks allow you to place extra information. The global_block call goes inside the view or theme layout file.</p>
        <p>The function needs 2 parameters </p>
        <ol>
        <li>A title</li>
        <li>textarea or input</li>
        </ol>
        <p> <code>GlobalBlocks::get('Phone Number', 'input')</code></p>
    </div>

    <form action='<?php echo admin_url('globalblocks/update'); ?>' method='post'>
    <input type='hidden' name='csrfToken' value='<?php echo $csrfToken; ?>'>

    <?php if($blocks): ?>

        <?php 
        $x = 0;
         ?>
        <?php foreach($blocks as $block): ?>

            <div class='panel panel-default'>
            <div class='panel-heading'>
              <h4 class='panel-title'>
                <a data-toggle='collapse' data-parent='#accordion' href='#collapseblock<?php echo $x; ?>'><?php echo e($block->title); ?></a>
              </h4>
            </div>
            <div id='collapseblock<?php echo $x; ?>' class='panel-collapse collapse'>
                <div class='panel-body'>

                    <input type='hidden' name='id[]' value='<?php echo e($block->id); ?>'>
                    <a class='btn btn-xs btn-danger pull-right' href='#' data-toggle='modal' data-target='#confirm_<?php echo e($block->id); ?>'><i class='fa fa-remove'></i> Delete</a>

                    <?php 
                    switch ($block->type) {
                        case 'input':
                            echo "<input type='text' class='form-control' name='content[]' value='$block->content'>";
                            break;
                        case 'textarea':
                            echo "<textarea class='form-control ckeditor' name='content[]'>$block->content</textarea>";
                            break;
                        case 'plaintextarea':
                            echo "<textarea rows='10' class='form-control' name='content[]'>$block->content</textarea>";
                            break;
                    }
                     ?>

                </div>
            </div>
            </div>
            <?php 
            $x++;
             ?>
        <?php endforeach; ?>
    <?php endif; ?>


    <?php if(count($blocks) > 0): ?>
        <p><button type="submit" class="btn btn-success" name="updatepageblocks"><i class="fa fa-check"></i> Update Global Blocks</button></p>
    <?php endif; ?>

    </form>

    </div>
</div>

<?php if($blocks): ?>
    <?php foreach($blocks as $block): ?>

<div class="modal modal-default" id="confirm_<?php echo e($block->id); ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Select Page Block: <?php echo e($block->title); ?></h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this page block?</p>

            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-primary pull-left col-md-3" type="button">Cancel</button>
                <form action="<?php echo e(admin_url('globalblocks/' .$block->id .'/destroy')); ?>" method="POST">
                    <input type="hidden" name="csrfToken" value="<?php echo $csrfToken; ?>" />
                    <input type="submit" name="button" class="btn btn btn-danger pull-right" value="Delete">
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
    <?php endforeach; ?>
<?php endif; ?>

</section>
