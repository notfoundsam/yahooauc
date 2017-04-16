<?php if (!empty($result['pages'])): ?>
  <p> Go to page: 
    <?php foreach ($result['pages'] as $page): ?>
    <?php echo Html::anchor('admin/bidding/'.$page, $page);?>
    <?php endforeach ; ?>
  </p>
<?php endif; ?>

<?php if (!empty($result['lots'])): ?>
    
  <?php foreach ($result['lots'] as $item): ?>
    <div class="item-wrapper">
      <div class="bidding-wrap <?= $item['bidder'] != \Config::get('my.yahoo.user_name') ? ' price-up' : ''?>">
        <div class="bidding-img">
        <?php if ($item['images']) : ?>
          <?php foreach ($item['images'] as $img): ?>
            <div><img src="<?= $img; ?>"></div>
          <?php endforeach; ?>
        <?php endif; ?>
        </div>
        
        <div class="bidding-content">
          <div><?= $item['title']; ?></div>
          <div><span>Price:</span> <?= $item['price']; ?></div>
          <div><span>Time left:</span> <?= $item['end']; ?></div>
          <div><span>Vendor:</span> <?= $item['vendor']; ?></div>
          <div><span>Bids:</span> <?= $item['bids']; ?></div>
          <div><span>ID:</span> <?= $item['id']; ?></div>
          <div><span>Current bidder:</span> <?= $item['bidder'] == \Config::get('my.yahoo.user_name') ? 'me' : $item['bidder']; ?></div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

<?php else : ?>
  <h4>Nothing for show...</h4>
<?php endif; ?>
