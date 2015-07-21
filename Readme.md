# Content Code

This module allows you to add unique codes for contents. It may be used to retrieve contents with codes instead of IDs.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ContentCode.
* Activate it in your thelia administration panel

### Composer

Execute this in a terminal, in your thelia directory:

```
$ composer require thelia/content-code-module:~1.1
```

or add it in your main thelia composer.json file

```
"require": {
 "thelia/content-code-module": "~1.1"
}
```

## Usage

After having activate the module, go to your content's "module" tab, type your code and click submit.

## Hook

This module uses the ```content.tab-content``` hook to add the code form.


## Loop

[content-code]

### Input arguments

|Argument |Description |
|---      |--- |
|**id** | The content_code ID |
|**content_id** | The content ID |
|**code** | The content code |
|**order** | The order to retrieve the content codes. Values: id, id-reverse, content_id, content_id-reverse, code, code-reverse |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID    | The content_code ID|
|$CONTENT_ID  | The content ID |
|$CODE  | The content code |

### Exemple

```smarty
{loop type="content-code" name="content-code" code="foo"}
    {loop type="content" name="content" id=$CONTENT_ID}
        ...
    {/loop}
{/loop}
```
