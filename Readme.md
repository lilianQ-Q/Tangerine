# Tangerine - Template engine
Tangerine is a project to introduce myself with the concept of templating engine.<br><br>

## Prerequisites
- Php8.0
- PhpAutoloader (Stable Release)

<br>

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
3. If you want to run library's tests, don't forget to download the lib's dependencies. Project's dependencies are listed in Tangerine/conf/tinypm.json)
	```bash
	$> make libupdate
	$> make tests
	```
## Usage
<br>

### Declaration
First, create a Engine object and then you'll be able to render html files like this
```php
use Tangerine\Engine;

$engine = new Engine('path/to/cache/folder', 'path/to/views/folder');

$engine->render('index.html');
```
<br>

### Template file
<br>

#### Yields & Blocks
A common usage of yield section is this one. In your layout file declare a yield section. 
```html
<!-- baselayout.html in views folder -->
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Test</title>
	</head>
	<body>
		{% yield content %}
	</body>
</html>
```
Then in your index view, extends this layout and declare a new block.
```html
<!-- index.html in views folder -->
{% extends layout.html %}

{% block content %}

<div class="demo_content">
	<p>Welcome to Tangerine engine, nice to meet you !</p>
</div>

{% endblock %}
```

And your result will be this after rendering with the Tangerine engine
```html
<!-- This is what you'll get, and cached if enabled -->
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Test</title>
	</head>
	<body>
		<div class="demo_content">
			<p>Welcome to Tangerine engine, nice to meet you !</p>
		</div>
	</body>
</html>
```

Basicly, a yield section needs to be filled via a block section. Pretty simple right ?<br><br>

### Extends and includes
For convention, when you want to extend a layout use the keywork extends.
But if you want to include another view part, use the keywork include.
<br><br>

## Snippets

To easily write code faster you'll find snippets support for vscode in support folder of the project.<br><br>


## Future Features

I'm currently learning how to create a langage support so you can have a better highlighting support for the engine.<br><br>

## Contact
Lilian Damiens - <a href="https://twitter.com/damiens_lilian">@damiens_lilian</a> - <a href="mailto:lilianpierredamiens@gmail.com">lilianpierredamiens@gmail.com</a>




<br><br>
## Acknowledgments
- Thanks to David Adams and his article called <a href="https://codeshack.io/lightweight-template-engine-php/">Lightweight Template Engine with PHP</a> released in September 2020 on <a href="https://codeshack.io">codeshack.io</a>. 
It was really helpful to me especially the regex part, I really learned a lot. My ambitions are to add functionnalities by myself on this repo and use it for my own clients.