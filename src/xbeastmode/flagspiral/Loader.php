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
        @mkdir($this->getDataFolder()."custom/");
        $this->saveDefaultConfig();
        $this->cf = $this->getConfig()->getAll();
        $this->getServer()->getCommandMap()->register("fs", new FlagSpiralCmd($this));
        $fh = $this->getServer()->getScheduler()->scheduleRepeatingTask($ft = new FlagSpiralTask($this), 20*$this->cf["flag_time"]);
        $cfh = $this->getServer()->getScheduler()->scheduleRepeatingTask($cft = new CustomFlagSpiralTask($this), 20*$this->cf["custom_flag_time"]);
        $ft->setHandler($fh);
        $cft->setHandler($cfh);
    }


    /**
     * @param Position $pos
     * @param $radius
     * @param $name
     * @return bool
     */
    public function saveFlag(Position $pos, $radius, $name){
        if(!isset($this->cf[$name])){
            $pos = [$pos->x, $pos->y, $pos->z, $pos->level->getName()];
            $this->cf[$name] = ["radius" => $radius, "position" => "{$pos[0]}:{$pos[1]}:{$pos[2]}:{$pos[3]}", "flag" => "mexico"];
            $this->getConfig()->setAll($this->cf);
            $this->getConfig()->save();
            return true;
        }
        return false;
    }

    /**
     * @param Position $pos
     * @param $name
     * @return bool
     */
    public function changeFlagPos(Position $pos, $name){
        if(isset($this->cf[$name])){
            $pos = [$pos->x, $pos->y, $pos->z, $pos->level->getName()];
            $this->cf[$name]["position"] = "{$pos[0]}:{$pos[1]}:{$pos[2]}:{$pos[3]}";
            $this->getConfig()->setAll($this->cf);
            $this->getConfig()->save();
            return true;
        }
        return true;
    }

    /**
     * @param $name
     * @return bool
     */
    public function deleteFlag($name){
        if(isset($this->cf[$name])){
            unset($this->cf[$name]);
            $this->getConfig()->setAll($this->cf);
            $this->getConfig()->save();
            return true;
        }
        return false;
    }

    /**
     * @param Position $pos
     * @param $radius
     * @param $name
     * @param $creator
     * @param array $p1
     * @param array $p2
     * @param array $p3
     * @return bool
     */
    public function saveCustomFlag(Position $pos, $radius, $name, $creator, $p1, $p2, $p3){
        if(!file_exists($this->getDataFolder()."custom/$name.dat")) {
            $pos = [$pos->x, $pos->y, $pos->z, $pos->level->getName()];
            $p1 = explode("-", $p1);
            $p2 = explode("-", $p2);
            $p3 = explode("-", $p3);
            $defaults =
                ["name" => $name, "creator" => $creator, "radius" => (int)$radius, "position" => "{$pos[0]}:{$pos[1]}:{$pos[2]}:{$pos[3]}",
                    "r" => [$p1[0], $p1[1], $p1[2]], "g" => [$p2[0], $p2[1], $p2[2]], "b" => [$p3[0], $p3[1], $p3[2]]];
            file_put_contents($this->getDataFolder() . "custom/$name.dat", serialize($defaults));
            return true;
        }
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function deleteCustomFlag($name){
        if(file_exists($this->getDataFolder()."custom/$name.dat")) {
            unlink($this->getDataFolder()."custom/$name.dat");
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getCustomFlagData(){
        $data = [];
        $dir = glob($this->getDataFolder()."custom/*", GLOB_BRACE);
        foreach($dir as $d){
            $data[] = unserialize(file_get_contents($d));
        }
        return $data === null ? null : $data;
    }

    public function spawnCustomFlags(){
        $data = $this->getCustomFlagData();
        if($data !== null) {
            for ($i = 0; $i < count($data); ++$i) {
                $radius = $data[$i]["radius"];
                $pos = $data[$i]["position"];
                $pos = explode(":", $pos);
                $level = $this->getServer()->getLevelByName($pos[3]);
                $r = $data[$i]["r"];
                $g = $data[$i]["g"];
                $b = $data[$i]["b"];
                $this->spawnCustomFlag(new Position($pos[0], $pos[1], $pos[2], $level), $radius, $r, $g, $b);
            }
        }
    }

    /**
     * this logic is by xBeastMode, no code was stolen
     */
    public function spawnFlagSpiral(){
        foreach($this->cf as $name => $c) {
            if ($name === "flag_time" || $name == "custom_flag_time") continue;
            $radius = $this->cf[$name]["radius"];
            $p = explode(":", $this->cf[$name]["position"]);
            $spX = $p[0];
            $spY = $p[1];
            $spZ = $p[2];
            $spLvl = $p[3];
            $p = new Position($spX, $spY, $spZ, $this->getServer()->getLevelByName($spLvl));
            switch (strtolower($this->cf[$name]["flag"])) {
                case 'mexico':
                case 'italy':
                    for ($i = 0; $i <= $radius * 25; $i += 0.10) {
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
                case 'belgium':
                case 'germany':
                    for ($i = 0; $i <= $radius * 25; $i += 0.10) {
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
                    for ($i = 0; $i <= $radius * 25; $i += 0.10) {
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
                    for ($i = 0; $i <= $radius * 25; $i += 0.10) {
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
                    for ($i = 0; $i <= $radius * 25; $i += 0.10) {
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
                    for ($i = 0; $i <= $radius * 25; $i += 0.10) {
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
                    for ($i = 0; $i <= $radius * 25; $i += 0.10) {
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
                    for ($i = 0; $i <= $radius * 25; $i += 0.10) {
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

    /**
     * @param Position $p
     * @param $radius
     * @param array $part1 -> [0] => RED, [1] => GREEN, [2] => BLUE
     * @param array $part2 -> [0] => RED, [1] => GREEN, [2] => BLUE
     * @param array $part3 -> [0] => RED, [1] => GREEN, [2] => BLUE
     */
    public function spawnCustomFlag(Position $p, $radius, array $part1 = [0,0,0], array $part2 = [0,0,0], array $part3 = [0,0,0]){
        for ($i = 0; $i <= $radius * 25; $i += 0.10) {
            $x = $radius * cos($i);
            $z = $radius * sin($i);
            $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), $part1[0], $part1[1], $part1[2]));//FIRST PART OF FLAG
            $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x + $x, $p->y + $i * M_PI, $p->z + $z), $part1[0], $part1[1], $part1[2]));//FIRST PART OF FLAG
            $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), $part2[0], $part2[1], $part2[2]));//SECOND PART OF FLAG
            $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI + 4, $p->z - $z), $part2[0], $part2[1], $part2[2]));//SECOND PART OF FLAG
            $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), $part3[0], $part3[1], $part3[2]));//THIRD PART OF FLAG
            $p->getLevel()->addParticle(new DustParticle(new Vector3($p->x - $x, $p->y + $i * M_PI, $p->z - $z), $part3[0], $part3[1], $part3[2]));//THIRD PART OF FLAG
        }
    }
}
