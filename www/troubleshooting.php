<!DOCTYPE html>
<html>
<?php
require_once 'common.php';
require_once 'config.php';
?>

<head>
<?php include 'common/menuHead.inc';?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $pageTitle; ?></title>
<style>
.back2top{
        position: fixed;
        bottom: 10px;
        right: 20px;
        z-index: 99;
        background: red;
        color: #fff;
        border-radius: 30px;
        padding: 15px;
        font-weight: bold;
        line-height: normal;
        border: none;
        opacity: 0.5;
    }
</style>
</head>

<body>
<div id="bodyWrapper">
  <?php
$activeParentMenuItem = 'help';
include 'menu.inc';?>
  <div class="mainContainer">

    <h1 class="title">Troubleshooting</h1>
    <div class="pageContent">
      <h2>Troubleshooting Commands</h2>

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <?
//LoadCommands
$troubleshootingCommandsLoaded = 0;
LoadTroubleShootingCommands();
$target_platforms = array('all', $settings['Platform']);

//Display Nav Tabs - one per group
foreach (array_keys($troubleshootingCommandGroups) as $commandGrpID) {
    //Loop through groupings
    //Display group if relevant for current platform
    if (count(array_intersect($troubleshootingCommandGroups[$commandGrpID]["platforms"], $target_platforms)) > 0) {
        echo "<li class=\"nav-item\" role=\"presentation\">";
        echo "<button class=\"nav-link" . ($commandGrpID == array_key_first($troubleshootingCommandGroups) ? " active" : "") . "\" id=\"pills-" . $commandGrpID . "-tab\" data-bs-toggle=\"pill\" data-bs-target=\"#pills-" . $commandGrpID . "\" type=\"button\" role=\"tab\" aria-controls=\"pills-" . $commandGrpID . "\" aria-selected=\"true\">" . $troubleshootingCommandGroups[$commandGrpID]["grpDisplayTitle"] . "</button>";
        echo "</li>";
    }
}
?>
    </ul>


    <div class="tab-content" id="pills-tabContent">
<?
////Display Command Contents
foreach ($troubleshootingCommandGroups as $commandGrpID => $commandGrp) {
    //Loop through groupings
    //Display group if relevant for current platform
    if (count(array_intersect($troubleshootingCommandGroups[$commandGrpID]["platforms"], $target_platforms)) > 0) {
        ${'hotlinks-' . $commandGrpID} = "<ul>";
        echo "<div class=\"tab-pane fade" . ($commandGrpID == array_key_first($troubleshootingCommandGroups) ? " show active" : "") . "\" id=\"pills-" . $commandGrpID . "\" role=\"tabpanel\" aria-labelledby=\"pills-" . $commandGrpID . "-tab\">";
        ?>
    <div id="troubleshooting-grp-<?echo $commandGrpID; ?>" class="backdrop">
    <h4><?echo $commandGrp["grpDescription"]; ?></h4>
    <div id="troubleshooting-hot-links-<?echo $commandGrpID; ?>"> </div>
    </div>
    <hr>
    <div style="overflow: hidden; padding: 10px;\">

    <?
    }
    //Loop through commands in grp
    echo "<div id=\"troubleshooting-results-" . $commandGrpID . "\">";
    foreach ($commandGrp["commands"] as $commandKey => $commandID) {
        //Display command if relevant for current platform
        if (count(array_intersect($commandID["platforms"], $target_platforms)) > 0) {
            $commandTitle = $commandID["title"];
            $commandCmd = $commandID["cmd"];
            $header = "header_" . $commandKey;
            ${'hotlinks-' . $commandGrpID} .= "<li><a href=\"#$header\">$commandTitle</a></li>";
            ?>

        <a class="troubleshoot-anchor" name="<?echo $header ?>">.</a><h4><?echo $commandTitle; ?></h4>
        <h4><?echo "(Command: " . $commandCmd . ")"; ?></h4>
        <pre id="<?echo ("command_" . $commandKey) ?>"><i>Loading...</i></pre>
        <hr>
<?
        }}
    ${'hotlinks-' . $commandGrpID} .= "</ul>";
    ?></div>
    </div></div><?

}

?>
</div>


  </div>
  <?php include 'common/footer.inc';?>
</div>
<button type="button" class="back2top">Back to top</button>

<script type="application/javascript">

/*
 * Anchors are dynamically via ajax thus auto scrolling if anchor is in url
 * will fail.  This will workaround that problem by forcing a scroll
 * afterward dynamic content is loaded.
 */
function fixScroll() {
    // Remove the # from the hash, as different browsers may or may not include it
    var hash = location.hash.replace('#','');

    if(hash != ''){
        var elements = document.getElementsByName(hash);
        if (elements.length > 0) {
            elements[0].scrollIntoView();
        }
   }
}

//$(document).on('shown.bs.tab', function(){
$( document ).ready(function() {
<?
foreach ($troubleshootingCommandGroups as $commandGrpID => $commandGrp) {
    echo ("document.querySelector(\"#troubleshooting-hot-links-" . $commandGrpID . "\").innerHTML ='" . ${'hotlinks-' . $commandGrpID} . "'; \n");

    foreach ($commandGrp["commands"] as $commandKey => $commandID) {
        //Run command if relevant for current platform
        if (count(array_intersect($commandID["platforms"], $target_platforms)) > 0) {
            $url = "./troubleshootingHelper.php?key=" . urlencode($commandKey);
            ?>
      $.ajax({
            url: "<?php echo $url ?>",
            type: 'GET',
            success: function(data) {
                document.querySelector("#<?php echo ("command_" . $commandKey) ?>").innerHTML = data;
                fixScroll();
            },
            error: function() {
                DialogError('Failed to query command', "Error: Unable to query for <?php echo $commandKey ?>");
            }
        });

<?php
}}}
?>
  $(".back2top").click(() => $("html, body").animate({scrollTop: 0}, "slow") && false);
  $(window).scroll(function(){
      if ($(this).scrollTop() > 100) $('.back2top').fadeIn();
      else $('.back2top').fadeOut();
  });
}); // end document ready
</script>

</body>
</html>