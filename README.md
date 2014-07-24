# CSS Analyser

A PHP class to analyse a string of CSS to produce statistics. It is able to get the following information about a string of CSS:

- Number of rules
- Number of selectors
- Number of media queries
- Number of property definitions
- Size (in bytes)

The class assumes that your CSS is valid - always run your code through a [CSS Lint](http://csslint.net "CSS Lint") tool first. All comments get stripped from the CSS.

_Yes, analyser is spelt right (with a S not a Z) - I'm British!_

## Installation / Setup
### The proper way
Use [Composer](https://getcomposer.org "Composer") - simply css_analyser to your composer.json file:

    {
    	"require": {
    		"gbuckingham89/css_analyser": "dev-master"
    	}
    }

### The other way
Simply download and extract the [ZIP](https://github.com/gbuckingham89/css_analyser/archive/master.zip "Download ZIP") into your project and include the class (css_analyser.php) using `require()` or `include()` in the most appropriate place for your project.

## Usage

Input CSS and return all of the results as an array:

    $css_string = ".example-css { color: red; }";
    $analyser = new \gbuckingham89\css_analyser();
    $results = $analyser->process($css_string);

Or, if you only need one or two or the results, simply call the relevant m ethod:

	$css_string = ".example-css { color: red; }";
    $analyser = new \gbuckingham89\css_analyser();
    $analyser->process($css_string, false);
    $number_of_rules = $analyser->get_rules_count();
    $size = $analyser->get_size();

## Contributing

Spotted any bugs? Got an idea for a new feature? Want to help improve? Open an [issue](https://github.com/gbuckingham89/css_analyser/issues "Open an issue") and if you can supply the fix, submit a [pull request](https://github.com/gbuckingham89/css_analyser/pulls "Pull request").

## Author
George Buckingham - [www.georgebuckingham.com](www.georgebuckingham.com "George Buckingham")

## License
The MIT License (MIT)

Copyright (c) 2014 George Buckingham

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
