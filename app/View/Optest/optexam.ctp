<script>
    jQuery(document).ready(function(){
        timeLimit = jQuery('.timer').prop('id');
        timeLimit = timeLimit.split(':');
        timeHours = timeLimit[0];
        if (timeLimit[1] == 0) {
            timeHours = timeLimit[0]-1 ;
        }
        timeSeconds = timeLimit[2];
        timeMinute  = ((timeLimit[1]*10)-1);
        if (timeMinute < 0) {
            timeMinute = 59;
        }
        jQuery('.timer').html(timeLimit[0]+':'+timeMinute+':'+timeSeconds);
        secondBefore = 0;
        minuteBefore = 0;
        setInterval(function(){
            if ( (timeSeconds > 0 && timeMinute > -1 && timeHours> -1) && (timeSeconds + timeMinute + timeHours != 0) ) {
                timeSeconds = timeSeconds - 1;
                if (timeMinute<10 && parseInt(minuteBefore) != parseInt(timeMinute) ) {
                    timeMinute = '0'+timeMinute;
                    minuteBefore = timeMinute;
                }
                if (timeSeconds<10 && parseInt(timeSeconds) != parseInt(secondBefore)) {
                    timeSeconds = '0'+timeSeconds;
                    secondBefore = timeSeconds;
                }
                jQuery('.timer').html('0'+timeHours+':'+timeMinute+':'+timeSeconds);
            }else if( (timeSeconds == 0 && timeMinute > -1 && timeHours > -1) && (timeSeconds + timeMinute + timeHours != 0))
            {
                timeSeconds = 59;
                timeMinute = timeMinute-1;
                if (timeMinute == -1) {
                    timeMinute = 59;
                    timeHours = timeHours -1;
                    if (timeHours == -1) {
                        timeSeconds = -2
                        jQuery('.timer').html('examover');
                    }
                }
                if (timeMinute<10 && parseInt(minuteBefore) != parseInt(timeMinute) ) {
                    timeMinute = '0'+timeMinute;
                    minuteBefore = timeMinute;
                }
                if (timeSeconds<10 && parseInt(timeSeconds) != parseInt(secondBefore)) {
                    timeSeconds = '0'+timeSeconds;
                    secondBefore = timeSeconds;
                }
                jQuery('.timer').html('0'+timeHours+':'+timeMinute+':'+timeSeconds);
            }
            else if ((timeSeconds + timeMinute + timeHours == 0)) {
                        timeSeconds = -2;
                        jQuery('.timer').html('examover');
                        stop();
            }
            /*update timer*/
            jQuery.ajax({
                    type: "post",
                    url: "/optest/users/updatetimer",
                    data: {
                        ht: timeHours,
                        mt: timeMinute,
                        st: timeSeconds
                    },
                    success: function(result) {
                        console.clear();
                    }
                });
            /*end ajax*/
            if (timeHours == 0 && timeMinute == 4) {
                jQuery('.timer').addClass('time-small');
            }
        },1000);
    });
</script>
<div class="time-box">
    <div> Time Left</div>
    <div id="<?php echo $time_limit[0].':'.$time_limit[1].':'.$time_limit[2]; ?>" class="timer">
    </div>
</div>
<?php
foreach($paper as $value)
{
    ?>
    <div id="q-<?php echo $value['FinalPaper']['final_id']; ?>" class="<?php echo $value['FinalPaper']['question_for']; ?> english"><?php echo $value['FinalPaper']['e_sub_question']; ?></div>
    <input type="radio" name="<?php echo $value['FinalPaper']['final_id']; ?>" value="<?php echo $value['FinalPaper']['e_a']; ?>"><label><?php echo $value['FinalPaper']['e_a']; ?></label>
    <input type="radio" name="<?php echo $value['FinalPaper']['final_id']; ?>" value="<?php echo $value['FinalPaper']['e_b']; ?>"><label><?php echo $value['FinalPaper']['e_b']; ?></label>
    <input type="radio" name="<?php echo $value['FinalPaper']['final_id']; ?>" value="<?php echo $value['FinalPaper']['e_c']; ?>"><label><?php echo $value['FinalPaper']['e_c']; ?></label>
    <input type="radio" name="<?php echo $value['FinalPaper']['final_id']; ?>" value="<?php echo $value['FinalPaper']['e_d']; ?>"><label><?php echo $value['FinalPaper']['e_d']; ?></label>
    <input type="radio" name="<?php echo $value['FinalPaper']['final_id']; ?>" value="<?php echo $value['FinalPaper']['e_e']; ?>"><label><?php echo $value['FinalPaper']['e_e']; ?></label>
    <br />
    <button>Mark for Review & Next</button>
    <button>Clear Response</button>
    <button>Save & Next</button>
    <?php
}