<?php
namespace xbeastmode\flagspiral;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\level\Position;
use pocketmine\math\Math;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
class FlagSpiralCmd extends Command implements PluginIdentifiableCommand{

    /** @var Loader */
    private $main;

    /**
     * @param Loader $main
     */
    public function __construct(Loader $main){
        parent::__construct("fs", "create flags out of particles", "/fs help", ["flagspiral"]);
        $this->main = $main;
    }

    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if ($sender instanceof Player and $sender->hasPermission("fs.use")) {
            if (count($args) <= 0) {
                $sender->sendMessage(TextFormat::RED . $this->getUsage());
                return;
            }
            switch (strtolower($args[0])) {
                case 'help':
                    $sender->sendMessage(TextFormat::AQUA . "/fs create <name> <radius> [x] [y] [z]");
                    $sender->sendMessage(TextFormat::AQUA . "/fs tp <name> [x] [y] [z]");
                    $sender->sendMessage(TextFormat::AQUA . "/fs custom <name> <radius> <r-g-b> <r-g-b> <r-g-b> [x] [y] [z]");
                    $sender->sendMessage(TextFormat::AQUA . "/fs delete <flag/custom> <name>");
                    break;
                case 'create':
                    if (!isset($args[1]) or !isset($args[2])){
                        $sender->sendMessage(TextFormat::AQUA . "/fs create <name> <radius> [x] [y] [z]");
                        return;
                    }
                    if(isset($args[3]) and isset($args[4]) and isset($args[5])){
                        $name = $args[1];
                        $radius = $args[2];
                        $x = $args[3];
                        $y = $args[4];
                        $z = $args[5];
                        $level = $sender->getLevel();
                        if($this->main->saveFlag(new Position($x, $y, $z, $level), $radius, $name) === true){
                            $sender->sendMessage(TextFormat::AQUA . "Successfully saved flag!");
                        }else{
                            $sender->sendMessage(TextFormat::RED . "Flag already exists.");
                        }
                    }else{
                        $name = $args[1];
                        $radius = $args[2];
                        $x = Math::floorFloat($sender->x);
                        $y = Math::floorFloat($sender->y);
                        $z = Math::floorFloat($sender->z);
                        $level = $sender->getLevel();
                        $this->main->saveFlag(new Position($x, $y, $z, $level), $radius, $name);
                        if($this->main->saveFlag(new Position($x, $y, $z, $level), $radius, $name) === true){
                            $sender->sendMessage(TextFormat::AQUA . "Successfully saved flag!");
                        }else{
                            $sender->sendMessage(TextFormat::RED . "Flag already exists.");
                        }
                    }
                break;
                case 'tp':
                    if(!isset($args[1])){
                        $sender->sendMessage(TextFormat::AQUA . "/fs tp <name> [x] [y] [z]");
                        break;
                    }
                    if(isset($args[2]) and isset($args[3]) and isset($args[4])){
                        $name = $args[1];
                        $x = $args[2];
                        $y = $args[3];
                        $z = $args[4];
                        $level = $sender->getLevel();
                        if($this->main->changeFlagPos(new Position($x, $y, $z, $level), $name) === true){
                            $world = $level->getName();
                            $sender->sendMessage(TextFormat::GREEN . "Teleported flag to: $x, $y, $z, $world");
                        }else{
                            $sender->sendMessage(TextFormat::GREEN . "Could not find flag with that name.");
                        }
                    }else{
                        $name = $args[1];
                        $x = Math::floorFloat($sender->x);
                        $y = Math::floorFloat($sender->y);
                        $z = Math::floorFloat($sender->z);
                        $level = $sender->getLevel();
                        if($this->main->changeFlagPos(new Position($x, $y, $z, $level), $name) === true){
                            $world = $level->getName();
                            $sender->sendMessage(TextFormat::GREEN . "Teleported flag to: $x, $y, $z, $world");
                        }else{
                            $sender->sendMessage(TextFormat::GREEN . "Could not find flag with that name.");
                        }
                    }
                break;
                case 'custom':
                    if (!isset($args[1]) or !isset($args[2]) or !isset($args[3]) or !isset($args[4]) or !isset($args[5])){
                        $sender->sendMessage(TextFormat::AQUA . "/fs custom <name> <radius> <r-g-b> <r-g-b> <r-g-b> [x] [y] [z]");
                        return;
                    }
                    if(isset($args[6]) and isset($args[7]) and isset($args[8])){
                        $name = $args[1];
                        $radius = $args[2];
                        $x = $args[6];
                        $y = $args[7];
                        $z = $args[8];
                        $level = $sender->getLevel();
                        if($this->main->saveCustomFlag(new Position($x, $y, $z, $level), $radius, $name, $sender->getName(), $args[3], $args[4], $args[5]) === true){
                            $sender->sendMessage(TextFormat::AQUA . "Successfully saved flag!");
                        }else{
                            $sender->sendMessage(TextFormat::RED . "Flag already exists.");
                        }
                    }else{
                        $name = $args[1];
                        $radius = $args[2];
                        $x = Math::floorFloat($sender->x);
                        $y = Math::floorFloat($sender->y);
                        $z = Math::floorFloat($sender->z);
                        $level = $sender->getLevel();
                        if($this->main->saveCustomFlag(new Position($x, $y, $z, $level), $radius, $name, $sender->getName(), $args[3], $args[4], $args[5]) === true){
                            $sender->sendMessage(TextFormat::AQUA . "Successfully saved flag!");
                        }else{
                            $sender->sendMessage(TextFormat::RED . "Flag already exists.");
                        }
                    }
                break;
                case 'delete':
                    if(!isset($args[1]) or !isset($args[2])) {
                        $sender->sendMessage(TextFormat::AQUA . "/fs delete <flag/custom> <name>");
                        return;
                    }
                if(strtolower($args[1]) === "flag"){
                    $name = $args[2];
                    if($this->main->deleteFlag($name) === true){
                        $sender->sendMessage(TextFormat::GREEN . "Successfully deleted flag $name.");
                    }else{
                        $sender->sendMessage(TextFormat::GREEN . "Could not find flag with that name.");
                    }
                }
                if(strtolower($args[1]) === "custom"){
                    $name = $args[2];
                    if($this->main->deleteCustomFlag($name) === true){
                        $sender->sendMessage(TextFormat::GREEN . "Successfully deleted custom flag $name.");
                    }else{
                        $sender->sendMessage(TextFormat::GREEN . "Could not find custom flag with that name.");
                    }
                }
                break;
                default:
                    $sender->sendMessage(TextFormat::RED . $this->getUsage());
            }
        }
    }

    /**
     * @return Loader
     */
    public function getPlugin(){
        return $this->main;
    }
}
