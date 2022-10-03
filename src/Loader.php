<?php

declare(strict_types=1);

namespace skh6075\AbsoluteSpawn;

use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

final class Loader extends PluginBase{
	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvent(PlayerJoinEvent::class, function(PlayerJoinEvent $event): void{
			$this->toSpawn($event->getPlayer(), true);
		}, EventPriority::MONITOR, $this, false);
		$this->getServer()->getPluginManager()->registerEvent(PlayerMoveEvent::class, function(PlayerMoveEvent $event): void{
			if(!$event->isCancelled() && $event->getTo()->getFloorY() <= 0){
				$this->toSpawn($event->getPlayer());
			}
		}, EventPriority::MONITOR, $this, false);
	}

	private function toSpawn(Player $player, bool $absolute = false): void{
		if(!$absolute && $player->hasPermission(DefaultPermissions::ROOT_OPERATOR)){
			return;
		}
		$this->getScheduler()->scheduleTask(new ClosureTask(fn() => $player->teleport($this->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn())));
	}
}