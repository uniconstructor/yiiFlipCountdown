<?php

/**
 * Flip countdown widget for Yii Framework
 * 
 * Original plugin:
 * @see https://github.com/xdan/flipcountdown/
 * 
 * @author Smirnov Ilia <frost@easycast.ru>
 */
class FlipCountDown extends CWidget
{
    /**
     * @var array - widget container options
     */
    public $htmlOptions = array();
    /**
     * @var string - countdown target date. Format: "mm/dd/yyyy hh:mm:ss"
     */
    public $beforeDateTime;
    /**
     * @var int - countdown to unix timestamp
     */
    public $beforeUnixTime;
    /**
     * @var string - custom counter value 
     *               Can be used instread of beforeDateTime, if you want a custom non-date counter
     */
    public $value;
    /**
     * @var array - list of original plugin settings
     *              Example:
     *              array(
     *              //  widget size: 'lg', 'md', 'sm', 'xs'
     *              'size'       => 'md', 
     *              // Hide second or minute or hour
     *              'showHour'   => true,
     *              'showMinute' => true,
     *              'showSecond' => true,
     *              // offset timezone
     *              'tzoneOffset' => 3,
     *              // 12 format hours
     *              'am'          => false,
     *              // speed animate flip digit (multiply 6 must by less than 1000) default 60
     *              'speedFlip'   => 30,
     *              // target date and time: "mm/dd/yyyy hh:mm:ss"
     *              'beforeDateTime' => '1/01/2016 00:00:01',
     *              // custom tick function (must return int or JS Date object)
     *              'tick' => 'function(){return i++;}',
     *              );
     */
    public $settings = array();
    /**
     * @var bool - use if widget loaded via AJAX
     *             true:  print init script insread of registering it
     *             false: register init script as usual
     */
    public $isAjaxRequest;
    
    /**
     * @var string - path published to widget assets 
     */
    protected $assets;
    /**
     * @var string - widget init script
     */
    protected $initJs;
    
    /**
     * @see CWidget::init()
     */
    public function init()
    {
        if ( $this->beforeUnixTime )
        {
            $this->settings['beforeDateTime'] = date('m/d/Y h:i:s', $this->beforeUnixTime);
        }
        if ( ! $this->beforeDateTime AND ! isset($this->settings['beforeDateTime']) )
        {
            throw new CException('Missing date and time for countdown widget');
        }
        // configuring defaults
        if ( $this->beforeDateTime )
        {
            $this->settings['beforeDateTime'] = $this->beforeDateTime;
        }
        if ( $this->value )
        {
            $this->settings['tick'] = $this->value;
        }
        if ( ! isset($this->htmlOptions['id']) )
        {
            $this->htmlOptions['id'] = 'flipCountDown_'.$this->getId();
        }
        if ( $this->isAjaxRequest === null )
        {
            $this->isAjaxRequest = Yii::app()->request->isAjaxRequest;
        }
        $defaults = $this->getCounterDefaults();
        $settings = CJSON::encode(CMap::mergeArray($defaults, $this->settings));
        
        $this->assets = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets');
        /* @var $cs CClientScript */
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        // register assets
        $cs->registerCssFile($this->assets.'/jquery.flipcountdown.css');
        $cs->registerScriptFile($this->assets.'/jquery.flipcountdown.js', CClientScript::POS_HEAD);
        $this->initJs = "jQuery('#{$this->htmlOptions['id']}').flipcountdown({$settings})";
        if ( ! $this->isAjaxRequest )
        {
            $cs->registerScript('_initCountDownScript#'.$this->id, $this->initJs, CClientScript::POS_END);
        }
    }
    
    /**
     * @see CWidget::run()
     */
    public function run()
    {
        echo CHtml::tag('div', $this->htmlOptions);
        if ( $this->isAjaxRequest )
        {
            echo '<script>$(document).ready(function() {'.$this->initJs.'}</script>';
        }
    }
    
    /**
     * @return array  widget defaults (overrided by $this->settings)
     */
    protected function getCounterDefaults()
    {
        // get default timezone 
        $timeZone = new DateTimeZone(date_default_timezone_get());
        $dateTime = new DateTime("now", $timeZone);
        $tzOffset = ($timeZone->getOffset($dateTime) / 3600);
        
        return array(
            'size'           => 'md', 
            'showHour'       => true,
            'showMinute'     => true,
            'showSecond'     => true,
            //'tzoneOffset'    => $tzOffset,
            //'am'             => false,
            'beforeDateTime' => '',
        );
    }
}