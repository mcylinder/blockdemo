# BlockDemo

## Requirements

At a minimum you need to have:

* [Composer](https://getcomposer.org) >= 1.0
* [VirtualBox](https://www.virtualbox.org/) >= 5.1
* [Vagrant](https://www.vagrantup.com/) >= 1.8.4
* [Node.js](https://nodejs.org) >= 8.0
* [NPM](https://www.npmjs.com/) >= 5.0

## Local environment setup

**Clone this repository** into a working directory (e.g., `~/Code`)
  ```shell
  $ git clone https://github.com/mcylinder/blockdemo.git blockdemo
  ```

**Setup your Homestead.yaml file** (Homestead is a Vagrant based Ubuntu 16.04 virtual machine)
  ```shell
  # @ ~/Code/blockdemo
  $ cp Homestead.example.yaml Homestead.yaml
  # Update `folders.map` with your local absolute path to the blockdemo repository by replacing <FULL_PATH_TO_FOLDER>.
  # Change the IP <LOCAL_VM_IP> at the top of that file if you have another vm running at the same IP
  ```

**Setup your hosts file**  
  ```shell
  $ sudo -- sh -c "echo '<LOCAL_VM_IP> blockdemo.test' >> /etc/hosts"
  $ sudo -- sh -c "echo '<LOCAL_VM_IP> admin.blockdemo.test' >> /etc/hosts"
  ```

**Setup your .env file**  
  ```shell
  # @ ~/Code/blockdemo
  $ cp .env.example .env
# Update DB_HOST=<LOCAL_VM_IP> to the ip address the VM is using.
  ```

**Install Composer dependencies**    
  ```shell
  # @ ~/Code/blockdemo
  $ composer install
  ```

**Fire up the virtual machine** (be patient!)  
  ```shell
  # @ ~/Code/blockdemo
  $ vagrant up
  ```

**SSH into the virtual machine**
  ```shell
  # @ ~/Code/blockdemo
  $ vagrant ssh
  ```

**Setup and add a superadmin user for yourself in the local CMS**  
  ```shell
  # @ /home/vagrant
  $ cd blockdemo && php artisan key:generate && php artisan migrate && php artisan twill:superadmin
  ```
## Block Rules
Each entity has a set of requirements for blocks used in their respective editor. The rules 
are sequence based. Each step is either _required_ or _optional_. 
### Example
The following array is configured in the `Article.php` model. 
- The first rule requires the first block to be a header.
- The next rule allows for either an image block or a wysiwyg block. 
Up to three of either block is allowed. As long as no more than three are used. 
It's optional so zero blocks can be chosen for this slot.
- The final rule requires the last block be a footer.  
Note the rules are stated in a basic language structure.  
_[must have/may have]_  _[number]_ of _[block]_ or _[block]_ or _[block]_ or ...

```php
    public $blockEditorRules = [
        'must have 1 of header',
        'may have 3 of wysiwyg or image ',
        'must have 1 of   footer',
    ];
```
### Validate
For the sake of the demo, the bottom of the `Article` form is a _Validate blocks_ 
button.   
This makes an ajax call to the backend and the response is `JSON`. This approach is just for
demo's sake and a much more artful integration could be achieved leveraging Vue.    

Example of a response  

```json
{
    "valid": false,
    "choice_step": 1,
    "current_block": "image",
    "possible_blocks": [
        "wysiwyg",
        "hero"
    ]
```
The `choice_step` is an array pointer. This is referencing the _second_ choice in the block editor.
A suggestion of a different choice is in the `possible_blocks` array.
