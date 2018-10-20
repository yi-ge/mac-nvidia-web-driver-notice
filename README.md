# Mac Nvidia Web Driver Notice

> Mac 版本的 NVIDIA 显卡 WEB 驱动更新检测通知

如果检测到新版本的驱动发布，则自动发送邮件及短信给用户。

主要用于**MacOS Mojave 10.14 (18A391) Nvidia Web Driver**的检测。

**PHP 5.4+ MySQL 5.1+**

修改`config.php`文件即可。使用[`PAYJS`](https://payjs.cn/ref/DPEBRZ)作为个人微信支付接口。

首次启动请手工访问cron.php开启后台监控，后续用户访问首页将自动检查后台运行是否正常。

### 说明

由于 MacOS Mojave 10.14 (18A391) 版本的 NVIDIA 显卡 WEB 驱动一直没有发布，其中缘由不能说。因此忙里抽闲用PHP迅速的搭建了一个监控平台，并做成多用户版本。

系统每隔15s会自动检测一次列表，如果发现有新版本发布，则会发送邮件及手机短信通知，方便广大N卡用户。

访问地址：https://driver.wyr.me，填入邮箱或手机号即可。

手机短信收取成本费0.5￥，源代码开源：https://github.com/yi-ge/mac-nvidia-web-driver-notice

**特别说明**：项目非盈利，填邮箱是免费的。此项目收集的邮箱和手机号不会对任何人公开。手机短信收费仅为帮助别人但自己不亏。如有需要，可以自行搭建。

<p style="width: 100%;text-align: center;">捐赠</p>
<p style="width: 100%;text-align: center;"><img src="donate.png" /></p>