<?php

require_once('simple_html_dom.php');
require_once("header.php");
require_once("session.php");
require_once("db.php");

$keyword = $_POST['search-txt'];
$db = new db();
$mysqli = $db->getConnection();
$mysqli->select_db("medicines");

$sql = "SELECT * FROM available_medicines WHERE medicine_name LIKE '%$keyword%'";
$result = $mysqli->query($sql);

if ($result->num_rows == 0) {
    $session->setSession("search-txt-err", true);
    header('Location:index.php');
    exit();
}

if(isset($_POST['submit'])) {
  $keyword = $_POST['keyword'];

  $epharmacy_url = "https://www.epharmacy.com.np/search?q=".urlencode($keyword);
  $nepmeds_url = "https://www.nepmeds.com.np/search?query=".urlencode($keyword);
  $merohealthcare_url = "https://www.merohealthcare.com/search?product=".urlencode($keyword);

  $curl_epharmacy = curl_init();
  curl_setopt($curl_epharmacy, CURLOPT_URL, $epharmacy_url);
  curl_setopt($curl_epharmacy, CURLOPT_RETURNTRANSFER, true);
  $result_epharmacy = curl_exec($curl_epharmacy);
  curl_close($curl_epharmacy);

  //epharmacy
  $html_epharmacy = str_get_html($result_epharmacy);

  $product_container_epharmacy = $html_epharmacy->find('.product-container.mb-2.col-sm-6.col-lg-3.col-6', 0);
  $card_body_info_epharmacy = $product_container_epharmacy->find('.card-body-info', 0);

  $med_name_epharmacy = $card_body_info_epharmacy->find('.text-dark.m-0', 0)->plaintext;
  $price_epharmacy = $card_body_info_epharmacy->find('.d-md-flex.d-block .price.actual-price.mr-2', 0)->plaintext;
if (!$product_container_epharmacy) {
  $no_results = true;
}

//nepmeds
  $curl_nepmeds = curl_init();
  curl_setopt($curl_nepmeds, CURLOPT_URL, $nepmeds_url);
  curl_setopt($curl_nepmeds, CURLOPT_RETURNTRANSFER, true);
  $result_nepmeds = curl_exec($curl_nepmeds);
  curl_close($curl_nepmeds);

  $html_nepmeds = str_get_html($result_nepmeds);

  $product_inner_wrapper_nepmeds = $html_nepmeds->find('.product-item.col-grid-4', 0)->find('.product-inner-wrapper', 0);
  $product_content_nepmeds = $product_inner_wrapper_nepmeds->find('.product-content', 0)->plaintext;
  $price_nepmeds = $product_inner_wrapper_nepmeds->find('.product-pricing.clear-fix .item-price .fix-price', 0)->plaintext;
if (!$product_inner_wrapper_nepmeds) {
  $no_results = true;
}

//merohealthcare
$merohealthcare_url = "https://www.merohealthcare.com/search?product=".urlencode($keyword);
$curl_merohealthcare = curl_init();
curl_setopt($curl_merohealthcare, CURLOPT_URL, $merohealthcare_url);
curl_setopt($curl_merohealthcare, CURLOPT_RETURNTRANSFER, true);
$result_merohealthcare = curl_exec($curl_merohealthcare);
curl_close($curl_merohealthcare);

$html_merohealthcare = str_get_html($result_merohealthcare);

$featured_product_detail_div = $html_merohealthcare->find('.featured_product_detail_div', 0);
$med_name_merohealthcare = $featured_product_detail_div->find('.featured_product_name', 0)->plaintext;
$price_merohealthcare = $featured_product_detail_div->find('.featured_product_price', 0)->plaintext;
if (!$featured_product_detail_div) {
  $no_results = true;
}
?>
<div class="process-panel">
  <p id="searching-txt">You are searching keyword: <span style="background: #8e9df3;"><?php echo $_POST['search-txt']; ?></span></p>
  <p>Do you want to search again ? <a href="index.php" class="btn btn-lg">Go Back</a></p>
  <span id="search-status-bar" style="height: 300px;">
  </span>
  <img src="loader.gif" id="loader" style="display:block;" />
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th>SN</th>
        <th>Source Website</th>
        <th>Medicine Name</th>
        <th>Price</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $num = 1;
      foreach($urls as $url) { ?>
        <tr>
          <td><?php echo $num; ?></td>
          <td><?php echo $url['site']; ?></td>
          <td><?php echo $url['med_name']; ?></td>
          <td><?php echo $url['price']; ?></td>
           <?php $num++; ?>
    </tr>
  <?php
  }
   ?>
</tbody>
</table>
 <script>
 	var x = document.getElementById("loader");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
</script>
</div>
 <?php
echo "<table>";
echo "<tr><th>SN</th><th>Source Website</th><th>Medicine Name</th><th>Price</th></tr>";
echo "<tr><td>1</td><td><a href='$epharmacy_url'>epharmacy</a></td><td>" . ($med_name_epharmacy ?: "No Result") . "</td><td>" . ($price_epharmacy ?: "No Result") . "</td></tr>";
echo "<tr><td>2</td><td><a href='$nepmeds_url'>nepmeds</a></td><td>" . ($product_content_nepmeds ?: "No Result") . "</td><td>" . ($price_nepmeds ?: "No Result") . "</td></tr>";
echo "<tr><td>3</td><td><a href='$merohealthcare_url'>merohealthcare</a></td><td>" . ($med_name_merohealthcare ?: "No Result") . "</td><td>" . ($price_merohealthcare ?: "No Result") . "</td></tr>";
echo "</table>";
}
?>