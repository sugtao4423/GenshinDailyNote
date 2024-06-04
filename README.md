# GenshinDailyNote
樹脂確認を怠るな

* [in shell](#in-shell)
* [in Slack](#in-slack)
* [Slack resin notify](#slack-resin-notify)

## in shell
```
$ php GenshinDailyNote.php userAliasName
Resin: 82 / 160
Resin Recovery: 10:23:27 left (at 05:08)
Daily Commissions: 0 / 4
Got Commission Reward: No
Expeditions: 5 / 5
  Expedition#1: 14:53:45 left
  Expedition#2: 14:53:45 left
  Expedition#3: 14:53:45 left
  Expedition#4: 14:53:45 left
  Expedition#5: 14:53:45 left
Home Coin: 780 / 2400
Home Coin Recovery: 53:55:27 left (at 01/28 00:40)
```

## in Slack
[Slack API: Applications](https://api.slack.com/apps)

Slash Commands: `/genshin` `/resin` `/daily` `/expedition` `/home`  
Usage: `command [user alias name]`

### /genshin
<img src="https://user-images.githubusercontent.com/8792860/150952692-e605aa38-7f88-456e-81e2-cbde6cccc820.png" alt="genshin" width="300px">

### /resin
<img src="https://user-images.githubusercontent.com/8792860/150952703-ceed75ff-5a04-4ab5-bd0e-32612b060810.png" alt="resin" width="300px">

### /daily
<img src="https://user-images.githubusercontent.com/8792860/150952705-13d1f7b4-6822-487b-a217-a19ec573d9c4.png" alt="daily" width="300px">

### /expedition
<img src="https://user-images.githubusercontent.com/8792860/150952707-d0e7ce91-6fa0-451f-9743-70692641efa0.png" alt="expedition" width="300px">

### /home
<img src="https://user-images.githubusercontent.com/8792860/150952709-9f268895-333b-4547-be3c-9491d5ef4428.png" alt="home" width="300px">

## Slack resin notify
short | long | required | description
--- | --- | --- | ---
-s | --send-slack | Yes | Make it clear that this is a notification to Slack.
-a | --all-users | Either this or `-u` | Send to all users at 5-second intervals.
-u | --user-alias | Either this or `-a` | Send to user specified by alias.
-o | --resin-over | Yes | {number} >= resin.
-n | --not-resin-over | Yes | {number} < resin.

#### eg.
```
$ crontab -l
0 */2 * * * php GenshinDailyNote.php -s -u {user alias name} -o 160 -n 200
```
