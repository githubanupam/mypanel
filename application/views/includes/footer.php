<!-- Essential javascripts for application to work-->

<script src="<?= base_url() ?>assets/js/popper.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/main.js"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="<?= base_url() ?>assets/js/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->
<script type="text/javascript" src="<?= base_url() ?>assets/js/plugins/chart.js"></script>

<script type="text/javascript" src="<?= base_url() ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.rowGroup.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/plugins/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.19/pagination/input.js" type="text/javascript"></script>


<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet"/>

<link href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" type="text/css" rel="stylesheet"></link>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js" type="text/javascript"></script>-->

<script type="text/javascript">
    $(document).ready(function () {


        var pathname = window.location.href;

        $('a[href="' + pathname + '"]').click(function (event) {

            localStorage.removeItem('activeTab');

        });

        if (pathname === "<?= base_url() ?>dashboard")
        {
            localStorage.removeItem('activeTab');
            $('a[href="' + pathname + '"]').toggleClass('active');
        }

        $("a.treeview-item").click(function (event) {
            localStorage.setItem('activeTab', $(event.target).attr('href'));

        });
        var activeTab = localStorage.getItem('activeTab');
        $('a[href="' + activeTab + '"]').toggleClass('active');
        $('a[href="' + activeTab + '"]').parent().parent().parent().toggleClass('is-expanded');


    });
</script>

</body>
</html>