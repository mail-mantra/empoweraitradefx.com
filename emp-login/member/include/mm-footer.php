<div class="mm-footer2">
  <p>© 2025 <?php include('include/title.php'); ?> | All Rights Reserved.</p>
</div>

<script type="text/javascript" src="../web-assets/js/pushbar.js"></script>
<script type="text/javascript">
  var pushbar = new Pushbar({
    blur: true,
    overlay: true,
  });
</script>

<script>
  $(".mm-sidebar-dropdown > a").click(function() {
    $(".mm-sidebar-submenu").slideUp(200);
    if (
      $(this)
      .parent()
      .hasClass("active")
    ) {
      $(".mm-sidebar-dropdown").removeClass("active");
      $(this)
        .parent()
        .removeClass("active");
    } else {
      $(".mm-sidebar-dropdown").removeClass("active");
      $(this)
        .next(".mm-sidebar-submenu")
        .slideDown(200);
      $(this)
        .parent()
        .addClass("active");
    }
  });

  //$("#close-sidebar").click(function() {
  //  $(".page-wrapper").removeClass("toggled");
  //});
  //$("#show-sidebar").click(function() {
  //  $(".page-wrapper").addClass("toggled");
  //});
</script>
<script>
  $(window).on('load', function() {
    setTimeout(function() { // allowing 3 secs to fade out loader
      $('.page-loader').fadeOut('slow');
    }, 100);
  });
</script>

<script>
    window.cbn_z_index = 10000;
    /*
    window.cbn_placement = {
        from: 'bottom',
        align: 'center'
    };
    */
</script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js'></script>
<script type="text/javascript" src="../web-assets/js/scrollable.js"></script>

<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

<script type="text/javascript" src="//milan-sahana.github.io/bootstrap-notify/bootstrap-notify.js" charset="UTF-8"></script>
<script type="text/javascript" src="//milan-sahana.github.io/bootstrap-notify/custom-notify.js" charset="UTF-8"></script>
<script>
    window.cbn_placement =  {
        from: 'bottom',
        align: 'center'
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!--<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" /></head>-->
<!--<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>-->

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
/>


<script>
    Fancybox.bind("[data-fancybox]", {
// Your custom options
    });
</script>


<script type="text/javascript" src="//afarkas.github.io/lazysizes/lazysizes.min.js"></script>
<script type="text/javascript" src="//afarkas.github.io/lazysizes/plugins/progressive/ls.progressive.min.js"></script>