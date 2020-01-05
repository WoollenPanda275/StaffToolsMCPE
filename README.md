![](https://poggit.pmmp.io/shield.state/StaffTools)](https://poggit.pmmp.io/p/StaffTools)
<a href="https://poggit.pmmp.io/p/StaffTools"><img src="https://poggit.pmmp.io/shield.state/StaffTools"></a>

# StaffToolsMCPE
This PocketMine-MP plugin provides you some tools to help detect cheaters or hackers in your server.
## Before Installing
It is highly recommended to use the **Classic Interface** by going to Settings > Video in your game.
There's an addictional feature that you can unlock by installing [PreciseCpsCounter from luca28pet](https://github.com/luca28pet/PreciseCpsCounter): the CPS Test, which counts how many clicks per second the player gets and what is his average.
## Features
### Compass
To have access to the features, you firstly want to type ```/tools``` (make sure you've got the permission ```tools.command``` set) and you will be given a compass. To open the tools menu, simply left click on a player and a GUI will open.
### Logs
This feature is really useful if your staff members are offline. You can set it to false by clicking the book item and whenever the plugin will detect that a user is cheating it will write it down in a file called ```"playerName.txt"``` which is in the folder ```resources/logs```. To disable this feature, click the book again or keep it on off.
### Warnings
This feature will show you how many times the plugin has detected the usage of hitbox/reach by a player.
__IMPORTANT__: a lot of warnings could show, because  if a user is lagging or actually cheating, the plugin will detect every hit the server registers. To be more sure on the situation, always check the logs file.
### Alias
This feature shows you every account linked to the same IP. For more informations, see [Alias](https://github.com/poggit-orphanage/Alias).
### Ping
Shows you the current ping of the player. Refresh it by closing and opening the menu.
