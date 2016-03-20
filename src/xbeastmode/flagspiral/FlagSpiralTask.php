<?php
namespace xbeastmode\flagspiral;
use pocketmine\scheduler\PluginTask;
class FlagSpiralTask extends PluginTask{

    /** @var Loader */
    private $main;

    /**
     * @param Loader $main
     */
    public function __construct(Loader $main){
        parent::__construct($main);
        $this->main = $main;
    }

    /**
     * @param $tick
     */
    public function onRun($tick){
        $this->main->createSpiral();
    }

}
