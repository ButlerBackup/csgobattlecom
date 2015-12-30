<h1>{L:DISPUTES_TITLE}</h1>

<?php if(count($this->disputes) > 0):  ?>
    <?php foreach($this->disputes as $disput): ?>
        <div>
            <a href="<?php echo url($disput->uid); ?>"><?php echo $disput->uidName; ?></a>
            <a href="<?php echo url('mail'.$disput->uid); ?>">[{L:DISPUTES_SEND_MAIL}]</a>
            <?php if($disput->uwin == '1'): ?>
                {L:DISPUTES_WIN}
            <?php elseif($disput->uwin == '2'): ?>
                {L:DISPUTES_LOSE}
            <?php endif; ?>
            vs.
            <a href="<?php echo url($disput->pid); ?>"><?php echo $disput->pidName; ?></a>
            <a href="<?php echo url('mail'.$disput->pid); ?>">[{L:DISPUTES_SEND_MAIL}]</a>
            <?php if($disput->pwin == '1'): ?>
                {L:DISPUTES_WIN}
            <?php elseif($disput->pwin == '2'): ?>
                {L:DISPUTES_LOSE}
            <?php endif; ?>

            <div>
                <a href="<?php echo url('match'.$disput->id); ?>" target="_blank">{L:DISPUTES_OPEN_MATCH}</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    {L:DISPUTES_NO_DATA}
<?php endif; ?>