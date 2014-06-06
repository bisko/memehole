Readme
==============


Overview
--------------

The project is aimed at people who like to procrastinate by spending time looking at some AdviceAnimals memes and so on. 
It satisfies you with the pleasure of seeing a meme and at the same time get mocked by it for not concentrating on the task at hand.


Installation
--------------

- Get the project hosted somewhere where you have access to the default vhost, e.g. local apache + php running.
- Add the project as the default VHost when you reach the IP
- Check the configuration at the top of `memehole.php`
- Add a font of your choice for the meme text. **Impact** is the most popular fonts for memes, but it is not included as licensing conflicts may arise from that.
 - The font **must** be in the root of the project (next to `index.php` and `memehole.php`)
 - Change the name of the file accordingly in the configuration at the top of `memehole.php`
- Add the domains of your favorite time-loosing sites, pointing to the IP of where you hosted the project to the hosts file on the system, e.g.:
```
127.0.0.1 reddit.com
127.0.0.1 www.reddit.com
127.0.0.1 9gag.com
127.0.0.1 www.9gag.com
127.0.0.1 facebook.com
127.0.0.1 www.facebook.com
127.0.0.1 memecenter.com
127.0.0.1 www.memecenter.com
127.0.0.1 funnyjunk.com
127.0.0.1 www.funnyjunk.com
```

- Save the changes and profit! :)
