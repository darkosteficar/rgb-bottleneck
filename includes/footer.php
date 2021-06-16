<footer class="page-footer text-center font-small mdb-color darken-2 mt-4 wow fadeIn">

    <!--Call to action-->
    <div class="pt-4">

    </div>
    <!--/.Call to action-->

    <hr class="my-4">

    <!-- Social icons -->
    <div class="pb-4">
        <a href="https://www.facebook.com/mdbootstrap" target="_blank">
            <i class="fab fa-facebook-f mr-3"></i>
        </a>

        <a href="https://twitter.com/MDBootstrap" target="_blank">
            <i class="fab fa-twitter mr-3"></i>
        </a>

        <a href="https://www.youtube.com/watch?v=7MUISDJ5ZZ4" target="_blank">
            <i class="fab fa-youtube mr-3"></i>
        </a>

        <a href="https://plus.google.com/u/0/b/107863090883699620484" target="_blank">
            <i class="fab fa-google-plus-g mr-3"></i>
        </a>

        <a href="https://dribbble.com/mdbootstrap" target="_blank">
            <i class="fab fa-dribbble mr-3"></i>
        </a>

        <a href="https://pinterest.com/mdbootstrap" target="_blank">
            <i class="fab fa-pinterest mr-3"></i>
        </a>

        <a href="https://github.com/mdbootstrap/bootstrap-material-design" target="_blank">
            <i class="fab fa-github mr-3"></i>
        </a>

        <a href="http://codepen.io/mdbootstrap/" target="_blank">
            <i class="fab fa-codepen mr-3"></i>
        </a>
    </div>
    <!-- Social icons -->

    <!--Copyright-->
    <div class="footer-copyright py-3">
        © 2021 Copyright:
        <a href="https://mdbootstrap.com/education/bootstrap/" target="_blank"> RGB Bottleneck </a>
    </div>
    <!--/.Copyright-->

</footer>
<!--/.Footer-->

<!-- SCRIPTS -->
<!-- JQuery -->
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="js/mdb.min.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>

<!-- Initializations -->
<script type="text/javascript">
    // Animations initialization
    new WOW().init();
</script>
<script>
    $(document).ready(function() {
        $('#commentForm').on('submit', function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: "add_comment.php",
                method: "POST",
                data: formData,
                dataType: "JSON",
                success: function(response) {
                    if (!response.error) {
                        $('#commentForm')[0].reset();
                        //$('#commentId').val('0');
                        $('#message').html(response.message);
                        showComments();
                    } else if (response.error) {
                        $('#message').html(response.message);

                    }
                }
            })

        });

    });

    function showComments() {
        var str = getUrlParameter('id');
        $.ajax({
            url: "show_comments.php",
            method: "POST",
            data: {
                'str': str
            },
            success: function(response) {
                $('#card-body-comments231').html(response);
            },


        })

    }
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };
</script>
</body>

</html>