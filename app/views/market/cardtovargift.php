 
<link rel="stylesheet" href="/public/js/jq_slider/jquery-ui.css">

<script>
    $(document).ready(function () {
        $('#modal').hide();
        $("#butt img").click(function () {

            $('#modal').slideToggle();
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#new_komm").keyup(function ()
        {
            var box = $(this).val();
            var main = box.length * 100;
            var value = (main / 500);
            var count = 500 - box.length;

            if (box.length <= 145)
            {
                $('#count').html(count);

            }
            else
            {
                alert(' Достигнут предел знаков! ');
            }
            return false;
        });

    });
</script>

<div id="body_center_content_comm">
    <div id="body_center_left_column_comm">


        <div class="site_part">
            <div class="site_part_container">
                <!-- <div class="site_part_h4">КОМИССИОНКА</div>-->
                <?php echo $block_cat_comission; ?>
            </div>


        </div>
        <!-- menu button -->
        <div class="block_menu">
            <a href="/market/comissionka"><img src="/public/images/comiss/kom.png" onmouseover="this.src = '/public/images/comiss/kom_act.png';" onmouseout="this.src = '/public/images/comiss/kom.png';"></a>
            <img src="/public/images/market/rinok.png" onmouseover="this.src = '/public/images/market/rinok_activ.png';" onmouseout="this.src = '/public/images/market/rinok.png';">
            <img src="/public/images/market/bazar.png" onmouseover="this.src = '/public/images/market/bazar_activ.png';" onmouseout="this.src = '/public/images/market/bazar.png';">

        </div>
    </div>
    <!-- ########################################################################################################### -->



    <div id="body_center_tovar">
        <div id="flag"><a href="/market/comissionka"><img src="/public/images/market/flag.png"></a></div>
        <?php echo $block_full_tovar; ?>
    </div>



    <div id="body_center_right_column">

    </div>
    <div class="middle_right_block_adv">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- NewBlock300x250 -->
        <ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px" data-ad-client="ca-pub-7523142643336947" data-ad-slot="6347754113"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>



    <div class="middle_right_block_adv">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- NewBlock300x250 -->
        <ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px" data-ad-client="ca-pub-7523142643336947" data-ad-slot="6347754113"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>

</div>

</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
      function ch(x) {
          $('#small img').click(function () {
              $("#left_block img").replaceWith("<img src='" + x + "' id='bigimgtov' class='imgtov' >")
          });
      }
</script>
