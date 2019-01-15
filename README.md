# SSOUP

This work is protected by the DBAD (Dont Be A Dick) License. http://www.dbad-license.org


SSOUP is a piece of software that allows you quickly and quietly upload files to a specific set of folders on the go, without an FTP client, passwords, security keys, etc.  

How does it work? Well. it's a bit like Imgur. You upload a file, and it gives you a link to the file. Unlike Imgur, you can upload more than just images, and you get to keep them on your own server. 


## Installation

How do I install this software, you ask? Well, it's very simple. You don't need to log into your shell, run special terminal commands or anything. All you have to do, is copy the three files into whatever folder you want to launch it from.

There's no databse configuration, no crazy files or ports.
 1.  Copy `index.php`, `script.js`, and `style.css` into the directory you would like.
 2.  Done. Seriously, that's it.


## Configuration

The configuration is pretty straight-forward, but I'll go over it line-by-line.

The configuration is located inside of `index.php`, at the top.

- `$file_size_limit` is the maximum accepted file size, in **megabytes**. You can change this as high as you want, but you **must** make sure that your `php.ini` configs; `upload_max_filesize` and `post_max_size` are **equal or higher** than whatever value you put inside the configuration. If they aren't then the script can't accept the file. You won't even get an error. :(

- `$storage_folder` is the folder where SSOUP stores all of the uploaded files. This means that you can specify a folder to save to, that isn't in the directory the rest of the script is in. 
- `$allowed_directories` is an array containing all of the folder options. What would you do with this? well, lets say you want a **specific** folder for images, a specific folder for files, and another folder for random things. Instead of all these files being put into one folder, you can separate them. it would look something like `$allowed_directories = ["images", "files", "misc"];` Now, when you go to upload a file, these would be your save options.
- `$administrator_PIN` is the *password* that authenticates the upload. If this wasn't here, anybody could upload anything they wanted. This can be **as long as you want it to be**, and it can also be **text instead of numbers**. I would change this from the default of `1234`, but that's just me.


## Security

Security options. They're important. They prevent somebody from completely deleting everything and screwing your server up.

- `$allow_indexing` will allow indexing of the folders that files are uploaded to. This can be a good thing, or a bad thing. If this is enabled, it means that anyone can see every file inside the folder.
- `$allow_executable`. This is one of the most important, and possibly *deadly* settings. It allows PHP, JavaScript, and any other compiled script to run. If somebody were to upload a php script that deleted everything, this line would block it. I recommend leaving this feature set to **false**.
- `$allow_read_only` is a flag that lets you read compiled files. If I were to upload a php document and this flag were set to `true`, I would be able to read the text of the php file, instead of running it or downloading it (depending on your configuration). This also works for html documents, Javascript, just about everything.
- `$show_debug` is a small debug feature. If it's enabled, it will show you (on the page) a few debug messages. Most of the time, there won't be any messages to show. I'd enable this if the script is having issues creating folders, saving files, etc.
