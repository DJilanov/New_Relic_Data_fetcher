#Description

The easy configurable php data fetcher that can get all the info for your monitored servers directly from Pingdom API

##When to use

[![Pingdom home](Screenshots/PingdomHome.png)](Screenshots/PingdomHome.png)

The main reason I created that plugin was because I lost a few days while searching through the web about how to get the info directly from their API, since I really dislike their public status page. I ended up having to read a bunch of poorly written documentation. I do not want the same thing for you... you can just use the plugin.  It is quite easy to be used. Set the cron how often you want to pick the data and just use it in your custom made status page.

* Supports almost all of the servers (uses php curl that is basically pre-set on every Unix machine)
* Support is easy to be handled (just set the cron job which I cover later, auto fetch the data and then save it)
* Can be used on every Web page
* Customisable style (it is explained in a way which does not require any knowledge of programming to grasp)

### Installation

1. Go to the Terminal. 

2. Type "crontab -e" and choose nano.

3. You will see this field

[![Cron home](Screenshots/cron-large.png)](Screenshots/PingdomHome.png)

4. You will see that row

[![Cron home](Screenshots/cron-target.png)](Screenshots/PingdomHome.png)

Where each value stands for:

1. Minute (0-59)
2. Hours (0-23)
3. Day (0-31)
4. Month (0-12 [12 == December])
5. Day of the week(0-7 [7 or 0 == sunday])
6. /path/to/command - Script or command name to schedule

5. Set the main php script contained in Plugin/get_pingdom_data.php to be run at least 1 time on a daily basis.

6. You can actually manually set eveything the script needs to run. Now you just need to put your info into the settings.json that looks like this:

[![Settings json](Screenshots/settings.png)](Screenshots/settings.png)

7. There is inline documentation but ill still explain what to do.

1.1. The row under comment 1 is app-key. You must get it from your Pingdom acc and put it here.

1.2. The row under comment 2 is for the user. You must first type your username, then the symbol ":" and finally your password next to it.

1.3.  In the servers tab you only type the last numbers here and leave just the amount of servers you need (add or remove rows in the json)

[![Cron home](Screenshots/servers.png)](Screenshots/Servers.png)

1.4. If you want to change something first be sure you know what is JSON and if you dont know read here http://en.wikipedia.org/wiki/JSON

#### Example Usage

The result will be into the Plugin/storagePingdom.json that looks like this.

[![Cron home](Screenshots/result.png)](Screenshots/result.png)

There is a summary for each server. "days" holds the 10 days that pingdom returns as output. "Uptime" is the total uptime for the day displayed in seconds (unix time). "avgresponse" relates to the average required calltime and startime which displays when the day gets started.

## Requirements

* PHP
* CURL
* CRON

## Contact

[Dimitar Jilanov](http://jilanov.com)   
[@DimitarJilanov](https://twitter.com/DimiturJilanov)

## License

Pingdom Data Fetcher is available under the MIT license. See the LICENSE file for more info.

## Special thanks 

Special thanks to Emil Katzarski for the code assistance while creating it!

Special thanks to Stefo Danchovski for the documentation assistance while creating it!