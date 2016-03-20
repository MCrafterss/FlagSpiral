<?php
namespace xbeastmode\flagspiral;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
class Loader extends PluginBase{

    /** @var array */
    private $cf;

    public function onEnable(){
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->cf = $this->getConfig()->getAll();
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new FlagSpiralTask($this), 20*$this->cf["time"]);
    }

    /**
     * this logic is by xBeastMode, no code was stolen
     */
    public function createSpiral(){
        $radius = $this->cf["radius"];
        $p = explode(":", $this->cf["position"]);
        $spX = $p[0];
        $spY = $p[1];
        $spZ = $p[2];
        $spLvl = $p[3];
        $p = new Position($spX, $spY, $spZ, $this->getServer()->getLevelByName($spLvl));
        switch(strtolower($this->cf["flag"])){
            case 'mexico':
            case 'italy':
            for($i = 0; $i <= $radius*10; $i += 0.10) {
                $x = $radius * cos($i);
                $z = $radius * sin($i);
                $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 51, 102, 0));//DARK GREEN
                $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 51, 102, 0));//DARK GREEN
                $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 255, 255, 255));//WHITE
                $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 255, 255, 255));//WHITE
                $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 204, 0, 0));//RED
                $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 204, 0, 0));//RED
            }
            break;
            case 'germany':
                for($i = 0; $i <= $radius*10; $i += 0.10) {
                    $x = $radius * cos($i);
                    $z = $radius * sin($i);
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 0, 0, 0));//BLACK
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 0, 0, 0));//BLACK
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 255, 0, 0));//RED
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 255, 0, 0));//RED
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 255, 255, 0));//RED
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 255, 255, 0));//RED
                }
            break;
            case 'russia':
                for($i = 0; $i <= $radius*10; $i += 0.10) {
                    $x = $radius * cos($i);
                    $z = $radius * sin($i);
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 255, 255, 255));//WHITE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 255, 255, 255));//WHITE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 0, 0, 204));//BLUE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 0, 0, 204));//BLUE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 255, 0, 0));//RED
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 255, 0, 0));//RED
                }
            break;
            case 'uk':
            case 'britain':
            case 'france':
                for($i = 0; $i <= $radius*10; $i += 0.10) {
                    $x = $radius * cos($i);
                    $z = $radius * sin($i);
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 0, 0, 204));//BLUE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 0, 0, 204));//BLUE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 255, 255, 255));//WHITE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 255, 255, 255));//WHITE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 204, 0, 0));//RED
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 204, 0, 0));//RED
                }
            break;
            case 'usa':
            case 'america':
                for($i = 0; $i <= $radius*10; $i += 0.10) {
                    $x = $radius * cos($i);
                    $z = $radius * sin($i);
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 0, 0, 204));//BLUE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 0, 0, 204));//BLUE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 204, 0, 0));//RED
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 204, 0, 0));//RED
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 255, 255, 255));//WHITE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 255, 255, 255));//WHITE
                }
            break;
            case 'canada':
                for($i = 0; $i <= $radius*10; $i += 0.10) {
                    $x = $radius * cos($i);
                    $z = $radius * sin($i);
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 204, 0, 0));//RED
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 204, 0, 0));//RED
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 255, 255, 255));//WHITE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 255, 255, 255));//WHITE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 204, 0, 0));//RED
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 204, 0, 0));//RED
                }
            break;
            case 'ireland':
                for($i = 0; $i <= $radius*10; $i += 0.10) {
                    $x = $radius * cos($i);
                    $z = $radius * sin($i);
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 51, 102, 0));//DARK GREEN
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 51, 102, 0));//DARK GREEN
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 255, 255, 255));//WHITE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), 255, 255, 255));//WHITE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 255, 153, 51));//ORANGE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 255, 153, 51));//ORANGE
                }
            break;
            case 'scotland':
                for($i = 0; $i <= $radius*10; $i += 0.10) {
                    $x = $radius * cos($i);
                    $z = $radius * sin($i);
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 0, 0, 204));//BLUE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), 0, 0, 204));//BLUE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 255, 255, 255));//WHITE
                    $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), 255, 255, 255));//WHITE
                }
            break;
        }
    }
}
