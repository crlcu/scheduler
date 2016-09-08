<?php $presenter = new Appp\Pagination\MaterializePresenter($paginator); ?>

<?php if ($paginator->getLastPage() > 1): ?>
    <ul class="pagination">
        <?php echo $presenter->render(); ?>
    </ul>
<?php endif;?>
