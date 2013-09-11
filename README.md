#Server Port Online Check with Notifications
This is a script that helps you to get notified if your server or a service on your server is down.
It currently supports E-Mail and [Pushover](https://pushover.net/) notifications.

##How to use
The script is pretty easy to use. The only thing you will have to do is to run it as a cronjob. In the following example it checks the server status every 10 minutes.

```bash
*/10 * * * * php /path/to/file/server_online_check.php
```

##License
Server-Port-Online-Check notifies you when a server or service is offline.
Copyright (c) 2013 Martin Schurig

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses/.
