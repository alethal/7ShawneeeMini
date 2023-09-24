<!-- BEGIN FOOTER -->
</div>
{display_admin_links}

</div>

{literal}
<!-- for main dashboard results -->
<script>
    //$(".sum-bottom").addClass(function(i) { return "statsbox" + (i + 1) })
    //$(".sum-bottom-left").addClass(function(i) { return "statsboxbtmleft" + (i + 1) })
</script>

{/literal}


{literal}
<script>
    //$( document ).ajaxComplete(function( event,request, settings ) {
    //$(".sum-bottom").addClass(function(i) { return "statsbox" + (i + 1) })
    //$(".sum-bottom-left").addClass(function(i) { return "statsboxbtmleft" + (i + 1) })
    //});
</script>

{/literal}

{literal}
<!-- for stastics area results -->



<script>
    //$("#statTable .tab-column").addClass(function(i) { return "thisperiodstatsbox" + (i + 1) })
    //$(".block-inner-columns").addClass(function(i) { return "statspage" + (i + 1) })
    //$(".footer-row .tab-column").addClass(function(i) { return "footercol" + (i + 1) })
</script>

<script>
    $(document).ajaxComplete(function(event, request, settings) {
        $("#statTable td.tab-column").addClass(function(i) {
            return "thisperiodstatsbox" + (i + 1)
        })
        $(".block-inner-columns").addClass(function(i) {
            return "statspage" + (i + 1)
        })
        $(".footer-row .tab-column").addClass(function(i) {
            return "footercol" + (i + 1)
        })
    });
</script>

<script>
    // $(document).ready(function() {
    //$('#statTable').hasClass('tab-column').addClass(function(i) { return "thisperiodstatsbox" + (i + 1) });
    //$("#statTable td.tab-column").addClass(function(i) { return "thisperiodstatsbox" + (i + 1) });
    //$("#statTable td.tab-column").addClass(function(i) { return "thisperiodstatsbox" + (i + 1) })
    // });
</script>


<script>
    $(document).ready(function() {
        $(".AffiliateLinkCodeSettings .block-inner-columns.four-columns").addClass(function(i) {
            return "linkcodecolumn" + (i + 1)
        })
    });
</script>

<script>
    $(document).ready(function() {
        // $("#toolTable .display-link-text").addId(function (i) { return "linkcodecolumn" + (i + 1) })

        //$("#toolTable .display-link-text").attr('id', (function (i) { return "CopyMeValue" + (i + 1) });


        var idToLink = $('#toolTable a.display_full_banner').attr('id');
        $('#toolTable .display-link-text').attr('id', idToLink + 'LinkId');
        //var GetNewIdId = $('#toolTable .display-link-text').attr('id');
        //let CopyThisTextFromId = document.getElementByID(CopyNewIdId).value;

        //console.log(CopyThisTextFromId);
    });
</script>


<script>
    $(document).ready(function() {
        function copyProdcutLink() {
            // Get the text field
            var copyText = document.getElementById("CopyMeValue").value;

            console.log("Copied to clipboard: " + copyText.value);

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);

            // Alert the copied text
            alert("Copied to clipboard: " + copyText.value);
            console.log("Copied to clipboard: " + copyText.value);
        }
    });
</script>


{/literal}


<!-- JS -->
<!--script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.min.js"></script-->
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.easing.1.3.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/bootstrap.bundle.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/SmoothScroll.js"></script>
<!--script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.scrollTo.min.js"></script-->
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.localScroll.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.viewport.mini.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.countTo.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.appear.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.parallax-1.1.3.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.fitvids.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/owl.carousel.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/isotope.pkgd.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/imagesloaded.pkgd.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.magnific-popup.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/masonry.pkgd.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.lazyload.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/wow.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/morphext.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/typed.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/all.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/contact-form.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/jquery.ajaxchimp.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/objectFitPolyfill.min.js"></script>
<script src="https://sellapleasure.com/misc-smp/rhythm_full/js/splitting.min.js"></script>
</body>

</html>