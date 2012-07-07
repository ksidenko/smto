<div class="eVideoWidget">
    
    <!-- this A tag is where your Flowplayer will be placed. it can be anywhere -->
    <a 
        href="<?php echo $videoUrl; ?>"
        style="display:block;width:<?php echo $width; ?>; height:<?php echo $height; ?>"
        id="<?php echo $this->_uniqid ?>">
    </a>

    <!-- this will install flowplayer inside previous A- tag. -->
    <script type="text/javascript">
        flowplayer("<?php echo $this->_uniqid ?>", "<?php echo $flowplayerSwf ?>", 
        {
            clip : {
                autoPlay: <?php echo $this->autoPlay ?>,
                autoBuffering: true
            },

            // change the default controlbar to tube
            plugins: {
                controls: {
                    url: '<?php echo $videoControls ?>'
                }
            }
        });
    </script>

</div>