<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php
            if (isset($get_title)) {
                echo $get_title;
            }
            ?></title>
    <link rel="icon" type="image/x-icon" href="public/stylesheets/new/assets/img/favicon.ico" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
    <link href="public/stylesheets/new/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="public/stylesheets/new/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="public/stylesheets/new/plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.css" rel="stylesheet" type="text/css" />
    <link href="public/stylesheets/new/plugins/charts/chartist/chartist.css" rel="stylesheet" type="text/css">
    <link href="public/stylesheets/new/assets/css/default-dashboard/style.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <link rel="stylesheet" href="public/stylesheets/style.css">

    <?php
    require_once('condb/include.php');
    $get_title = 'Home';
    require_once('side/header.php');
    require_once('side/footer.php');
    ?>


</head>
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="public/stylesheets/new/assets/js/libs/jquery-3.1.1.min.js"></script>
<script src="public/stylesheets/new/bootstrap/js/popper.min.js"></script>
<script src="public/stylesheets/new/bootstrap/js/bootstrap.min.js"></script>
<script src="public/stylesheets/new/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="public/stylesheets/new/assets/js/app.js"></script>
<script>
    $(document).ready(function() {
        App.init();
    });
</script>
<script src="public/stylesheets/new/assets/js/custom.js"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script src="public/stylesheets/new/plugins/charts/chartist/chartist.js"></script>
<script src="public/stylesheets/new/plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.min.js"></script>
<script src="public/stylesheets/new/plugins/maps/vector/jvector/worldmap_script/jquery-jvectormap-world-mill-en.js"></script>
<script src="public/stylesheets/new/plugins/calendar/pignose/moment.latest.min.js"></script>
<script src="public/stylesheets/new/plugins/calendar/pignose/pignose.calendar.js"></script>
<script src="public/stylesheets/new/plugins/progressbar/progressbar.min.js"></script>
<script src="public/stylesheets/new/assets/js/default-dashboard/default-custom.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->