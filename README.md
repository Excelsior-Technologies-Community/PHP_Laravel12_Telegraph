# PHP_Laravel12_Telegraph

## Project Description

PHP_Laravel12_Telegraph is a Laravel 12 based application that demonstrates how to integrate a Telegram Bot using the DefStudio Telegraph package.

This project allows the Laravel application to communicate with Telegram by sending messages directly to users through a Telegram bot. It uses the official Telegram Bot API and stores bot and chat information in the MySQL database.

The Telegraph package simplifies Telegram bot integration by providing ready-made models, migrations, and methods for sending messages, managing chats, and handling bot communication without writing complex API code.


## Features

• Laravel 12 integration with Telegram Bot
• Send messages from Laravel to Telegram user
• Secure bot token configuration using .env
• Database storage for bots and chats
• Simple and clean controller-based message sending
• Beginner-friendly implementation
• Uses official DefStudio Telegraph package



## Technologies Used

• Laravel 12
• PHP 8+
• MySQL
• Telegram Bot API
• DefStudio Telegraph Package
• Composer


## Use Cases

• Telegram notification system
• Alert system for applications
• Chat automation
• Admin notification bot
• Real-time message integration


---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Telegraph "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Telegraph

```

#### Explanation:

This command installs a fresh Laravel 12 project with all required core files.

It creates the project folder and prepares the Laravel environment.




## STEP 2: Database Setup 

### Open .env and set:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Telegraph_laravel12
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: Telegraph_laravel12

```

#### Explanation:

This step connects Laravel application with MySQL database so Telegraph can store bot and chat information.




## STEP 3: Install Telegraph Package

### Use composer to install the package:

```
composer require defstudio/telegraph

```

#### Explanation:

This installs the Telegraph package which provides easy integration between Laravel and Telegram Bot API.





## STEP 4: Publish Telegraph Files

### Publish migration:

```
php artisan vendor:publish --tag="telegraph-migrations"

```

### Publish config:

```
php artisan vendor:publish --tag="telegraph-config"

```

### Run:

```
php artisan migrate

```

#### Explanation:

This creates required database tables like telegraph_bots and telegraph_chats and publishes configuration file.





## STEP 5: Create Telegram Bot using BotFather

1. Open Telegram

2. Search:

```
BotFather

```

3. This is official Telegram bot creator by Telegram.

4. Send:

```
/start

```

5. Then send:

```
/newbot

```

6. Enter:

```
Bot Name: Laravel Telegraph Bot

```

7. Enter username:

```
laravel_telegraph_bot

```

8. You will get BOT TOKEN:

Example:

```
123456789:ABCDEFxxxxxxxxxxxxxxxxxxxx

```

9. Copy this token.


#### Explanation:

BotFather is official Telegram tool to create bots. It generates unique BOT TOKEN used for authentication.





## STEP 6: Add Token in .env

### Open .env

##### Add:

```
TELEGRAPH_BOT_TOKEN=123456789:ABCDEFxxxxxxxxxxxx

```


#### Explanation:

This token connects your Laravel application with your Telegram bot securely.





## STEP 7: Start Chat with Your Bot

1. Open Telegram

2. Search your bot:

```
laravel_telegraph_bot

```

3. Click:

```
START

```

#### Explanation:

This registers your Telegram account as a chat so Laravel can send messages to you.



## STEP 8: Create Controller to Send Message

### Run command:

```
php artisan make:controller TelegramController

```

### File created: app/Http/Controllers/TelegramController.php

```
<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Models\TelegraphBot;

class TelegramController extends Controller
{
    public function sendMessage()
    {
        $bot = TelegraphBot::first();

        $chat = $bot->chats()->first();

        Telegraph::bot($bot)
            ->chat($chat->chat_id)
            ->message('Hello from Laravel 12 Telegraph!')
            ->send();

        return "Message sent successfully!";
    }
}

```

#### Explanation:

This controller fetches bot and chat from database and sends message using Telegraph package.





## STEP 9: Add Route

### Open: routes/web.php

#### Add:

```
use App\Http\Controllers\TelegramController;

Route::get('/send-message', [TelegramController::class, 'sendMessage']);

```

#### Explanation:

This creates URL endpoint to trigger message sending from browser.





## STEP 10: Create Bot Record in Database

### Run:

```
php artisan tinker

```

1. Get bot first

```
use DefStudio\Telegraph\Models\TelegraphBot;

$bot = TelegraphBot::first();

```

#### You should see output like:

```
DefStudio\Telegraph\Models\TelegraphBot {#....}

```

2. Create chat using bot relation (IMPORTANT)

```
$bot->chats()->create([
    'chat_id' => '8*********',
    'name' => 'User Name'
]);

```

#### This automatically sets:

```
telegraph_bot_id = 1

```

3. Exit tinker

```
exit

```

#### Explanation:

This stores your Telegram chat ID in database and links it with bot.






## STEP 11: Test Message Sending

### Run server:

```
php artisan serve

```

### Open browser:

```
http://127.0.0.1:8000/send-message

```

### Output:

```
Message sent successfully!

```

<img width="1919" height="919" alt="Screenshot 2026-02-25 145534" src="https://github.com/user-attachments/assets/767abd28-e123-4bc9-93e1-b1d5ebd52a94" />


### Check Telegram.

#### You will receive:

```
Hello from Laravel 12 Telegraph!

```


<img width="326" height="38" alt="Screenshot 2026-02-25 145614" src="https://github.com/user-attachments/assets/e66e9f03-586e-46a0-ab64-5b627e58bb3b" />


SUCCESS!

#### Explanation:

This sends message from Laravel application to Telegram bot successfully.





# Project Folder Structure:

```
PHP_Laravel12_Telegraph
│
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── TelegramController.php
│
├── config
│   └── telegraph.php
│
├── database
│   └── migrations
│
├── routes
│   └── web.php
│
├── .env
│
└── composer.json

```
