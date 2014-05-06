<?php
class BLMVote extends Vote{
    static $has_one = array('BLMoment' => 'BLMoment');
}
