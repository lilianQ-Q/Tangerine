# Introduction
Tangerine is a project to introduce myself with the concept of templating engine.

## Prerequisites
- Php8.0
- PhpAutoloader (Stable Release)
## Installation
1. Clone the repo
	```bash
	$> git clone https://lilianQ-Q/Tangerine.git
	```
2. Configure your autoloader
	```json
	{
		"Tangerine\\" : "path/to/tangerine/src/folder"
	}
	```
3. If you want to run library's tests, don't forget to download the lib's dependencies (That are listed in Tangerine/conf/tinypm.json)
	```bash
	$> make libupdate
	$> make tests
	```
## Usage
First, create a Engine object and then you'll be able to render html template files
```php
use Tangerine\Engine;

$engine = new Engine('path/to/cache/folder', 'path/to/views/folder');

$engine->render('index.html');
```
	
## Contact
Lilian Damiens - <a href="https://twitter.com/damiens_lilian">@damiens_lilian</a> - <a href="mailto:lilianpierredamiens@gmail.com">lilianpierredamiens@gmail.com</a>

## Acknowledgments
- Thanks to David Adams and his article called "Lightweight Template Engine with PHP" released in September 2020 on <a href="https://codeshack.io/lightweight-template-engine-php/">codeshack.io</a>. 
It was really helpful to me especially the regex part, I really learned a lot. My ambitions are to add functionnalities by myself on this repo now.